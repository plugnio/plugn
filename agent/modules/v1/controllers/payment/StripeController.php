<?php

namespace agent\modules\v1\controllers\payment;

use Yii;
use agent\models\Currency;
use agent\models\PaymentMethod;
use agent\modules\v1\controllers\BaseController;
use common\models\InvoicePayment;
use common\models\Payment;
use common\models\Setting;
use common\models\SubscriptionPayment;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;


class StripeController extends BaseController
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'callback'];

        return $behaviors;
    }

    /**
     * return client secret to initiate stripe form
     * @return array
     * @throws NotFoundHttpException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function actionIndex() {

        $plan_id = Yii::$app->request->getBodyParam('plan_id');
        $invoice_uuid = Yii::$app->request->getBodyParam ('invoice_uuid');

        $paymentMethod = PaymentMethod::findOne(['payment_method_code' => 'Stripe']);

        if($plan_id) {

            $subscription = \agent\models\SubscriptionPayment::initPayment($plan_id, $paymentMethod->payment_method_id);

            if(isset($subscription['message'])) {
                return $subscription;
            }    

            $currency = Currency::findOne(['code' => 'KWD']);

            $payment = $subscription->subscriptionPayment;

        } else {

            $payment = InvoicePayment::initPayment($invoice_uuid, $paymentMethod->payment_method_id);

            $currency = $payment->currency;
        }

        try {

            $stripeSecretKey = Setting::getConfig(null, "Stripe", 'payment_stripe_secret_key');
            $stripePublishableKey = Setting::getConfig(null, "Stripe", 'payment_stripe_publishable_key');

            \Stripe\Stripe::setApiKey($stripeSecretKey);

            // Create a PaymentIntent with amount and currency
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $payment->payment_amount_charged * pow(10, $currency->decimal_place),
                'currency' => strtolower($currency->code),
                "payment_method_types" => ["card"],
                /*'automatic_payment_methods' => [
                    'enabled' => true,
                ],*/
            ]);

            $payment->payment_gateway_transaction_id = $paymentIntent->id;
            $payment->save(false);

            return [
                'operation' => 'success',
                'clientSecret' => $paymentIntent->client_secret,
                "stripePublishableKey" => $stripePublishableKey,
                'success_url' => Url::to(['payment/stripe/callback', 'intent_id' => $paymentIntent->id], true),
                'cancel_url' => Url::to(['payment/stripe/callback', 'intent_id' => $paymentIntent->id], true),
            ];

        } catch (\Stripe\Exception\InvalidRequestException $e) {

            return [
                'operation' => 'error',
                'message' => $e->getMessage()
            ];
        } catch (Error $e) {

            return [
                'operation' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * callback from gateway
     */
    public function actionCallback()
    {
        $payment = $this->updateOrder();

        if ($payment) {

            $message = "Payment got processed successfully";

            $url = Yii::$app->params['newDashboardAppUrl'] . '/invoice-list';

            if(isset($payment->subscription_uuid)) {

                $message = $payment->plan->name . ' has been activated';

                $url = Yii::$app->params['newDashboardAppUrl'] . '/settings/payment-methods';
            }

            $this->_addCallbackCookies ("paymentSuccess", $message);
        } else {
            $this->_addCallbackCookies ("paymentFailed", "There seems to be an issue with your payment, please try again.");


            $url = Yii::$app->params['newDashboardAppUrl'] . '/settings/payment-methods';
        }

        return $this->redirect ($url);
    }

    // todo: old callback in general form
    protected function updateOrder()
    {
        /*if (isset($get_data['sid'])) {
            $this->session->start($get_data['sid']);
        }*/

        $intent_id = Yii::$app->request->get('intent_id');
        //$id = Yii::$app->request->get('id');
        $status = Yii::$app->request->get('redirect_status');

        $payment = InvoicePayment::findOne(['payment_gateway_transaction_id' => $intent_id]);

        if(!$payment) {
            $payment = SubscriptionPayment::findOne(['payment_gateway_transaction_id' => $intent_id]);
            //$currency = Currency::findOne(['code' => 'KWD']);
            //} else {
            //$currency = Currency::findOne(['code' => $payment->currency_code]);
        }

        if (!$payment) {
            Yii::error('Stripe Payment Verification Failed: '. $status);

            return false;
        }

        $stripeSecretKey = Setting::getConfig(null, "Stripe", 'payment_stripe_secret_key');

        $stripe = new \Stripe\StripeClient($stripeSecretKey);

        $paymentIntent = $stripe->paymentIntents->retrieve(
            $intent_id,
            []
        );

        // $payment->payment_gateway_fee = $paymentDetail['fee'] / pow(10, $order->currency->decimal_place);//todo: fees from stripe?
        $payment->received_callback = true;

        // Net amount after deducting gateway fee
        $payment->payment_net_amount = $payment->payment_amount_charged - $payment->payment_gateway_fee;

        if ($paymentIntent->status == 'succeeded')
            $payment->payment_current_status = 'CAPTURED';
        else
            $payment->payment_current_status = $paymentIntent->status;

        $payment->save(false);

        $error = null;

        if ($paymentIntent->status != 'succeeded') {
            $error = 'Stripe Payment Verification Failed: '. $paymentIntent->status;
        }

        /*if (!$payment) {
            Yii::error('Moyasar payment is successful but payment_uuid does not match, possible tampering. #' . $id);
            return false;
        }*/

        //Yii::$app->currency->format($payment['total'], $payment['currency_code'], $payment['currency_value'], false)*100;

        if($error) {

            //notify tech team + vendor

            Yii::error($error);

            //Payment::notifyTapError($payment, $paymentIntent);

            return false;
        }

        if(isset($payment->subscription_uuid))
            SubscriptionPayment::onPaymentCaptured($payment);

        return $payment;
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
}