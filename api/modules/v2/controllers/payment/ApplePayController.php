<?php

namespace api\modules\v2\controllers\payment;

use Yii;
use agent\models\Restaurant;
use api\models\Order;
use api\modules\v2\controllers\BaseController;
use GuzzleHttp\Exception\RequestException;
use yii\web\NotFoundHttpException;

class ApplePayController extends BaseController
{
    public function behaviors()
    {
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

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        //$behaviors['authenticator']['except'] = ['options', 'index', 'callback'];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
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
     * process apple token -> tap token -> charge user
     * @return array
     */
    public function actionProcessPayment()
    {
        $order_uuid = Yii::$app->request->getBodyParam ('order_uuid');
        $token = \Yii::$app->request->getBodyParam ('token');

        //$paymentMethod = PaymentMethod::findOne(['payment_method_code' => 'Moyasar']);

        $order = $this->findOrder($order_uuid);

        Yii::$app->tapPayments->setApiKeys(
            $order->restaurant->live_api_key,
            $order->restaurant->test_api_key,
            false
        );

        //convert apple pay token to Tap apple pay token
        //https://developers.tap.company/docs/apple-pay-token

        if($token) {
            $response = \Yii::$app->tapPayments->fromApplePayToken($token);
            $responseContent = json_decode($response->content);

            \Yii::error($responseContent);

            $token = $responseContent->id;

            return [
                "success" => true,
                "token" => $token
            ];
        }

        return [
            "success" => false,
            "message" => "no token"
        ];
    }

    /**
     * @return void
     */
    public function actionValidateMerchant() {

        $validationURL = \Yii::$app->request->getBodyParam("validationURL");
        $restaurant_uuid = \Yii::$app->request->getBodyParam("restaurant_uuid");

        $store = $this->findModel($restaurant_uuid);

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
            "domainName" => $store->restaurant_domain,// 'dash.plugn.io',
            "displayName" => $store->name,//'Plugn',
            "initiative" => "web",
            "initiativeContext" => $store->restaurant_domain,//"dash.plugn.io"
        ];

        try {
            $client = new \GuzzleHttp\Client([
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
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findOrder($order_uuid)
    {
        $model = Order::find()
            ->andWhere([
                'order_uuid' => $order_uuid
            ])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($store_uuid =  null)
    {
        $model = Restaurant::find()->andWhere(['restaurant_uuid' => $store_uuid]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}