<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;

/**
 * SmsComponent class to send SMS
 */
class SmsComponent extends Component
{
    /**
     * @var string Variable for test api key to be stored in
     */
    private $apiEndpoint = "https://api.future-club.com/falconapi/fccsms.aspx";
    //http://62.215.226.164/fccsms.aspx";

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
    public function sendSms($phone_number, $orderUuid)
    {
        $phone_number = str_replace('+', '', $phone_number);
        $phone_number = str_replace(' ', '', $phone_number);

        //check if phone number contain country code

        if (strpos($phone_number, '965') !== 0) {
            $phone_number = "965" . $phone_number;
        }

        $urlShortner = 'https://i.plugn.io/' . $orderUuid;

        $message = '
          Order #'.  $orderUuid .' has been accepted. Get order status updates at '. $urlShortner .'.
        ';

        //?IID=accountiid&UID=username&P=apikey&S=senderid&G=965********&M=message&L=L
//96598765432 96598773607
        $smsParams = [
            "IID" => "1993",
            "UID" => "usrbawes",
            "S" => "Plugn",
            "G" => $phone_number,
            "M" => 'Order #'.  $orderUuid .' has been accepted. Get order status updates at '. $urlShortner,
            "L" => "L",
        ];

        //https://api.future-club.com/falconapi/fccsms.aspx?IID=1993&UID=usrbawes&S=Plugn&G=96594424722&M=Test-1 by FCC1&L=L

        $client = new Client();

        $response = $client->createRequest()
                ->setMethod('GET')
                ->setHeaders([
                    "X-API-KEY" => "886ea93b-20a0-4b2f-9883-9cf251d038b7"
                ])
                ->setUrl($this->apiEndpoint)
                ->setData($smsParams)
                ->send();

        return $response;
    }
}
