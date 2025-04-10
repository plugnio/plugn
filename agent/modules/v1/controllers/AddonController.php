<?php

namespace agent\modules\v1\controllers;

use agent\models\Currency;
use common\models\MailLog;
use common\models\RestaurantAddon;
use Yii;
use common\components\TapPayments;
use agent\models\Addon;
use common\models\AddonPayment;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;


class AddonController extends BaseController
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'callback', 'payment-webhook'];

        return $behaviors;
    }

    /**
     * @return ActiveDataProvider
     * 
     * @api {get} /addons Get addons list
     * @apiName GetAddonsList
     * @apiGroup Addon
     *
     * @apiSuccess {Array} List of addons.
     */
    public function actionList() {

        $keyword = Yii::$app->request->get('keyword');

        $query =  Addon::find()
            ->orderBy('sort_number');

        if($keyword) {
            $query->filterKeyword($keyword);
        }    
        
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Return Addon detail
     * @param integer $addon_uuid
     * @return Addon
     * 
     * @api {get} /addons/:id Get addon detail
     * @apiName GetAddonDetail
     * @apiGroup Addon
     *
     * @apiSuccess {Array} Addon detail.
     */
    public function actionDetail($id) {
        return $this->findModel($id);
    }

    /**
     * confirm payment for addon
     * @param $id
     * @return array|string[]
     * 
     * @api {post} /addons/confirm Confirm payment for addon
     * 
     * @apiParam {string} addon_uuid Addon UUID.
     * @apiParam {string} payment_method_id Payment method ID.
     * @apiParam {string} token Token.
     * 
     * @apiName ConfirmAddon
     * @apiGroup Addon
     *
     * @apiSuccess {Array} Addon confirmed successfully.
     */
    public function actionConfirm()
    {
        $addon_uuid = Yii::$app->request->getBodyParam ('addon_uuid');
        $payment_method_id = Yii::$app->request->getBodyParam ('payment_method_id');
        $token = Yii::$app->request->getBodyParam ('token');

        $addon = $this->findModel($addon_uuid);

        $store = Yii::$app->accountManager->getManagedAccount (null, false);

        $payment = new AddonPayment;
        
        $payment->restaurant_uuid = $store->restaurant_uuid;
        $payment->payment_mode = $payment_method_id == 1 ? TapPayments::GATEWAY_KNET : TapPayments::GATEWAY_VISA_MASTERCARD;
        $payment->addon_uuid = $addon_uuid;
        $payment->payment_amount_charged = $addon->special_price > 0 ? $addon->special_price: $addon->price;
        $payment->payment_current_status = "Redirected to payment gateway";
        $payment->is_sandbox = false;//$store->is_sandbox;
        $payment->payment_token = $token;

        if (!$payment->save ()) {
            return [
                'operation' => 'error',
                'message' => $payment->getErrors ()
            ];
        }

        // Redirect to payment gateway
        Yii::$app->tapPayments->setApiKeys (
            \Yii::$app->params['liveApiKey'],
            \Yii::$app->params['testApiKey'],
            $payment->is_sandbox
        );

        if(!$token) {
            $token = $payment_method_id == 1 ? TapPayments::GATEWAY_KNET :
                TapPayments::GATEWAY_VISA_MASTERCARD;
        }

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
            Url::to(['addons/payment-webhook'], true),
            $token,
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
                'message' => $errorMessage,
                "response" => $responseContent
            ];
        }

        if ($responseContent->id) {

            $chargeId = $responseContent->id;
            $redirectUrl = $responseContent->transaction->url;

            $payment->payment_gateway_transaction_id = $chargeId;

            if (!$payment->save()) {

                //\Yii::error ($payment->errors, __METHOD__); // Log error faced by user

                return [
                    'operation' => 'error',
                    'message' => $payment->getErrors (),
                    "response" => $responseContent
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
     * 
     * @api {get} /addons/callback Process callback from TAP payment gateway
     * @apiName ProcessCallback
     * @apiGroup Addon
     *
     * @apiSuccess {Array} Callback processed successfully.
     */
    public function actionCallback()
    {
        //http://localhost/plugn/agent/web/v1/addons/callback?tap_id=chg_TS021920221604Yu671108847
        //try {

            $id = Yii::$app->request->get('tap_id');

            $paymentRecord = AddonPayment::updatePaymentStatusFromTap($id);
            $paymentRecord->received_callback = true;
            $paymentRecord->save(false);

            if ($paymentRecord->payment_current_status == 'CAPTURED') {
                $this->_addCallbackCookies ("addonPaymentSuccess", $paymentRecord->addon->name . ' has been activated');
            } else {
                $this->_addCallbackCookies ("paymentFailed", "There seems to be an issue with your payment, please try again.");
            }

       // } catch (\Exception $e) {

       //     $this->_addCallbackCookies ("paymentFailed", $e->getMessage ());
       // }

        //todo: show success/failed message in addon page

        $url = Yii::$app->params['newDashboardAppUrl'] . '/addon-list';

        return $this->redirect ($url);//Yii::$app->getResponse()->send();
    }

    /**
     * Process callback from TAP payment gateway
     * @param string $tap_id
     * @return mixed
     * 
     * @api {post} /addons/payment-webhook Process callback from TAP payment gateway
     * @apiName ProcessPaymentWebhook
     * @apiGroup Addon
     *
     * @apiParam {string} id Charge ID.
     * @apiParam {string} status Charge status.
     * @apiParam {string} amount Amount.
     * @apiParam {string} currency Currency.
     * @apiParam {string} reference Reference.
     * @apiParam {string} destinations Destinations.
     * @apiParam {string} response Response.
     * @apiParam {string} source Source.
     * @apiParam {string} transaction Transaction.
     * @apiParam {string} acquirer Acquirer.
     * 
     * @apiSuccess {Array} Payment webhook processed successfully.
     */
    public function actionPaymentWebhook() {

        $headers = Yii::$app->request->headers;
        $headerSignature = $headers->get('hashstring');

        $charge_id = Yii::$app->request->getBodyParam("id");
        $status = Yii::$app->request->getBodyParam("status");
        $amount = Yii::$app->request->getBodyParam("amount");
        $currency = Yii::$app->request->getBodyParam("currency");
        $reference = Yii::$app->request->getBodyParam("reference");
        $destinations = Yii::$app->request->getBodyParam("destinations");
        $response = Yii::$app->request->getBodyParam("response");
        $source = Yii::$app->request->getBodyParam("source");
        $transaction = Yii::$app->request->getBodyParam("transaction");
        $acquirer = Yii::$app->request->getBodyParam("acquirer");

        if($currency_mode = Currency::find()->where(['code' => $currency])->one()) {
            $decimal_place = $currency_mode->decimal_place;
        } else {
            throw new ForbiddenHttpException('Invalid Currency code');
        }

        $paymentRecord = \common\models\SubscriptionPayment::findOne(['payment_gateway_transaction_id' => $charge_id]);

        if (!$paymentRecord) {
            throw new NotFoundHttpException('The requested payment does not exist in our database.');
        }

        if (isset($reference)){
            $gateway_reference = $reference['gateway'];
            $payment_reference = $reference['payment'];
        }

        if(isset($transaction)){
            $created = $transaction['created'];
        }

        $amountCharged = \Yii::$app->formatter->asDecimal($amount, $decimal_place);
        $toBeHashedString = 'x_id'.$charge_id.'x_amount'.$amountCharged.'x_currency'.$currency.'x_gateway_reference'.$gateway_reference.'x_payment_reference'.$payment_reference.'x_status'.$status.'x_created'.$created.'';

        $isValidSignature = true;

        //Check If Enabled Secret Key and If The header has request
        if ($headerSignature != null)  {
            $response_message  = null;

            if(isset($acquirer) && isset($acquirer['response'])){
                $response_message = $acquirer['response']['message'];
            } else if(isset($response)) {
                $response_message = $response['message'];
            }

            $isValidSignature = false;

            if (!$isValidSignature) {

                Yii::$app->tapPayments->setApiKeys(
                    \Yii::$app->params['liveApiKey'],
                    \Yii::$app->params['testApiKey'],
                    $paymentRecord->is_sandbox
                );

                $isValidSignature = Yii::$app->tapPayments->checkTapSignature($toBeHashedString , $headerSignature);

                if (!$isValidSignature) {
                    Yii::error('Invalid Signature', __METHOD__);
                    throw new ForbiddenHttpException('Invalid Signature');
                }
            }

            $paymentRecord = \common\models\SubscriptionPayment::updatePaymentStatus(
                $charge_id,
                $status,
                $destinations,
                $source,
                $reference,
                $response_message);

            $paymentRecord->received_callback = true;
            $paymentRecord->save(false);

            if($paymentRecord->payment_current_status == 'CAPTURED')
            {
                    //Send event to Segment
                    
                    $kwdCurrency = Currency::findOne(['code' => 'KWD']);

                    $rate = 1 / $kwdCurrency->rate;// to USD

                    Yii::$app->eventManager->track('Addon Purchase', [
                            'addon_uuid' => $paymentRecord->addon_uuid,
                            'addon' => $paymentRecord->addon->name,
                            'paymentMethod' => $paymentRecord->payment_mode,
                            'charged' => $paymentRecord->payment_amount_charged,
                            'value' => ( $paymentRecord->payment_amount_charged * $rate ),
                            'revenue' => $paymentRecord->payment_net_amount,
                            'currency' => 'USD'
                        ],
                        null, 
                        $paymentRecord->restaurant_uuid
                    );

                $model = new RestaurantAddon();
                $model->addon_uuid = $paymentRecord->addon_uuid;
                $model->restaurant_uuid = $paymentRecord->restaurant_uuid;
                $model->save();

                foreach ($paymentRecord->restaurant->getOwnerAgent()->all() as $agent ) {

                    $ml = new MailLog();
                    $ml->to = $agent->agent_email;
                    $ml->from = Yii::$app->params['noReplyEmail'];
                    $ml->subject ='Thank you for your purchase';
                    $ml->save();

                    $mailer = \Yii::$app->mailer->compose([
                        'html' => 'addon-purchased',
                    ], [
                        'paymentRecord' => $paymentRecord,
                        'addon' => $paymentRecord->addon,
                        'store' => $paymentRecord->restaurant,
                    ])
                        ->setFrom([\Yii::$app->params['noReplyEmail'] => \Yii::$app->name])
                        ->setTo([$agent->agent_email])
                       // ->setReplyTo(\Yii::$app->params['supportEmail'])
                        ->setBcc(\Yii::$app->params['supportEmail'])
                        ->setSubject('Thank you for your purchase');

                    if(\Yii::$app->params['elasticMailIpPool'])
                        $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

                    try {
                        $mailer->send();
                    } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
                        // Handle email transport-specific exceptions
                        Yii::error( "Failed to send email: " . $e->getMessage());
                    } catch (\Exception $e) {
                        // Handle any other exceptions
                        Yii::error( "An error occurred: " . $e->getMessage());
                    }
                }
            }

            if($paymentRecord) {
                return [
                    'operation' => 'success',
                    'message' => 'Payment status has been updated successfully'
                ];
            }
        }
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

        $cookie->sameSite = 'None';//PHP_VERSION_ID >= 70300 ? 'None' : null;

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