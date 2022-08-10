<?php

namespace agent\modules\v1\controllers;

use agent\models\Plan;
use agent\models\Subscription;
use agent\models\SubscriptionPayment;
use common\components\TapPayments;
use common\models\Addon;
use common\models\AddonPayment;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;


class AddonController extends Controller
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count'
                ],
            ],
        ];

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    /**
     * @return ActiveDataProvider
     */
    public function actionList() {

        $keyword = Yii::$app->request->get('keyword');

        $query =  Addon::find()
            ->filterKeyword($keyword)
            ->orderBy('sort_number');

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Return Addon detail
     * @param integer $addon_uuid
     * @return Addon
     */
    public function actionDetail($id) {
        return $this->findModel($id);
    }

    /**
     * confirm plan for current store
     * @param $id
     * @return array|string[]
     */
    public function actionConfirm()
    {
        $addon_uuid = Yii::$app->request->getBodyParam ('addon_uuid');
        $payment_method_id = Yii::$app->request->getBodyParam ('payment_method_id');

        $addon = $this->findModel($addon_uuid);

        $store = Yii::$app->accountManager->getManagedAccount ();

        $payment = new AddonPayment;
        $payment->restaurant_uuid = $store->restaurant_uuid;
        $payment->payment_mode = $payment_method_id == 1 ? TapPayments::GATEWAY_KNET : TapPayments::GATEWAY_VISA_MASTERCARD;
        $payment->addon_uuid = $addon_uuid;
        $payment->payment_amount_charged = $addon->special_price > 0 ? $addon->special_price: $addon->price;
        $payment->payment_current_status = "Redirected to payment gateway";

        if (!$payment->save ()) {
            return [
                'operation' => 'error',
                'message' => $payment->getErrors ()
            ];
        }

        // Redirect to payment gateway
        Yii::$app->tapPayments->setApiKeys (\Yii::$app->params['liveApiKey'], \Yii::$app->params['testApiKey']);

        $response = Yii::$app->tapPayments->createCharge (
            "KWD",
            $addon->name . " for " . $store->name, // Description
            'Plugn - ' . $addon->name, //Statement Desc.
            $payment->payment_uuid, // Reference
            $payment->payment_amount_charged,
            $store->name,
            $store->getAgents ()->one ()->agent_email,
            $store->country->country_code,
            $store->owner_number ? $store->owner_number : null,
            0, //Comission
            Url::to (['addons/callback'], true),
            $payment_method_id == 1 ? TapPayments::GATEWAY_KNET : TapPayments::GATEWAY_VISA_MASTERCARD,
            0,
            0,
            ''
        );

        $responseContent = json_decode ($response->content);

        //try {

        // Validate that theres no error from TAP gateway
        if (isset($responseContent->errors)) {

            $errorMessage = "Error: " . $responseContent->errors[0]->code . " - " . $responseContent->errors[0]->description;

            //todo: notify vendor?
            //\Yii::error ($errorMessage, __METHOD__); // Log error faced by user

            return [
                'operation' => 'error',
                'message' => $errorMessage
            ];
        }

        if ($responseContent->id) {

            $chargeId = $responseContent->id;
            $redirectUrl = $responseContent->transaction->url;

            $payment->payment_gateway_transaction_id = $chargeId;

            if (!$payment->save (false)) {

                //\Yii::error ($payment->errors, __METHOD__); // Log error faced by user

                return [
                    'operation' => 'error',
                    'message' => $payment->getErrors ()
                ];
            }
        } else {

            //\Yii::error ('[Payment Issue > Charge id is missing ]' . json_encode ($responseContent), __METHOD__); // Log error faced by user

            return [
                'operation' => 'error',
                'message' => Yii::t('agent','Payment Issue > Charge id is missing')
            ];
        }

        return [
            'operation' => 'success',
            'redirect' => $redirectUrl
        ];

        /*} catch (\Exception $e) {

            if ($payment)
                Yii::error ('[TAP Payment Issue > ]' . json_encode ($payment->getErrors ()), __METHOD__);

            Yii::error ('[TAP Payment Issue > Charge id is missing]' . json_encode ($responseContent), __METHOD__);

            return [
                'operation' => 'error',
                'message' => json_encode ($responseContent)
            ];
        }*/
    }

    /**
     * Process callback from TAP payment gateway
     * @param string $tap_id
     * @return mixed
     */
    public function actionCallback()
    {
        try {

            $id = Yii::$app->request->get('tap_id');

            $paymentRecord = AddonPayment::updatePaymentStatusFromTap($id);
            $paymentRecord->received_callback = true;
            $paymentRecord->save(false);

            if ($paymentRecord->payment_current_status == 'CAPTURED') {
                $this->_addCallbackCookies ("paymentSuccess", "There seems to be an issue with your payment, please try again.");
            } else {
                $this->_addCallbackCookies ("paymentFailed", $paymentRecord->plan->name . ' has been activated');
            }

        } catch (\Exception $e) {

            $this->_addCallbackCookies ("paymentFailed", $e->getMessage ());
        }

        //todo: show success/failed message in addon page

        $url = Yii::$app->params['newDashboardAppUrl'] . '/addons';

        return $this->redirect ($url);
    }

    /**
     * add cookies to show error message in front app
     */
    private function _addCallbackCookies($name, $msg)
    {
        $cookie = new Cookie([
            'name' => $name,
            'value' => $msg,
            'expire' => time () + 86400,
            'domain' => Yii::$app->params['dashboardCookieDomain'],
            'httpOnly' => false,
            'secure' => str_contains (Yii::$app->params['newDashboardAppUrl'], 'https://')? true: false,
        ]);

        $cookie->sameSite = PHP_VERSION_ID >= 70300 ? 'None' : null;

        \Yii::$app->getResponse ()->getCookies ()->add ($cookie);
    }

    /**
     * Finds the Area  model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Addon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($addon_uuid)
    {
        if (($model = Addon::findOne ($addon_uuid)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}