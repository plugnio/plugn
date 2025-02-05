<?php

namespace agent\modules\v1\controllers;

use agent\models\Currency;
use common\models\MailLog;
use common\models\PlanPrice;
use Yii;
use common\components\TapPayments;
use agent\models\Plan;
use agent\models\Subscription;
use agent\models\SubscriptionPayment;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Cookie;


class PlanController extends BaseController
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'callback', 'payment-webhook', 'price'];

        return $behaviors;
    }

    /**
     * only owner will have access
     */
    public function beforeAction($action)
    {
        parent::beforeAction ($action);

        if(in_array($action->id, ['payment-webhook', 'options', 'callback', 'price'])) {
            return true;
        }

        if(!Yii::$app->accountManager->isOwner() && !in_array ($action->id, ['view'])) {
            throw new \yii\web\BadRequestHttpException(
                Yii::t('agent', 'You are not allowed to manage plan. Please contact with store owner')
            );

            return false;
        }

        //should have access to store

        Yii::$app->accountManager->getManagedAccount();

        return true;
    }

    /**
     * return plan detail
     * @param $id
     * @return Plan|null
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->findModel ($id);
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionApplePayParams($id) {

        //$currency_code = Yii::$app->request->getBodyParam ('currency');

        $store = Yii::$app->accountManager->getManagedAccount(null, false);

        $plan = $this->findModel ($id);

        $currency = $store->currency ? $store->currency: Currency::findOne(['code' => "KWD"]);
        //$currency_code? Currency::findOne(['code' => $currency_code]): $store->currency;

        if($currency && $currency->code != "KWD") {

            $kwdCurrency = \agent\models\Currency::find()
                ->andWhere(['code' => "KWD"])
                ->one();

            if($store->custom_subscription_price > 0)
            {
                $planPriceUSD = $store->custom_subscription_price / $kwdCurrency->rate;

                $payment_amount_charged = round($planPriceUSD * $currency->rate, $currency->decimal_place);
            }
            else
            {
                $planPrice = $plan->getPlanPrices()
                    ->andWhere(['currency' => $currency->code])
                    ->one();

                $payment_amount_charged = round($planPrice->price, $currency->decimal_place);
            }
        }
        else
        {
            $payment_amount_charged = $store->custom_subscription_price > 0 ? $store->custom_subscription_price : $plan->price;
        }

        $user = Yii::$app->user->identity;

        return [
            //"public_key" => YII_ENV == "prod" ? $store->live_public_key: $store->test_public_key,
            //"merchant_id" => $store->merchant_id,

            "public_key" => YII_ENV == "prod" ? "pk_live_OtvCjd7hgqT6JWb3Z8yIrmcG" : "pk_test_nIRT3cC2zDy9NpeSxiZ0Vlj4",
            "merchant_id" => Yii::$app->tapPayments->destinationId,// "2663705",
            "currency" => $currency->code,
            "amount" => $payment_amount_charged,
            "agent_name" => $user->agent_name,
            "agent_number" => $user->agent_number,
            "agent_phone_country_code" => $user->agent_phone_country_code,
            "agent_email" => $user->agent_email,
        ];
    }

    /**
     * confirm plan for current store
     * @param $id
     * @return array|string[]
     */
    public function actionConfirm()
    {
        $store = Yii::$app->accountManager->getManagedAccount ();

        $plan_id = Yii::$app->request->getBodyParam ('plan_id');
        $payment_method_id = Yii::$app->request->getBodyParam ('payment_method_id');
        $source = Yii::$app->request->getBodyParam ('source');

        $response = SubscriptionPayment::initPayment($plan_id, $payment_method_id);

        //reverting to free plan or error

        if(isset($response['operation'])) {
            return $response;
        }

        $subscription = $response;

        // Redirect to payment gateway
        Yii::$app->tapPayments->setApiKeys (
            \Yii::$app->params['liveApiKey'],
            \Yii::$app->params['testApiKey'],
            $subscription->payment->is_sandbox
        );

        if(!$source)
            $source = $subscription->payment_method_id == 1 ? TapPayments::GATEWAY_KNET : TapPayments::GATEWAY_VISA_MASTERCARD;

        $response = Yii::$app->tapPayments->createCharge (
            "KWD",
            "Upgrade $store->name's plan to " . $subscription->plan->name, // Description
            'Plugn', //Statement Desc.
            $subscription->payment->payment_uuid, // Reference
            $subscription->payment->payment_amount_charged,
            $store->name,
            $store->getAgents ()->one ()->agent_email,
            $store->country->country_code,
            $store->owner_number ? $store->owner_number : null,
            0, //Comission
            Url::to (['plans/callback'], true),
            Url::to(['plans/payment-webhook'], true),
            $source,
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

                $subscription->payment->payment_gateway_transaction_id = $chargeId;

                if (!$subscription->payment->save (false)) {

                    //\Yii::error ($payment->errors, __METHOD__); // Log error faced by user

                    return [
                        'operation' => 'error',
                        'message' => $subscription->payment->getErrors ()
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

            $paymentRecord = SubscriptionPayment::updatePaymentStatusFromTap($id);
            $paymentRecord->received_callback = true;
            $paymentRecord->save(false);

            if ($paymentRecord->payment_current_status == 'CAPTURED') {
                $this->_addCallbackCookies ("paymentSuccess", $paymentRecord->plan->name . ' has been activated');
            } else {
                $this->_addCallbackCookies ("paymentFailed", "There seems to be an issue with your payment, please try again."
                );
            }

        } catch (\Exception $e) {

            $this->_addCallbackCookies ("paymentFailed", $e->getMessage ());
        }

        $url = Yii::$app->params['newDashboardAppUrl'] . '/settings/payment-methods';

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
     * Process callback from TAP payment gateway
     * @param string $tap_id
     * @return mixed
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

        $paymentRecord = \common\models\SubscriptionPayment::findOne([
            'payment_gateway_transaction_id' => $charge_id]);

        if (!$paymentRecord) {
            throw new NotFoundHttpException('The requested payment does not exist in our database.');
        }

        $gateway_reference = null;
        $payment_reference = null;

        if (isset($reference)) {
            $gateway_reference = isset($reference['gateway'])? $reference['gateway']: null;
            $payment_reference = isset($reference['payment'])? $reference['payment']: null;
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
                $response_message
            );

            $paymentRecord->received_callback = true;

            if ($paymentRecord->save(false) && $paymentRecord->payment_current_status == 'CAPTURED' ) {

                \common\models\Subscription::updateAll(['subscription_status' => Subscription::STATUS_INACTIVE], ['and', ['subscription_status' => Subscription::STATUS_ACTIVE], ['restaurant_uuid' => $paymentRecord->restaurant_uuid]]);

                $subscription = $paymentRecord->subscription;
                $subscription->subscription_status = Subscription::STATUS_ACTIVE;
                $valid_for =  $subscription->plan->valid_for;
                $subscription->subscription_end_at = date('Y-m-d', strtotime(date('Y-m-d H:i:s',  strtotime($subscription->subscription_start_at)) . " + $valid_for MONTHS"));
                $subscription->save(false);

                foreach ($subscription->restaurant->getOwnerAgent()->all() as $agent ) {

                    $ml = new MailLog();
                    $ml->to = $agent->agent_email;
                    $ml->from = Yii::$app->params['noReplyEmail'];
                    $ml->subject = 'Your store '. $paymentRecord->restaurant->name . ' has been upgraded to our '. $subscription->plan->name;
                    $ml->save();

                    $mailer = \Yii::$app->mailer->compose([
                        'html' => 'premium-upgrade',
                    ], [
                        'subscription' => $subscription,
                        'store' => $paymentRecord->restaurant,
                    ])
                        ->setFrom([\Yii::$app->params['noReplyEmail'] => \Yii::$app->name])
                        ->setTo([$agent->agent_email])
                       // ->setReplyTo(\Yii::$app->params['supportEmail'])
                        ->setBcc(\Yii::$app->params['supportEmail'])
                        ->setSubject('Your store '. $paymentRecord->restaurant->name . ' has been upgraded to our '. $subscription->plan->name);

                    if(\Yii::$app->params['elasticMailIpPool'])
                        $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

                    try {
                        $mailer->send();
                    } catch (\Swift_TransportException $e) {
                        Yii::error($e->getMessage(), "email");
                    }
                }

                    //Send event to Segment
                    
                    $kwdCurrency = Currency::findOne(['code' => 'KWD']);

                    $rate = 1 / $kwdCurrency->rate;// to USD
                    
                    Yii::$app->eventManager->track('Premium Plan Purchase',  [
                            'order_id' => $paymentRecord->payment_uuid,
                            'value' => ( $paymentRecord->payment_amount_charged * $rate ),
                            'payment_amount_charged' => $paymentRecord->payment_amount_charged,
                            'amount' => $paymentRecord->payment_amount_charged,
                            'paymentMethod' => $paymentRecord->payment_mode,
                            'currency' => 'USD',
                            'restaurant_uuid' => $paymentRecord->restaurant_uuid
                        ],
                        null,
                        $paymentRecord->restaurant_uuid
                    );
            }

            if($paymentRecord){
                return [
                    'operation' => 'success',
                    'message' => 'Payment status has been updated successfully'
                ];
            }
        }
    }

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function actionPrice() {

        $code = Yii::$app->request->get('currency');

        if(!$code) {

            $result = Yii::$app->ipstack->locate();

            $code = $result->currency->code;
        }

        if(!$code || $code == "KWD") {
            $plan = Plan::find()
                ->andWhere(new Expression("price > 0"))
                ->one();

            return [
                "price" => $plan->price . ' ' . $code
            ];
        }

        $model = PlanPrice::find()
            ->andWhere(['plan_id' => 2, 'currency' => $code])
            ->one();

        /*$price = Yii::$app->formatter->asCurrency($model->price, $currency->code, [
            \NumberFormatter::MAX_FRACTION_DIGITS => $currency->decimal_place
        ]);*/

        return [
            "price" => $model->price . ' ' . $code
        ];
    }

    /**
     * Finds the Plan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plan::findOne ($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}