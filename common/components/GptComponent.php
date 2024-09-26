<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;

class GptComponent extends Component
{
    public $apiEndpoint = 'http://localhost:8083/';

    public $token;

    /**
     * @inheritdoc
     */
    public function init() {
        // Fields required by default
        $requiredAttributes = ['token'];

        // Process Validation
        foreach ($requiredAttributes as $attribute) {
            if ($this->$attribute === null) {
                throw new InvalidConfigException(strtr('"{class}::{attribute}" cannot be empty.', [
                    '{class}' => static::className(),
                    '{attribute}' => '$' . $attribute
                ]));
            }
        }

        parent::init();
    }

    public function call($method, $url, $data = []) {
        $client = new Client();

        return $client->createRequest()
            ->setMethod($method)
            ->setUrl($this->apiEndpoint . $url)
            ->setFormat(Client::FORMAT_JSON)
            ->setData($data)
            ->addHeaders([
                'Authorization' =>'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();
    }
}