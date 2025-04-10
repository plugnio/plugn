<?php

namespace agent\modules\v1\controllers;

use agent\models\Currency;
use common\components\TapPayments;
use common\models\InvoicePayment;
use common\models\RestaurantInvoice;
use Stripe\Invoice;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;


class InvoiceController extends BaseController
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'callback', 'payment-webhook'];

        return $behaviors;
    }

    /**
     * Get all store's products
     * @return type
     * 
     * @api {get} /invoices Get all store's invoices
     * @apiName GetInvoices
     * @apiGroup Invoice
     *
     * @apiSuccess {Array} invoices List of invoices.
     */
    public function actionList()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $query = $store->getInvoices()
            //invoices will available after month end
            ->andWhere(['!=', 'invoice_status', RestaurantInvoice::STATUS_UNPAID])
            ->orderBy('created_at desc');

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Return invoice detail
     * @param string $id
     * @return RestaurantInvoice
     * 
     * @api {get} /invoices/:id Return invoice detail
     * @apiName GetInvoiceDetail
     * @apiGroup Invoice
     *
     * @apiSuccess {Array} invoice Invoice.
     */
    public function actionDetail($id)
    {
        return $this->findModel($id);
    }

    /**
     * pay invoice by tap
     * @return array|string[]
     * @throws NotFoundHttpException
     * 
     * @api {post} /invoices/pay-by-tap Pay invoice by tap
     * @apiName PayInvoiceByTap
     * @apiParam {string} invoice_uuid Invoice UUID.
     * @apiParam {string} payment_method_id Payment method ID.
     * @apiGroup Invoice
     *
     * @apiSuccess {Array} invoice Invoice.
     */
    public function actionPayByTap() {

        $store = Yii::$app->accountManager->getManagedAccount (null, false);

        $invoice_uuid = Yii::$app->request->getBodyParam ('invoice_uuid');
        $payment_method_id = Yii::$app->request->getBodyParam ('payment_method_id');

        $payment = InvoicePayment::initPayment($invoice_uuid, $payment_method_id);

        $invoice = $this->findModel($invoice_uuid);

        // Redirect to payment gateway
        Yii::$app->tapPayments->setApiKeys (
            \Yii::$app->params['liveApiKey'],
            \Yii::$app->params['testApiKey'],
            $payment->is_sandbox
        );

        $response = Yii::$app->tapPayments->createCharge (
            $payment->currency_code,
            "Invoice #". $invoice->invoice_number,
            'Plugn Commission', //Statement Desc.
            $payment->payment_uuid, // Reference
            $payment->payment_amount_charged,
            $store->name,
            $store->getAgents ()->one ()->agent_email,
            $store->country->country_code,
            $store->owner_number ? $store->owner_number : null,
            0, //Commission
            Url::to (['invoices/callback'], true),
            Url::to(['invoices/payment-webhook'], true),
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
     * 
     * @api {post} /invoices/callback Process callback from TAP payment gateway
     * @apiName Callback
     * @apiParam {string} tap_id TAP ID.
     * @apiGroup Invoice
     *
     * @apiSuccess {Array} invoice Invoice.
     */
    public function actionCallback()
    {
        try {

            $id = Yii::$app->request->get('tap_id');

            $payment = InvoicePayment::updateStatusFromTap($id);

            if ($payment->payment_current_status == 'CAPTURED') {
                $this->_addCallbackCookies ("paymentSuccess", 'Payment processed successfully');
            } else {
                $this->_addCallbackCookies ("paymentFailed", "There seems to be an issue with your payment, please try again.");
            }

        } catch (\Exception $e) {
            $this->_addCallbackCookies ("paymentFailed", $e->getMessage ());
        }

        $url = Yii::$app->params['newDashboardAppUrl'] . '/invoice-list';

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
     * 
     * @api {post} /invoices/payment-webhook Process callback from TAP payment gateway
     * @apiName PaymentWebhook
     * @apiParam {string} id TAP ID.
     * @apiParam {string} status Status.
     * @apiParam {string} amount Amount.
     * @apiParam {string} currency Currency.
     * @apiParam {string} reference Reference.
     * @apiParam {string} destinations Destinations.
     * @apiParam {string} response Response.
     * @apiParam {string} source Source.
     * @apiParam {string} transaction Transaction.
     * @apiParam {string} acquirer Acquirer.
     * @apiParam {string} headerSignature Header signature.
     * 
     * @apiGroup Invoice
     * 
     * @apiSuccess {Array} invoice Invoice.
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

            $payment = \common\models\InvoicePayment::findOne([
                'payment_gateway_transaction_id' => $charge_id
            ]);

            if (!$payment) {
                throw new NotFoundHttpException('The requested payment does not exist in our database.');
            }

            Yii::$app->tapPayments->setApiKeys(
                \Yii::$app->params['liveApiKey'],
                \Yii::$app->params['testApiKey'],
                $payment->is_sandbox
            );

                $isValidSignature = Yii::$app->tapPayments->checkTapSignature($toBeHashedString , $headerSignature);

                if (!$isValidSignature) {
                    Yii::error('Invalid Signature', __METHOD__);
                    throw new ForbiddenHttpException('Invalid Signature');
                }

            $payment = InvoicePayment::updateStatusFromTap($charge_id, $response_message, $payment);

            if($payment->payment_current_status == 'CAPTURED') {
                return [
                    'operation' => 'success',
                    'message' => 'Payment status has been updated successfully'
                ];
            }

            return [
                'operation' => 'error',
                "message" => $response_message,
                'payment' => $payment
            ];
        }
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $invoice_uuid
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($invoice_uuid)
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = $store->getInvoices()
            ->andWhere(['invoice_uuid' => $invoice_uuid])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}