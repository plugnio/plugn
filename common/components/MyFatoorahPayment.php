<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;

/**
 * MyFatoorahPayments class for payment processing
 *
 * @author Saoud Al-Turki <saoud@plugn.io>
 * @link http://www.plugn.io
 */

class MyFatoorahPayment extends Component
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
    const GATEWAY_KNET = 'kn';

    /**
     * @var string Generated link sends user directly to VISA/MASTER portal
     */
    const GATEWAY_VISA_MASTERCARD = 2;

    /**
     * @var string Generated link sends user directly to AMEX portal
     */
    const GATEWAY_AMEX = 3;

    /**
     * @var string Generated link sends user directly to SADAD portal
     */
    const GATEWAY_SADAD = 's';


    /**
     * @var string Generated link sends user directly to MADA portal
     */
    const GATEWAY_MADA = 'md';

    /**
     * @var string Generated link sends user directly to UAE Debit Cards	 portal
     */
    const GATEWAY_UAE = 7;

    /**
     * @var string Generated link sends user directly to Qatar Debit Cards portal
     */
    const GATEWAY_QATAR = 8;

    /**
     * @var string Generated link sends user directly to KAFST portal
     */
    const GATEWAY_KFAST = 9;

    /**
     * @var string Generated link sends user directly to Mezza portal
     */
    const GATEWAY_MEZZA = 13;

    /**
     * @var string Generated link sends user directly to Oman NET portal
     */
    const GATEWAY_OMAN_NET = 15;


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
    public $sadadGatewayFee = 0.025; // How much is charged per Creditcard transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $minMadaGatewayFee = 0; // How much is charged per Creditcard transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $creditcardGatewayFeePercentage = 0.025; // How much is charged per Creditcard transaction

    /**
     * @var float gateway fee charged by portal
     */
    public $minCreditcardGatewayFee = 0; // How much is charged per Creditcard transaction


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
     * @var string test api key to use will be stored here
     */
    public $testApiKey;

    /**
     * @var string live api key to use will be stored here
     */
    public $liveApiKey;

    /**
     * @var string live api key to use will be stored here
     */
    public $apiKey;


    private $liveApiEndpoint = "https://apitest.myfatoorah.com/v2";

    private $testApiEndpoint = "https://apitest.myfatoorah.com/v2";


    private $apiEndpoint = "https://apitest.myfatoorah.com/v2";

    /**
     * @inheritdoc
     */
    public function init()
    {
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
        if ($this->gatewayToUse == self::USE_LIVE_GATEWAY) {
            $this->apiEndpoint = $this->liveApiEndpoint;
            $this->apiKey = $this->liveApiKey;
        } else {
            $this->apiEndpoint = $this->testApiEndpoint;
            $this->apiKey = $this->testApiKey;
        }

        parent::init();
    }

    /**
     * uploadSupplierDocument
     * @param type $file_path
     * @param type $title
     * @return type
     */
    public function uploadSupplierDocument($file_path, $fileType, $supplierCode)
    {

        $fileEndpoint = $this->apiEndpoint . "/UploadSupplierDocument";

        $fileParams = [
              "FileType" => $fileType,
              "SupplierCode" => $supplierCode
        ];


        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('PUT')
                ->setUrl($fileEndpoint)
                ->setData($fileParams)
                ->addFile('FileUpload', $file_path)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->apiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }

    /**
     * checkMyFatoorahSignature
     * @param  [type]  $id                           [description]
     * @param  boolean $showUpdatedFlashNotification [description]
     * @return self                                [description]
     */
     public static function checkMyFatoorahSignature($genericWebhookModel, $secretKey, $headerSignature ) {
         //***Generate The Signature*** :
         //1- Order all properties alphabetic
         //2-Encrypt the data with the secret key
         //3-Compare the signature

        ksort ( $genericWebhookModel );


         $sortedArray = '';

         $counter = 0;

         if(isset($genericWebhookModel['GatewayReference']))
            unset($genericWebhookModel['GatewayReference']);

         foreach($genericWebhookModel as $key => $model) {

           if($counter == 0)
            $sortedArray .=  $key . "=" . $model ;
           else
            $sortedArray .=   "," . $key . "=" . $model ;

           $counter++;

          }

         $signature = static::signMyfatoorahSignature($sortedArray, $secretKey);
         return $signature == $headerSignature;
     }


     public static function signMyfatoorahSignature($paramsArray, $secretKey ) {
          return base64_encode(hash_hmac('sha256', $paramsArray, $secretKey, true));
     }

    /**
     * Create  Supplier
     * @param type $restaurant
     * @return type
     */
    public function createSupplier($store)
    {
        $createSupplierEndpoint = $this->apiEndpoint . "/CreateSupplier";

        $store_phone_number =  str_replace(' ','',(str_replace('+', '00',$store->owner_number)));

        $supplierParams = [
          "SupplierName" => $store->company_name,
          "Mobile" => $store_phone_number,
          "Email" =>  $store->owner_email,
          "CommissionPercentage" => 0,
          "DepositTerms" => "Daily",
          "iban" => $store->iban,
          "IsActive" => "true"
        ];

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($createSupplierEndpoint)
                ->setData($supplierParams)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->apiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }

    /**
     * Edit  Supplier
     * @param type $restaurant
     * @return type
     */
    public function editSupplier($store)
    {
        $editSupplierEndpoint = $this->apiEndpoint . "/EditSupplier";

        $store_phone_number =  str_replace(' ','',(str_replace('+', '00',$store->owner_number)));

        $supplierParams = [
          "SupplierCode" => $store->supplierCode,
          "SupplierName" => $store->company_name,
          "Mobile" => $store_phone_number,
          "Email" =>  $store->owner_email,
          "CommissionValue" => $store->platform_fee,
          "IsActive" => "true"
        ];


        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($editSupplierEndpoint)
                ->setData($supplierParams)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->apiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }


    /**
   * Create a charge for redirect
   */
  public function createCharge($currency, $amount ,$firstName, $email, $country_code ,$phone, $redirectUrl, $orderUuid, $supplierCode , $platform_fee  , $paymentMethodId, $paymentMethodCode,$warehouse_fee = 0)
  {

      $chargeEndpoint = $this->apiEndpoint . "/ExecutePayment";


      $phone =  str_replace(' ', '', $phone);
      $phone =  str_replace("+". $country_code, '', $phone);

      $proposedShare = $amount;


      if($platform_fee > 0){
         if($paymentMethodCode == static::GATEWAY_KNET){

               //if greater than 10KD
              if (($amount * $this->knetGatewayFee) >= $this->minKnetGatewayFee){
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

        } else if($paymentMethodCode == static::GATEWAY_SADAD)
            $platform_fee = $amount *  ( $platform_fee  - $this->sadadGatewayFee );
          else if($paymentMethodCode == static::GATEWAY_MADA)
              $platform_fee = $amount *  ( $platform_fee  - $this->madaGatewayFee );
        else
           $platform_fee = $amount *  ($platform_fee  - $this->creditcardGatewayFeePercentage);


         if($warehouse_fee > 0)
           $platform_fee = $warehouse_fee + $platform_fee;

           $proposedShare = $proposedShare - $platform_fee;

       } else if ($platform_fee == 0 && $warehouse_fee > 0) {

         $proposedShare = $proposedShare - $warehouse_fee;

       }


      $chargeParams = [
            "PaymentMethodId" => $paymentMethodId,
            "CustomerName"=> $firstName,
            "DisplayCurrencyIso"=> $currency,
            "MobileCountryCode"=> "+". $country_code,
            "CustomerMobile"=> $phone,
            "CustomerEmail"=> $email,
            "InvoiceValue"=> $amount,
            "CallBackUrl"=> $redirectUrl,
            "ErrorUrl"=> $redirectUrl,
            "Language"=> "EN",
            "CustomerReference"=> $orderUuid, //ORDER UUID
            "Suppliers" => [
              [
                "SupplierCode" => $supplierCode,
                "ProposedShare" => $proposedShare,
                "InvoiceShare" => $amount
              ]
            ]
        ];


      $client = new Client();
      $response = $client->createRequest()
              ->setMethod('POST')
              ->setUrl($chargeEndpoint)
              ->setData($chargeParams)
              ->addHeaders([
                  'authorization' => 'Bearer ' . $this->apiKey,
                  'content-type' => 'application/json',
              ])
              ->send();

      return $response;
  }


      /**
       * retrieve all available and enabled Payment Methods spplier your portal account
       * with the commission charge that the customer may pay on the gateway.
       * @param  string $chargeId
       */
      public function initiatePayment($amount, $currencyCode)
      {

          $initiatePaymentParams = [
            "InvoiceAmount" => $amount,
            "CurrencyIso" => $currencyCode
          ];



          $client = new Client();
          $response = $client->createRequest()
                  ->setMethod('POST')
                  ->setUrl($this->apiEndpoint . "/InitiatePayment")
                  ->setData($initiatePaymentParams)
                  ->addHeaders([
                      'authorization' => 'Bearer ' . $this->apiKey,
                      'content-type' => 'application/json',
                  ])
                  ->send();

          return $response;
      }



    /**
   * Make a Refund
   */
  public function makeRefund($paymentId, $amount , $comment, $supplierCode)
  {

      $refundEndpoint = $this->apiEndpoint . "/MakeSupplierRefund";


      $refundParams = [
          "Key"=> $paymentId,
          "KeyType"=> "PaymentId",
          "Comment" => $comment,
          "Suppliers" => [
            [
              "SupplierCode" => $supplierCode,
              "SupplierDeductedAmount" => $amount
            ]
          ]
        ];

      $client = new Client();
      $response = $client->createRequest()
              ->setMethod('POST')
              ->setUrl($refundEndpoint)
              ->setData($refundParams)
              ->addHeaders([
                  'authorization' => 'Bearer ' . $this->apiKey,
                  'content-type' => 'application/json',
              ])
              ->send();

      return $response;
  }

    /**
     * get the supplier dashboard
     * @param  string $chargeId
     */
    public function getSupplierDashboard($supplierCode)
    {
        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($this->apiEndpoint . "/GetSupplierDashboard?SupplierCode=" . $supplierCode)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->apiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }

    /**
     * Check charge object for status updates
     * @param  string $chargeId
     */
    public function retrieveCharge($paymentId, $keyType)
    {

        $chargeParams = [
          "Key" => $paymentId,
          "KeyType" => $keyType
        ];

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($this->apiEndpoint . "/GetPaymentStatus")
                ->setData($chargeParams)
                ->addHeaders([
                    'authorization' => 'Bearer ' . $this->apiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }


}
