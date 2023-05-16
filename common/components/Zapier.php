<?php

namespace common\components;

use yii\base\Component;
use yii\httpclient\Client;

class Zapier extends Component
{
    public function webhook($webook, $data)
    {
        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($webook)
            ->setData($data)
            ->send();

        return $response;
    }
}