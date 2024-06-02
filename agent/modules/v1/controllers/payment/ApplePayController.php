<?php

namespace agent\modules\v1\controllers\payment;

use api\modules\v2\controllers\BaseController;
use yii\httpclient\Client;

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

        $validationURL = Yii::$app->request->getBodyParam("validationURL");

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($validationURL)
            /*->addHeaders([
                'Authorization' => 'Bearer ', //YOUR_MERCHANT_ID_TOKEN
            ])*/
            ->setData([
                "merchantIdentifier" => 'merchant.io.plugn.dashboard',
                "domainName" => 'dash.plugn.io',
                "displayName" => 'Plugn'
            ])
            ->send();

        Yii::debug($response);

        return $response->data;
    }
}