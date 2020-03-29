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
 * @author Khalid Al-Mutawa <khalid@bawes.net>
 * @link http://www.bawes.net
 */
class TapPayments extends Component {

    const USE_TEST_GATEWAY = 1;
    const USE_LIVE_GATEWAY = 2;

    /**
     * @var string Which gateway to use, test or live?
     */
    public $gatewayToUse;

    /**
     * @var string Generated link sends user directly to KNET portal
     */
    const GATEWAY_KNET = "src_kw.knet";
    /**
     * @var string Generated link sends user directly to VISA/MASTER portal
     */
    const GATEWAY_VISA_MASTERCARD = "src_card";

    /**
     * @var float gateway fee charged by portal
     */
    public $knetGatewayFee = 0.005; // How much is charged per KNET transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $creditcardGatewayFeePercentage = 0.025; // How much is charged per Creditcard transaction

    /**
     * @var string secret api key to use will be stored here
     */
     public $secretApiKey;

     /**
      * @var string Variable for live api key to be stored in
      */
      public $liveApiKey;

      /**
       * @var string Variable for test api key to be stored in
       */
     public $testApiKey;

    private $apiEndpoint = "https://api.tap.company/v2";

    /**
     * @inheritdoc
     */
    public function init() {
        // Fields required by default
        $requiredAttributes = ['gatewayToUse', 'liveApiKey', 'testApiKey'];

        // Process Validation
        foreach ($requiredAttributes as $attribute) {
            if ($this->$attribute === null) {
                throw new InvalidConfigException(strtr('"{class}::{attribute}" cannot be empty.', [
                    '{class}' => static::className(),
                    '{attribute}' => '$' . $attribute
                ]));
            }
        }

        // Set the API key we're going to use
//        if ($this->gatewayToUse == self::USE_LIVE_GATEWAY) {
//            $this->secretApiKey = $this->liveApiKey;
//        }else{
//            $this->secretApiKey = $this->testApiKey;
//        }

          $this->secretApiKey = $this->testApiKey;
          
        parent::init();
    }


    /**
     * Create a charge for redirect
     */
    public function createCharge($desc = "Pay", $statementDesc = "", $ref, $amount, $firstName, $email, $phone, $redirectUrl, $gateway) {
        $chargeEndpoint = $this->apiEndpoint."/charges";

        $chargeParams = [
          "amount" => $amount,
          "currency" => "KWD",
          "threeDSecure" => true,
          "save_card" => false,
          "description" => $desc,
          "statement_descriptor" => $statementDesc,
          "metadata" => [
            // "udf1" => "test 1",
            // "udf2" => "test 2"
          ],
          "reference" => [
            "transaction" => $ref,
            "order" => $ref
          ],
          "receipt" => [
            "email" => false,
            "sms" => false
          ],
          "customer" => [
            "first_name" => $firstName,
            "email" => $email,
            "phone" => [
              "country_code" => "965",
              "number" => $phone
            ]
          ],
          "source" => [
            "id" => $gateway
          ],
          "redirect" => [
            "url" => $redirectUrl
          ]
        ];

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($chargeEndpoint)
            ->setData($chargeParams)
            ->addHeaders([
                'authorization' => 'Bearer '.$this->secretApiKey,
                'content-type' => 'application/json',
            ])
            ->send();

        return $response;

    }


    /**
     * Check charge object for status updates
     * @param  string $chargeId
     */
    public function retrieveCharge($chargeId)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($this->apiEndpoint."/charges/".$chargeId)
            ->addHeaders([
                'authorization' => 'Bearer '.$this->secretApiKey,
                'content-type' => 'application/json',
            ])
            ->send();

        return $response;
    }


}
