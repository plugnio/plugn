<?php

namespace api\modules\v2\controllers\payment;

use agent\models\PaymentMethod;
use common\models\Payment;
use common\models\SubscriptionPayment;
use Yii;
use common\models\Setting;
use yii\helpers\Url;
use api\modules\v2\controllers\BaseController;
use yii\web\Cookie;


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
     */
    public function actionIndex()
    {
        //Yii::$app->session->set('payment_uuid', $payment->payment_uuid);

        $store = Yii::$app->accountManager->getManagedAccount ();

        $plan_id = Yii::$app->request->get ('plan_id');

        $paymentMethod = PaymentMethod::findOne(['payment_method_code' => 'Moyasar']);

        $subscription = \agent\models\SubscriptionPayment::initPayment($plan_id, $paymentMethod->payment_method_id);

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

        $data['amount'] = $subscription->subscriptionPayment->payment_amount_charged;
        $data['amount_in_halals'] =  $subscription->subscriptionPayment->payment_amount_charged * 1000;
        $data['language_code'] = Yii::$app->language;
        $data['currency'] = "KWD";// $payment['currency_code'];
        $data['country'] = "KW";
        $data['store_name'] = Yii::$app->params['appName'];
        $data['orderdate'] = $subscription->subscriptionPayment['payment_created_at'];
        $data['description'] = "Upgrade $store->name's plan to " . $subscription->plan->name;
        $data['domain_name'] = Yii::$app->request->hostName;

        $data['text_cc'] = Yii::t('app', "Credit card");
        $data['text_mada'] = Yii::t('app', "Mada");
        $data['text_cc_mada'] = Yii::t('app', "Credit card mada");
        $data['text_stc_pay'] = Yii::t('app', "STC pay");
        $data['text_applepay'] = Yii::t('app', "Apple pay");
        $data['text_stcpay'] = Yii::t('app', "Stc Pay");
        $data['text_applepay_not_configured'] = Yii::t('app', "Apple pay not configured");
        $data['text_applepay_not_supported'] = Yii::t('app', "Apple pay not supported");

        //metadata
        $data['metadata'] = [
            'payment_uuid' =>  $subscription->subscriptionPayment['payment_uuid'],
            'restaurant_uuid' => $store->restaurant_uuid,
            'username' => $store->name,
            //'email' => $payment->user->email
        ];

        $data['metadata_json'] = $data['metadata'];

        //todo: update url
        $data['applepay_on_cancel_url'] = $data['callback_url'];//  Url::to(['site/index'], true);
        $data['applepay_on_payment_success_url'] = $data['callback_url'];//Url::to(['site/index'], true);
        $data['button_confirm'] = "Confirm";

        return $data;
    }

    /**
     * callback from gateway
     */
    public function actionCallback()
    {
        $payment = $this->updateOrder();

        if ($payment) {
            $this->_addCallbackCookies ("paymentSuccess", $payment->plan->name . ' has been activated');
        } else {
            $this->_addCallbackCookies ("paymentFailed", "There seems to be an issue with your payment, please try again.");
        }

        $url = Yii::$app->params['newDashboardAppUrl'] . '/settings/payment-methods';

        return $this->redirect ($url);
    }

    // todo: old callback in general form
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

        $payment_uuid = $paymentDetail['metadata']['payment_uuid'];

        $payment = SubscriptionPayment::findOne($payment_uuid);

        $order_amount = $payment['payment_amount_charged'] * 1000;//todo: only if 3 decimal currency

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
        $payment->response_message = $message;
        $payment->payment_gateway_fee = $paymentDetail['fee'] / 1000;//in halals
        $payment->payment_gateway_order_id = $id;

        // Net amount after deducting gateway fee
        $payment->payment_net_amount = $payment->payment_amount_charged - $payment->payment_gateway_fee;

        $payment->save(false);

        SubscriptionPayment::onPaymentCaptured($payment);

        return $payment;
    }

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
