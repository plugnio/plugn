<?php

namespace agent\modules\v1\controllers\payment;

use Yii;
use agent\models\Addon;
use api\modules\v2\controllers\BaseController;
use common\components\TapPayments;
use common\models\AddonPayment;
use yii\helpers\Url;
//use yii\httpclient\Client;
use yii\web\NotFoundHttpException;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class ApplePayController extends BaseController
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'process-payment', 'validate-merchant'];

        return $behaviors;
    }

    /**
     * process apple token -> tap token -> charge user
     * @return array
     */
    public function actionProcessPayment()
    {
        $addon_uuid = \Yii::$app->request->getBodyParam ('addon_uuid');
        $payment_method_id = \Yii::$app->request->getBodyParam ('payment_method_id');
        $token = \Yii::$app->request->getBodyParam ('token');

        \Yii::error($token);

        \Yii::$app->tapPayments->setApiKeys (
            \Yii::$app->params['liveApiKey'],
            \Yii::$app->params['testApiKey'],
            false
        );

        //convert apple pay token to Tap apple pay token

        if($token) {
            $response = \Yii::$app->tapPayments->fromApplePayToken($token);
            $responseContent = json_decode($response->content);

            \Yii::error($responseContent);

            $token = $responseContent->id;
        }

        $addon = $this->findModel($addon_uuid);

        $store = \Yii::$app->accountManager->getManagedAccount (null, false);

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
                "code" => 1,
                'message' => $payment->getErrors ()
            ];
        }

        if(!$token) {
            $token = $payment_method_id == 1 ? TapPayments::GATEWAY_KNET :
                TapPayments::GATEWAY_VISA_MASTERCARD;
        }

        $response = \Yii::$app->tapPayments->createCharge (
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

        //for initial test
        \Yii::error ($responseContent, __METHOD__);

        //try {

        // Validate that theres no error from TAP gateway
        if (isset($responseContent->errors)) {

            $errorMessage = "Error: " . $responseContent->errors[0]->code . " - " . $responseContent->errors[0]->description;

            //todo: notify vendor?
            \Yii::error ($errorMessage, __METHOD__); // Log error faced by user

            return [
                'operation' => 'error',
                "code" => 2,
                'message' => $errorMessage,
                "response" => $responseContent
            ];
        }

        $redirectUrl = null;

        if ($responseContent->id) {

            $chargeId = $responseContent->id;
            $redirectUrl = isset($responseContent->transaction->url)?
                $responseContent->transaction->url: null;

            $payment->payment_gateway_transaction_id = $chargeId;

            if (!$payment->save()) {

                \Yii::error ($payment->errors, __METHOD__); // Log error faced by user

                return [
                    'operation' => 'error',
                    "code" => 3,
                    'message' => $payment->getErrors (),
                    "response" => $responseContent
                ];
            }

            if($redirectUrl) {
                return [
                    'operation' => 'success',
                    'redirect' => $redirectUrl
                ];
            } else {

                $paymentRecord = AddonPayment::updatePaymentStatusFromTap($chargeId);
                $paymentRecord->save(false);

                if ($paymentRecord->payment_current_status == 'CAPTURED') {
                    return [
                        'operation' => 'success',
                        'redirect' => $paymentRecord->addon->name . ' has been activated'
                    ];;
                } else {
                    return [
                        'operation' => 'error',
                        "code" => 4,
                        'message' => "There seems to be an issue with your payment, please try again."
                    ];
                }
            }

        } else {

            \Yii::error ('[Payment Issue > Charge id is missing ]' . json_encode ($responseContent), __METHOD__); // Log error faced by user

            return [
                'operation' => 'error',
                "code" => 5,
                'message' => \Yii::t('agent','Payment Issue > Charge id is missing')
            ];
        }

        \Yii::error($responseContent);

        return [
            'operation' => 'error',
            "code" => 6,
            'message' => "unknown error"
        ];

        /*} catch (\Exception $e) {

            if ($payment)
                \Yii::error ('[TAP Payment Issue > ]' . json_encode ($payment->getErrors ()), __METHOD__);

            \Yii::error ('[TAP Payment Issue > Charge id is missing]' . json_encode ($responseContent), __METHOD__);

            return [
                'operation' => 'error',
                'message' => json_encode ($responseContent)
            ];
        }*/
    }

    /**
     * @return void
     */
    public function actionValidateMerchant()
    {
        $validationURL = \Yii::$app->request->getBodyParam("validationURL");

        if (!$validationURL) {
            $validationURL = "https://apple-pay-gateway.apple.com/paymentservices/paymentSession";
            //https://developer.apple.com/documentation/apple_pay_on_the_web/apple_pay_js_api/requesting_an_apple_pay_payment_session

            /*return [
                "operation" => "error",
                "message" => "Validation URL missing!"
            ];*/
        }

        $certPath = Yii::getAlias('@common') . '/certificates/cert.pem';//merchant_id.cer';
        $pemPath = Yii::getAlias('@common') . '/certificates/key.pem';

        $body = [
            "merchantIdentifier" => 'merchant.io.plugn.dashboard',
            "domainName" => 'dash.plugn.io',
            "displayName" => 'Plugn',
            "initiative" => "web",
            "initiativeContext" => "dash.plugn.io"
        ];

        try {
            $client = new Client([
                'base_uri' => "",
                'timeout' => 10.0,
                'verify' => true,
                'cert' => $certPath,
                'ssl_key' => $pemPath,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'curl' => [
                    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                ]
            ]);

            $response = $client->post($validationURL, [
                'json' => $body,
            ]);

            return json_decode($response->getBody()->getContents());

        } catch (RequestException $e) {

            Yii::error($e->getMessage());

            //header('Content-Type: application/json', true, 500);
            return json_encode(['error' => $e->getMessage()]);
        }

        /*
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($validationURL)
            ->addHeaders([
                'cert' => $certPath,
                'ssl_key' => $pemPath,
                //'Authorization' => 'Bearer ', //YOUR_MERCHANT_ID_TOKEN
            ])
            ->addOptions([
                CURLOPT_SSLCERT => $certPath,
                CURLOPT_SSLKEY => $pemPath,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2
            ])
            ->setData($body)
            ->send();

        \Yii::debug($response);

        return $response->data;*/
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