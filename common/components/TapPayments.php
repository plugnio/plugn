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
class TapPayments extends Component
{
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
    public $knetGatewayFee = 0.01; // How much is charged per KNET transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $minKnetGatewayFee = 0.100; // How much is charged per KNET transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $minChargeAmount = 5; // How much is charged per KNET transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $creditcardGatewayFeePercentage = 0.025; // How much is charged per Creditcard transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $minCreditcardGatewayFee = 0; // How much is charged per Creditcard transaction

    /**
     * @var string destination id
     */
    public $destinationId;

    /**
     * @var string secret api key to use will be stored here
     */
    public $plugnScretApiKey;

    /**
     * @var string Variable for live api key to be stored in
     */
    public $plugnLiveApiKey;

    /**
     * @var string Variable for test api key to be stored in
     */
    public $plugnTestApiKey;

    /**
     * @var string secret api key to use will be stored here
     */
    public $vendorSecretApiKey;

    /**
     * @var string Variable for live api key to be stored in
     */
    public $vendoerLiveApiKey;

    /**
     * @var string Variable for test api key to be stored in
     */
    public $vendorTestApiKey;
    private $apiEndpoint = "https://api.tap.company/v2";

    /**
     * @inheritdoc
     */
    public function init()
    {
        // Fields required by default
        $requiredAttributes = ['gatewayToUse', 'plugnLiveApiKey', 'plugnTestApiKey','destinationId'];

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
        //These keys we will use it to (upload document - create a business - create a merchant - create an operator)
        if ($this->gatewayToUse == self::USE_LIVE_GATEWAY) {
            $this->plugnScretApiKey = $this->plugnLiveApiKey;
        } else {
            $this->plugnScretApiKey = $this->plugnTestApiKey;
        }

        parent::init();
    }

    /**
     * Set the api keys to use
     * @param  [type] $liveKey [description]
     * @param  [type] $testKey [description]
     * @return [type]          [description]
     */
    public function setApiKeys($liveKey, $testKey)
    {
        $this->vendoerLiveApiKey = $liveKey;
        $this->vendorTestApiKey = $testKey;

        if ($this->gatewayToUse == self::USE_LIVE_GATEWAY) {
            $this->vendorSecretApiKey = $this->vendoerLiveApiKey;
        } else {
            $this->vendorSecretApiKey = $this->vendorTestApiKey;
        }
    }

    /**
     * upload a file to Tap
     * @param type $file_path
     * @param type $purpose
     * @param type $title
     * @return type
     */
    public function uploadFileToTap($file_path, $purpose, $title)
    {
        $fileEndpoint = $this->apiEndpoint . "/files";

        $fileParams = [
            "purpose" => $purpose,
            "title" => $title,
            "file_link_create" => '0'
        ];



        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($fileEndpoint)
                ->setData($fileParams)
                ->addFile('file', $file_path)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->plugnScretApiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }

    /**
     * Create a business
     * @param type $restaurant
     * @return type
     */
    public function createBussiness($restaurant)
    {
        $bussinessEndpoint = $this->apiEndpoint . "/business";

        $bussinessParams = [
            "name" => [
                "en" => $restaurant->name,
                "ar" => $restaurant->name_ar
            ],
            "type" => $restaurant->business_type,
            "entity" => [
                "legal_name" => [
                    "en" => $restaurant->name,
                    "ar" => $restaurant->name_ar
                ],
                "is_licensed" => 'true',
                "license_number" => $restaurant->license_number,
                "country" => "KW",
                "documents" => [],
                "bank_account" => [
                    "iban" => $restaurant->iban
                ]
            ],
            "contact_person" => [
                "name" => [
                    "first" => $restaurant->owner_first_name,
                    "last" => $restaurant->owner_last_name
                ],
                "contact_info" => [
                    "primary" => [
                        "email" => $restaurant->owner_email,
                        "phone" => [
                            "country_code" => "965",
                            "number" => $restaurant->owner_number
                        ]
                    ]
                ],
                "identification" => [
                    [
                        "type" => "Identity Card",
                        "issuing_country" => $restaurant->identification_issuing_country,
                        "issuing_date" => $restaurant->identification_issuing_date,
                        "expiry_date" => $restaurant->identification_expiry_date,
                        "images" => [
                            $restaurant->identification_file_id
                        ]
                    ]
                ],
            ],
            "brands" => [
                [
                    "name" => [
                        "en" => $restaurant->name,
                        "ar" => $restaurant->name_ar
                    ],
                    "website" => $restaurant->restaurant_domain,
                    "sector" => [
                        $restaurant->vendor_sector
                    ]
                ]
            ],
        ];



        if (
                $restaurant->authorized_signature_issuing_country &&
                $restaurant->authorized_signature_issuing_date &&
                $restaurant->authorized_signature_expiry_date &&
                $restaurant->authorized_signature_file_id &&
                $restaurant->commercial_license_issuing_country &&
                $restaurant->commercial_license_issuing_date &&
                $restaurant->commercial_license_expiry_date &&
                $restaurant->commercial_license_file_id
        ) {
            $authorizedSignatureDocument = [
                "type" => "Authorized Signature",
                "number" => 1,
                "issuing_country" => $restaurant->authorized_signature_issuing_country,
                "issuing_date" => $restaurant->authorized_signature_issuing_date,
                "expiry_date" => $restaurant->authorized_signature_expiry_date,
                "images" => [
                    $restaurant->authorized_signature_file_id
                ]
            ];

            array_push($bussinessParams['entity']['documents'], $authorizedSignatureDocument);


            $commercialLicenseDocument = [
                "type" => "Commercial License",
                "number" => 1,
                "issuing_country" => $restaurant->commercial_license_issuing_country,
                "issuing_date" => $restaurant->commercial_license_issuing_date,
                "expiry_date" => $restaurant->commercial_license_expiry_date,
                "images" => [
                    $restaurant->commercial_license_file_id
                ]
            ];

            array_push($bussinessParams['entity']['documents'], $commercialLicenseDocument);


            $bussinessParams['entity']['is_licensed'] = 'true';
        } else {
            $bussinessParams['entity']['is_licensed'] = 'false';
        }



        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($bussinessEndpoint)
                ->setData($bussinessParams)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->plugnScretApiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }

    /**
     * Create a merchant account
     * @param type $restaurant_name
     * @param type $business_id
     * @param type $business_entity_id
     * @param type $iban
     * @return type
     */
    public function createMergentAccount($restaurant_name, $business_id, $business_entity_id, $iban)
    {
        $merchantEndpoint = $this->apiEndpoint . "/merchant";

        $merchantParams = [
            "display_name" => $restaurant_name,
            "business_id" => $business_id,
            "business_entity_id" => $business_entity_id,
            "bank_account" => [
                "iban" => $iban
            ],
            "charge_currenices" => [
                "KWD"
            ]
        ];

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($merchantEndpoint)
                ->setData($merchantParams)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->plugnScretApiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }

    /**
     * Create an operator
     * @param type $restaurant_name
     * @param type $wallet_id
     */
    public function createAnOperator($restaurant_name, $wallet_id, $developer_id)
    {
        $operatorEndpoint = $this->apiEndpoint . "/operator";

        $operatorParams = [
            "wallet_id" => $wallet_id,
            "developer_id" => $developer_id,
            "name" => $restaurant_name,
        ];

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($operatorEndpoint)
                ->setData($operatorParams)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->plugnScretApiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }

    /**
     * Create a refund for a customer
     */
    public function createRefund($chargeId, $amount, $currency = "KWD", $reason="requested_by_customer")  {

        $refundEndpoint = $this->apiEndpoint . "/refunds";

        $refundParams = [
          "charge_id" => $chargeId,
          "amount" => $amount,
          "currency" => $currency,
          "reason" => $reason,
      ];


        $client = new Client();
        $response = $client->createRequest()
                      ->setMethod('POST')
                      ->setUrl($refundEndpoint)
                      ->setData($refundParams)
                      ->addHeaders([
                          'authorization' => 'Bearer ' . $this->vendorSecretApiKey,
                          'content-type' => 'application/json',
                      ])
                      ->send();

        return $response;
    }

    /**
     * Create a charge for redirect
     */
    public function createCharge($desc = "Pay", $statementDesc = "", $ref, $amount, $firstName, $email, $phone,$platform_fee, $redirectUrl, $gateway)
    {

        if($platform_fee > 0){
          if($gateway == static::GATEWAY_KNET){


            //if greater than 10KD
          if (($amount * $this->knetGatewayFee) >= $this->minKnetGatewayFee) {
              $platform_fee = $amount *  ( $platform_fee  - $this->knetGatewayFee );
            }
            // if amount between > 5 and < 10
            else if  ($amount > $this->minChargeAmount && (($amount * $this->knetGatewayFee) < $this->minKnetGatewayFee)){ //10KD
              $platform_fee = ($amount *  $platform_fee ) - $this->minKnetGatewayFee;
            }
            else if ($this->minChargeAmount >= $amount) {
              $platform_fee = 0.100;
            }

          } else {
            $platform_fee = $amount *  ($platform_fee  - $this->creditcardGatewayFeePercentage);
          }
        }


        $chargeEndpoint = $this->apiEndpoint . "/charges";

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
                "sms" => true
            ],
            "customer" => [
                "first_name" => $firstName,
                "email" => $email,
                "phone" => [
                    "country_code" => "965",
                    "number" => $phone
                ]
            ],
            "destinations" => [
                "destination" => [
                    [
                      "id" => $this->destinationId,
                      "amount" => $platform_fee,
                      "currency" => "KWD",
                    ]
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
                    'authorization' => 'Bearer ' . $this->vendorSecretApiKey,
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
                ->setUrl($this->apiEndpoint . "/charges/" . $chargeId)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->vendorSecretApiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }


    /**
     * Check refund object for status updates
     * @param  string $chargeId
     */
    public function retrieveRefund($refundId)
    {
        $client = new Client();

        $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($this->apiEndpoint . "/refunds/" . $refundId)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->vendorSecretApiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }
}
