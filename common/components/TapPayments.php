<?php

namespace common\components;

use common\models\ApiLog;
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
     * @var string Generated link sends user directly to VISA/MASTER portal
     */
    const GATEWAY_MADA = "src_sa.mada";

    /**
     * @var string Generated link sends user directly to VISA/MASTER portal
     */
    const GATEWAY_BENEFIT = "src_bh.benefit";

    /**
     * @var float gateway fee charged by portal
     */
    public $madaGatewayFee = 0.015; // How much is charged per Mada transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $benefitGatewayFee = 0.015; // How much is charged per BENEFIT transaction

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
    public $minChargeAmount = 4; // How much is charged per KNET transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $creditcardGatewayFeePercentage = 0.025; // How much is charged per Creditcard transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $minCreditcardGatewayFee = 0; // How much is charged per Creditcard transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $minMadaGatewayFee = 0; // How much is charged per Creditcard transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $minBenefitGatewayFee = 0; // How much is charged per Creditcard transaction

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
            Yii::info('Plugn Live gateway');
            $this->plugnScretApiKey = $this->plugnLiveApiKey;
        } else {
            Yii::info('Plugn Sandbox gateway');
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
    public function setApiKeys($liveKey, $testKey, $is_sandbox = false)
    {
        $this->vendoerLiveApiKey = $liveKey;
        $this->vendorTestApiKey = $testKey;

        if (!$is_sandbox && $this->gatewayToUse == self::USE_LIVE_GATEWAY) {
            Yii::info('Vendor Live gateway'. $this->vendoerLiveApiKey);
            $this->vendorSecretApiKey = $this->vendoerLiveApiKey;
        } else {
            Yii::info('Vendor Sandbox gateway'. $this->vendorTestApiKey);
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
    public function uploadFileToTap($file_path, $purpose, $title, $params = [])
    {
        $fileEndpoint = $this->apiEndpoint . "/files";

        $fileParams = array_merge($params, [
            "purpose" => $purpose,
            "title" => $title,
            "file_link_create" => '0'
        ]);

        $client = new Client();

        return $client->createRequest()
                ->setMethod('POST')
                ->setUrl($fileEndpoint)
                ->setData($fileParams)
                ->addFile('file', $file_path)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->plugnScretApiKey,
                    'content-type' => 'application/json',
                ])
                ->send();
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
                "en" => $restaurant->company_name. ' - Plugn @'. date('Y-m-d H:m:s'),
                "ar" => $restaurant->name_ar. ' - Plugn @'. date('Y-m-d H:m:s')
            ],
            "type" => $restaurant->business_type,
            "entity" => [
                "legal_name" => [
                    "en" => $restaurant->company_name . '- Plugn @'. date('Y-m-d H:m:s'),
                    "ar" => $restaurant->name_ar. '- Plugn @'. date('Y-m-d H:m:s')
                ],
                "is_licensed" => 'true',
                "license_number" => $restaurant->license_number,
                "country" => $restaurant->country->iso,
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
                            "country_code" => $restaurant->owner_phone_country_code,
                            "number" => str_replace(' ','',(str_replace('+'.$restaurant->owner_phone_country_code, '',$restaurant->owner_number)))
                        ]
                    ]
                ],
                "identification" => [
                    [
                        "type" => "Identity Card",
                        "issuing_country" => $restaurant->country->iso,
                        "images" => [
                            $restaurant->identification_file_id_front_side,
                            $restaurant->identification_file_id_back_side,
                        ]
                    ]
                ],
            ],
            "brands" => [
                [
                    "name" => [
                        "en" => $restaurant->company_name. '- Plugn',
                        "ar" => $restaurant->name_ar. '- Plugn'
                    ],
                    "logo" => $restaurant->logo_file_id,
                    "website" => $restaurant->restaurant_domain,
                    "sector" => [
                        $restaurant->vendor_sector
                    ]
                ]
            ],
            "metadata" =>  [
              "mtd" => "Plugn Platform"
            ]
        ];

        if (
            $restaurant->iban_certificate_file_id
        ) {
            $ibanDocument = [
                "type" => "IBAN Certificate",
                "number" => "",
                "issuing_country" => $restaurant->country->iso,
                "issuing_date" => "",
                "expiry_date" => "",
                "images" => [
                    $restaurant->iban_certificate_file_id
                ]
            ];

            array_push($bussinessParams['entity']['documents'], $ibanDocument);
        }

        if (
            $restaurant->authorized_signature_file_id &&
            $restaurant->commercial_license_file_id
        ) {
            $authorizedSignatureDocument = [
                "type" => "Authorized Signature",
                "number" => 1,
                "issuing_country" => $restaurant->country->iso,
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
                "issuing_country" => $restaurant->country->iso,
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

        $headers = [
            'authorization' => 'Bearer ' . $this->plugnScretApiKey,
            'content-type' => 'application/json',
        ];

        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($bussinessEndpoint)
                ->setData($bussinessParams)
                ->addHeaders($headers)
                ->send();

        if($restaurant) {// && $restaurant->enable_debugger
            $log = new ApiLog;
            $log->restaurant_uuid = $restaurant->restaurant_uuid;
            $log->method = "POST";
            $log->endpoint = $bussinessEndpoint;
            $log->request_headers = print_r($headers, true);
            $log->request_body = print_r($bussinessParams, true);
            $log->response_headers = print_r($response->headers, true);
            $log->response_body = print_r($response->data, true);
            $log->save();
        }

        return $response;
    }

    /**
     * get business status update
     * @param $restaurant
     * @return \yii\httpclient\Response
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function getBussiness($restaurant)
    {
        $bussinessEndpoint = $this->apiEndpoint . "/business/" . $restaurant->business_id;

        $client = new Client();

        return $client->createRequest()
            ->setMethod('GET')
            ->setUrl($bussinessEndpoint)
            ->addHeaders([
                'authorization' => 'Bearer ' . $this->plugnScretApiKey,
                'content-type' => 'application/json',
            ])
            ->send();
    }

    /**
     * retrive merchant details
     * @param $merchant_id
     * @return \yii\httpclient\Response
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function fetchMerchant($merchant_id)
    {
        $apiEndpoint = $this->apiEndpoint . "/merchant/" . $merchant_id;

        $client = new Client();

        return $client->createRequest()
            ->setMethod('GET')
            ->setUrl($apiEndpoint)
            ->addHeaders([
                'authorization' => 'Bearer ' . $this->plugnScretApiKey,
                'content-type' => 'application/json',
            ])
            ->send();
    }

    /**
     * Create a merchant account
     * @param type $restaurant_name
     * @param type $business_id
     * @param type $business_entity_id
     * @param type $iban
     * @return type
     */
    public function createMerchantAccount($company_name, $currency, $business_id, $business_entity_id, $iban, $restaurant = null)
    {
        $merchantEndpoint = $this->apiEndpoint . "/merchant";

        $merchantParams = [
            "display_name" => $company_name,
            "business_id" => $business_id,
            "business_entity_id" => $business_entity_id,
            "bank_account" => [
                "iban" => $iban
            ],
            "charge_currenices" => [
                $currency
            ]
        ];

        $client = new Client();

        $headers = [
            'authorization' => 'Bearer ' . $this->plugnScretApiKey,
            'content-type' => 'application/json',
        ];

        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($merchantEndpoint)
                ->setData($merchantParams)
                ->addHeaders($headers)
                ->send();

        if($restaurant) {// && $restaurant->enable_debugger
            $log = new ApiLog();
            $log->restaurant_uuid = $restaurant->restaurant_uuid;
            $log->method = "POST";
            $log->endpoint = $merchantEndpoint;
            $log->request_headers = print_r($headers, true);
            $log->request_body = print_r($merchantParams, true);
            $log->response_headers = print_r($response->headers, true);
            $log->response_body = print_r($response->data, true);
            $log->save();
        }

        return $response;
    }

    /**
     * Create an operator
     * @param type $restaurant_name
     * @param type $wallet_id
     */
    public function createAnOperator($restaurant_name, $wallet_id, $developer_id, $restaurant = null)
    {
        $operatorEndpoint = $this->apiEndpoint . "/operator";

        $operatorParams = [
            "wallet_id" => $wallet_id,
            "developer_id" => $developer_id,
            "name" => $restaurant_name,
        ];

        $client = new Client();

        $headers = [
            'authorization' => 'Bearer ' . $this->plugnScretApiKey,
            'content-type' => 'application/json',
        ];

        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($operatorEndpoint)
                ->setData($operatorParams)
                ->addHeaders($headers)
                ->send();

        if($restaurant) {// && $restaurant->enable_debugger
            $log = new ApiLog();
            $log->restaurant_uuid = $restaurant->restaurant_uuid;
            $log->method = "POST";
            $log->endpoint = $operatorEndpoint;
            $log->request_headers = print_r($headers, true);
            $log->request_body = print_r($operatorParams, true);
            $log->response_headers = print_r($response->headers, true);
            $log->response_body = print_r($response->data, true);
            $log->save();
        }

        return $response;
    }

    /**
     * Create a refund for a customer
     */
    public function createRefund(
        $chargeId,
        $amount,
        $currency,
        $reason="requested_by_customer",
        $restaurant = null
    ) {
        $refundEndpoint = $this->apiEndpoint . "/refunds";

        $refundParams = [
          "charge_id" => $chargeId,
          "amount" => $amount,
          "currency" => $currency,
          "reason" => $reason,
        ];

        $client = new Client();

        $headers = [
            'authorization' => 'Bearer ' . $this->vendorSecretApiKey,
            'content-type' => 'application/json',
        ];

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($refundEndpoint)
            ->setData($refundParams)
            ->addHeaders($headers)
            ->send();

        if($restaurant) {// && $restaurant->enable_debugger
            $log = new ApiLog();
            $log->restaurant_uuid = $restaurant->restaurant_uuid;
            $log->method = "POST";
            $log->endpoint = $refundEndpoint;
            $log->request_headers = print_r($headers, true);
            $log->request_body = print_r($refundParams, true);
            $log->response_headers = print_r($response->headers, true);
            $log->response_body = print_r($response->data, true);
            $log->save();
        }

        return $response;
    }

    /**
     * Create a charge for redirect
     */
    public function createCharge(
        $currency,
        $desc = "Pay",
        $statementDesc = "",
        $ref,
        $amount ,
        $firstName,
        $email,
        $country_code ,
        $phone,
        $platform_fee,
        $redirectUrl,
        $webhookUrl ,
        $gateway,
        $warehouse_fee = 0,
        $warehouse_delivery_charges = 0,
        $country_name = null,
        $restaurant_uuid = null
    ) {
        $chargeEndpoint = $this->apiEndpoint . "/charges";

        $phone =  str_replace(' ', '', $phone);
        $phone =  str_replace('+'.$country_code, '', $phone);

        $chargeParams = [
            "amount" => $amount,
            "currency" => $currency,
            "threeDSecure" => true,
            "save_card" => false,
            "description" => $desc,
            "statement_descriptor" => $statementDesc,
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
                    "country_code" => $country_code,
                    "number" => $phone
                ]
            ],
            "destinations" => [
                "destination" => []
            ],
            "source" => [
                "id" => $gateway
            ],
            "redirect" => [
                "url" => $redirectUrl
            ],
            "post" => [
                "url" => $webhookUrl
            ]
        ];

        if($platform_fee > 0) {
          
          if($gateway == static::GATEWAY_KNET) {

            //if greater than 10KD
            if (($amount * $this->knetGatewayFee) >= $this->minKnetGatewayFee) {
              $platform_fee = $amount *  ( $platform_fee  - $this->knetGatewayFee );
            }

            // if amount greater than  4 and  equal 10
            else if  ($amount > $this->minChargeAmount && ( ($amount * $this->knetGatewayFee) < $this->minKnetGatewayFee)){
               $platform_fee = ($amount *  $platform_fee ) - $this->minKnetGatewayFee;
            }

            //if amount less than or equal 4
            else if ($this->minChargeAmount >= $amount) {
               $platform_fee = 0.100;
            }
          } 
          else if($gateway == static::GATEWAY_BENEFIT) 
          {
              $platform_fee = $amount *  ($platform_fee  - $this->benefitGatewayFee );
          }
          else 
          {
             $platform_fee = $amount *  ($platform_fee  - $this->creditcardGatewayFeePercentage);
          }

           if($warehouse_fee > 0) 
           {
             $charge_amount = $warehouse_fee + $platform_fee;
           }
           else 
           {
               $charge_amount = $platform_fee;
           }

            if($warehouse_delivery_charges > 0 && $country_name != null && $country_name == 'Kuwait') {
                $charge_amount = $warehouse_delivery_charges + $charge_amount;
            }

           $destination = [
               "id" => $this->destinationId,
               "amount" => $charge_amount,
               "currency" => $currency,
           ];

           array_push($chargeParams['destinations']['destination'], $destination);

         } else if ($platform_fee == 0 && ($warehouse_fee > 0 || ($warehouse_delivery_charges > 0  && $country_name != null && $country_name == 'Kuwait'))) {

           $charge_amount = 0;

           if($warehouse_fee > 0)
             $charge_amount = $warehouse_fee + $charge_amount;

           if($warehouse_delivery_charges > 0  && $country_name != null && $country_name == 'Kuwait')
             $charge_amount = $warehouse_delivery_charges + $charge_amount;

           $destination = [
               "id" => $this->destinationId,
               "amount" => $charge_amount,
               "currency" => $currency,
           ];

           array_push($chargeParams['destinations']['destination'], $destination);
         }

         //for debug

        if (YII_ENV == 'prod') {
             
            Yii::$app->eventManager->track(
                'Tap Charge Attempt', 
                $chargeParams, 
                null, 
                'Tap Payments');
        }

        $client = new Client();

        $headers = [
            'authorization' => 'Bearer ' . $this->vendorSecretApiKey,
            'content-type' => 'application/json',
        ];

        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($chargeEndpoint)
                ->setData($chargeParams)
                ->addHeaders($headers)
                ->send();

        /*if($restaurant_uuid) {
            $log = new ApiLog();
            $log->restaurant_uuid = $restaurant_uuid;
            $log->method = "POST";
            $log->endpoint = $chargeEndpoint;
            $log->request_headers = print_r($headers, true);
            $log->request_body = print_r($chargeParams, true);
            $log->response_headers = print_r($response->headers, true);
            $log->response_body = print_r($response->data, true);
            $log->save();
        }*/

        return $response;
    }

    /**
     * The dynamic currency conversion API allows merchants
     * to get realtime and accurate currency exchange rates
     * as used by Tap and add a markup to those rates.
     * @param type $amount
     */
    public function createDCC($transferCurrencyCode, $amount, $toCurrency = 'USD')
    {
        $dccEndpoint = $this->apiEndpoint . "/currency/dcc/v1";

        $dccParams = [
            "from" => [
              "currency"  => 'KWD',
              "value" => $amount
            ],
            "to" => [
              [
                "currency" => 'USD',
                "dcc_rate" => "2.5%"
              ]
            ],
            "by" => "PROVIDER"
        ];

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($dccEndpoint)
                ->setData($dccParams)
                ->addHeaders([
                    'authorization' => 'Bearer sk_test_p07NquMX4HgwLT8mycdJnZv5'
                ])
                ->send();

        return $response;
    }

    /**
     * isValidToken
     * @param  string $chargeId
     */
    public function retrieveToken($tokenId)
    {
        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($this->apiEndpoint . "/tokens/" . $tokenId)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->vendorSecretApiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }

    /**
     * Update bank account connected with wallet
     * @param  string $wallet_id
     * @param  string $iban
     * @param  string $swift_code
     * @param  string $account_number
     */
    public function updateBankAccount($wallet_id, $iban, $swift_code = null, $account_number = null)
    {
        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('PUT')
            ->setUrl($this->apiEndpoint . "/bankaccount")
            ->setData([
                'iban' => $iban,
                'swift_code' => $swift_code,
                'account_number' => $account_number
            ])
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

    /*public function addDestination($display_name)
    {
        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($this->apiEndpoint . "/destination")
            ->addHeaders([
                'authorization' => 'Bearer ' . $this->vendorSecretApiKey,
                'content-type' => 'application/json',
            ])
            ->setData([
                'display_name' => $display_name,
                'business_id' => $swift_code,
                'business_entity_id' => $account_number,
                "brand_id" => $brand_id,
                "branch_id" => $branch_id,
                "bank_account": [
                    "iban":
                ],
                "settlement_by": "Acquirer"
            ])
            ->send();

        return $response;
    }*/

    public function getDestination($destination_id)
    {
        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($this->apiEndpoint . "/destination/" . $destination_id)
            ->addHeaders([
                'authorization' => 'Bearer ' . $this->plugnScretApiKey, 
                'content-type' => 'application/json',
            ])
            ->send();

        return $response;
    }

    public function listDestination()
    {
        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($this->apiEndpoint . "/destination/list")
            ->addHeaders([
                'authorization' => 'Bearer ' . $this->plugnScretApiKey,
                'content-type' => 'application/json',
            ])
            ->setData([
                "period" => [
                    "date" => [
                        "from" => 0,
                        "to" => time()
                    ]
                ],
                "limit" => 20,
                "page" => 1
            ])
            ->send();

        return $response;
    }

    public function deleteDestination($destination_id)
    {
        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('DELETE')
            ->setUrl($this->apiEndpoint . "/destination/" .$destination_id)
            ->addHeaders([
                'authorization' => 'Bearer ' . $this->plugnScretApiKey,
                'content-type' => 'application/json',
            ])
            ->send();

        return $response;
    }

    /**
     * checkTapSignature
     * @param  [type]  $id                           [description]
     * @param  boolean $showUpdatedFlashNotification [description]
     * @return self                                [description]
     */
     public function checkTapSignature($toBeHashedString, $headerSignature ) {
         //***Generate The Signature*** :

         $signature = hash_hmac('sha256', $toBeHashedString, $this->vendorSecretApiKey);

         return $signature == $headerSignature;
     }
}
