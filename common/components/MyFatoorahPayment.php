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
    const GATEWAY_KNET = 1;

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
    const GATEWAY_SADAD = 4;

    /**
     * @var string Generated link sends user directly to BENEFIT portal
     */
    const GATEWAY_BENEFIT = 5;


    /**
     * @var string Generated link sends user directly to MADA portal
     */
    const GATEWAY_MADA = 6;

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
            $this->apiKey = $this->liveApiEndpoint;
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
          "CommissionPercentage" => "5",
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
   * Create a charge for redirect
   */
  public function createCharge($currency, $amount ,$firstName, $email, $country_code ,$phone, $redirectUrl, $orderUuid, $supplierCode , $gateway)
  {

      $chargeEndpoint = $this->apiEndpoint . "/ExecutePayment";


      $phone =  str_replace(' ', '', $phone);
      $phone =  str_replace("+". $country_code, '', $phone);

      $chargeParams = [
            "PaymentMethodId" => $gateway,
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
                "ProposedShare" => null,
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
   * Make a Refund
   */
  public function makeRefund($paymentId, $amount , $comment, $supplierCode)
  {

      $refundEndpoint = $this->apiEndpoint . "/MakeRefund";


      $refundParams = [
          "Key"=> $paymentId,
          "KeyType"=> "PaymentId",
          "RefundChargeOnCustomer" => "false",
          "ServiceChargeOnCustomer" => "false",
          "Comment" => $comment,
          "Amount"=> $amount,
          "AmountDeductedFromSupplier"=> $amount
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
