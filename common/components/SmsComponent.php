<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;

/**
 * TapPayments class for payment processing
 *
 * @author Saoud Al-Turki <saoud@plugn.io>
 * @link http://www.plugn.io
 */
class SmsComponent extends Component
{
    /**
     * @var string Variable for test api key to be stored in
     */
    private $apiEndpoint = "http://62.215.226.164/fccsms.aspx";

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Send SMS
     */
    public function sendSms($phone_number,$orderUuid)
    {

        $phone_number = str_replace('+', '', $phone_number);
        $phone_number = str_replace(' ', '', $phone_number);

        $urlShortner = 'https://i.plugn.io/' . $orderUuid;

        $message = '
          Order #'.  $orderUuid .' has been accepted. Get order status updates at '. $urlShortner .'.
        ';

        $smsParams = [
            "UID" => "usrbawes",
            "p" => "bawes1452",
            "S" => "Plugn",
            "G" => $phone_number,
            "M" => 'Order #'.  $orderUuid .' has been accepted. Get order status updates at '. $urlShortner,
            "L" => "L",
        ];

        $client = new Client();

        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($this->apiEndpoint)
                ->setData($smsParams)
                ->send();

        return $response;
    }
}
