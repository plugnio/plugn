<?php

namespace agent\modules\v1\controllers\payment;

use agent\models\Currency;
use agent\models\PaymentMethod;
use agent\models\Restaurant;
use common\models\InvoicePayment;
use common\models\SubscriptionPayment;
use Yii;
use common\models\Setting;
use yii\helpers\Url;
use agent\modules\v1\controllers\BaseController;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;


class MoyasarController extends BaseController
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'callback'];

        return $behaviors;
    }

    /**
     * return params for payment
     * @return array
     * 
     * @api {POST} /payment/moyasar Return params for payment
     * @apiName GetPaymentParams
     * @apiGroup Payment
     * 
     * @apiParam {string} plan_id Plan ID.
     * @apiParam {string} invoice_uuid Invoice UUID.
     * @apiParam {string} currency Currency.
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionIndex()
    {
        //Yii::$app->session->set('payment_uuid', $payment->payment_uuid);

        $store = Yii::$app->accountManager->getManagedAccount ();

        $plan_id = Yii::$app->request->getBodyParam('plan_id');
        $invoice_uuid = Yii::$app->request->getBodyParam ('invoice_uuid');

        $paymentMethod = PaymentMethod::findOne(['payment_method_code' => 'Moyasar']);

        if($plan_id) {

            $store = $this->findStore();

            $currency_code = Yii::$app->request->getBodyParam ('currency');

            $currency = $currency_code? Currency::findOne(['code' => $currency_code]): $store->currency;

            $subscription = \agent\models\SubscriptionPayment::initPayment($plan_id, $paymentMethod->payment_method_id, $currency);

            $data['description'] = "Upgrade $store->name's plan to " . $subscription->plan->name;

            $payment = $subscription->subscriptionPayment;
        } else {

            $payment = InvoicePayment::initPayment($invoice_uuid, $paymentMethod->payment_method_id);

            $currency = $payment->currency;

            $data['description'] = "Invoice for plugn commission";// on order # . $payment->invoice->invoiceItems[0]->order_uuid;
        }

        $data['amount'] = $payment->payment_amount_charged;
        $data['amount_in_halals'] =  $payment->payment_amount_charged * pow(10, $currency->decimal_place);
        $data['language_code'] = Yii::$app->language;
        $data['currency'] = $currency->code;// $payment['currency_code'];
        $data['country'] = $store->country? $store->country->iso : "KW";
        $data['store_name'] = Yii::$app->params['appName'];
        $data['orderdate'] = $payment['payment_created_at'];
        $data['domain_name'] = Yii::$app->request->hostName;

        //metadata
        $data['metadata'] = [
            'payment_uuid' =>  $payment['payment_uuid'],
            'invoice_uuid' => $invoice_uuid,
            'restaurant_uuid' => $store->restaurant_uuid,
            'username' => $store->name,
            //'email' => $payment->user->email
        ];

        $data['metadata_json'] = $data['metadata'];

        $data['action'] = 'https://api.moyasar.com/v1/payments.html';

        //payment_moyasar_api_secret_key
        $data['payment_moyasar_api_key'] = Setting::getConfig(null, "Moyasar", 'payment_moyasar_api_key');

        $data['payment_moyasar_payment_type'] = Setting::getConfig(null, "Moyasar", 'payment_moyasar_payment_type');

        $data['payment_moyasar_network_type'] = Setting::getConfig(null, "Moyasar", 'payment_moyasar_network_type');

        //$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

        $data['payment_moyasar_payment_methods'] = ["creditcard", "stcpay", "applepay"];

        //array_push($data['payment_moyasar_payment_methods'], ($data['payment_moyasar_payment_type']['cc'])? 'creditcard' : 'creditcard');
        //array_push($data['payment_moyasar_payment_methods'], ($data['payment_moyasar_payment_type']['stcpay'])? 'stcpay' : 'stcpay');
        //array_push($data['payment_moyasar_payment_methods'], ($data['payment_moyasar_payment_type']['applepay'])? 'applepay' : '');
        $data['payment_moyasar_payment_methods_json'] = json_encode($data['payment_moyasar_payment_methods']);

        // get network support from admin
        $data['payment_moyasar_network_support'] = [];
        /*array_push($data['payment_moyasar_network_support'], ($data['payment_moyasar_network_type']['mada'])? 'mada' : '');
        array_push($data['payment_moyasar_network_support'], ($data['payment_moyasar_network_type']['visa'])? 'visa' : '');
        array_push($data['payment_moyasar_network_support'], ($data['payment_moyasar_network_type']['mastercard'])? 'mastercard' : '');
        array_push($data['payment_moyasar_network_support'], ($data['payment_moyasar_network_type']['amex'])? 'amex' : '');*/
        $data['payment_moyasar_network_support_json'] = json_encode($data['payment_moyasar_network_support']);

        $data['callback_url'] = str_replace("%2F", "/", Url::to(['payment/moyasar/callback'], true));

        $data['validate_merchant_url'] = 'https://api.moyasar.com/v1/applepay/initiate';

        $data['text_cc'] = Yii::t('app', "Credit card");
        $data['text_mada'] = Yii::t('app', "Mada");
        $data['text_cc_mada'] = Yii::t('app', "Credit card mada");
        $data['text_stc_pay'] = Yii::t('app', "STC pay");
        $data['text_applepay'] = Yii::t('app', "Apple pay");
        $data['text_stcpay'] = Yii::t('app', "Stc Pay");
        $data['text_applepay_not_configured'] = Yii::t('app', "Apple pay not configured");
        $data['text_applepay_not_supported'] = Yii::t('app', "Apple pay not supported");

        //todo: update url
        $data['applepay_on_cancel_url'] = $data['callback_url'];//  Url::to(['site/index'], true);
        $data['applepay_on_payment_success_url'] = $data['callback_url'];//Url::to(['site/index'], true);
        $data['button_confirm'] = "Confirm";

        return $data;
    }

    /**
     * callback from gateway
     * 
     * @api {get} /payment/moyasar/callback Callback from gateway
     * @apiName MoyasarCallback
     * @apiGroup Payment
     * 
     * @apiSuccess {string} message Message.
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
    /**
     * Update order
     * 
     * @return bool
     */
    protected function updateOrder()
    {
        /*if (isset($get_data['sid'])) {
            $this->session->start($get_data['sid']);
        }*/

        $id = Yii::$app->request->get('id');
        $status = Yii::$app->request->get('status');
        $message = Yii::$app->request->get('message');

        $paymentDetail = $this->_paymentDetail($id);

        if ($status != 'paid') {
            Yii::error('Moyasar Payment Verification Failed: '. $message);

            return false;
        }

        if(isset($paymentDetail['metadata']['invoice_uuid'])) {
            $payment = InvoicePayment::findOne($paymentDetail['metadata']['payment_uuid']);
            $currency = Currency::findOne(['code' => $payment->currency_code]);
        } else if(isset($paymentDetail['metadata']['payment_uuid'])) {
            $payment = SubscriptionPayment::findOne($paymentDetail['metadata']['payment_uuid']);
            $currency = Currency::findOne(['code' => 'KWD']);
        }

        $order_amount = $payment['payment_amount_charged'] * pow(10, $currency->decimal_place);

        //Yii::$app->currency->format($payment['total'], $payment['currency_code'], $payment['currency_value'], false)*100;

        if (empty($paymentDetail['amount']) || $paymentDetail['amount'] != $order_amount) {
            Yii::debug('Moyasar payment is successful but amount does not match paid, possible tampering. #' . $id);
            return false;
        }

        if (!$payment) {
            Yii::debug('Moyasar payment is successful but payment_uuid does not match, possible tampering. #' . $id);
            return false;
        }

        //payment was made successfully

        $payment->payment_current_status = 'CAPTURED';
        $payment->payment_gateway_fee = $paymentDetail['fee'] / pow(10, $currency->decimal_place);//in halals

        if(isset($payment->payment_gateway_order_id)) {
            $payment->response_message = $message;
            $payment->payment_gateway_order_id = $id;
        }

        // Net amount after deducting gateway fee
        $payment->payment_net_amount = $payment->payment_amount_charged - $payment->payment_gateway_fee;

        $payment->save();

        if(isset($payment->subscription_uuid))
            SubscriptionPayment::onPaymentCaptured($payment);

        return $payment;
    }

    /**
     * Register initiated order
     * 
     * @return void
     * 
     * @api {get} /payment/moyasar/register-initiated-order Register initiated order
     * @apiName RegisterInitiatedOrder
     * @apiGroup Payment
     * 
     * @apiParam {string} id Payment ID.
     * @apiParam {string} payment_id Payment ID.
     * 
     * @apiSuccess {string} message Message.
     */
    public function actionRegisterInitiatedOrder()
    {
        //Yii::debug(Yii::$app->request);

        $this->addOrder(
            Yii::$app->request->get('id'),
            Yii::$app->request->get('payment_id')
        );
    }

    private function _paymentDetail($payment_id)
    {
        $secret_key = Setting::getConfig(null, "Moyasar", 'payment_moyasar_api_secret_key');

        $header = [
            'Authorization: Basic '. base64_encode($secret_key.':')
        ];

        $curl = curl_init('https://api.moyasar.com/v1/payments/'.$payment_id);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);

        return json_decode(curl_exec($curl), true);
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findStore($store_uuid =  null)
    {
        $model = Yii::$app->accountManager->getManagedAccount($store_uuid);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
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

        $cookie->sameSite = PHP_VERSION_ID >= 70300 ? 'None' : null;

        \Yii::$app->getResponse ()->getCookies ()->add ($cookie);
    }
}
