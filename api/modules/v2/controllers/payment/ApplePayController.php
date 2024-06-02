<?php

namespace api\modules\v2\controllers\payment;

use api\modules\v2\controllers\BaseController;
use yii\httpclient\Client;

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
        $body = Yii::$app->request->getBodyParams();

        return [
            "success" => true,
            "body" => $body
        ];
    }

    /**
     * @return void
     */
    public function actionValidateMerchant() {
        $body = Yii::$app->request->getBodyParams();

        return [
            "success" => true,
            "body" => $body
        ];
        /*
        $validationURL = Yii::$app->request->getBodyParam("validationURL");

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($validationURL)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token, //YOUR_MERCHANT_ID_TOKEN
            ])
            ->setData([
                "merchantIdentifier" => 'merchant.com.your.merchant.id',
                "domainName" => 'your-website.com',
                "displayName" => 'Your Merchant Name'
            ])
            ->send();

        Yii::debug($response);

        return $response->data;*/
    }
}