<?php

namespace common\components;

use yii\base\Component;
use yii\httpclient\Client;


class ApplePay extends Component
{
    /**
     * @return \yii\httpclient\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function registerMerchant($store) {

        $url = $store->is_sandbox? "https://apple-pay-gateway-cert.apple.com/paymentservices/registerMerchant" :
            "https://apple-pay-gateway.apple.com/paymentservices/registerMerchant";

        $data = [
            "domainNames" => [$store->restaurant_domain],
            "encryptTo" => "merchant.io.plugn.dashboard",
            "merchantUrl" => "plugn.io",
            "partnerInternalMerchantIdentifier" => $store->getMerchantIdentifier(),
            "partnerMerchantName" => $store->name
        ];

        $client = new Client();

        return $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setFormat(Client::FORMAT_JSON)
            ->setData($data)
            ->addHeaders([
                'content-type' => 'application/json',
            ])
            ->send();
    }
}