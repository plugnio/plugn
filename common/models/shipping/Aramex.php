<?php

namespace common\models\shipping;

use common\models\Customer;
use common\models\Order;
use common\models\PaymentMethod;
use common\models\Setting;
use Yii;
use common\models\Currency;
use common\models\shipping\util\ShippingHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * todo: new params
 * aramex_allowed_domestic_methods
aramex_allowed_domestic_additional_services
aramex_allowed_international_methods
aramex_allowed_international_additional_services

aramex_report_id

 *
 */
class Aramex
{
    public static function createDelivery ($model)
    {
        ##################### config shipper details ################

        $restaurant_uuid = $model->restaurant_uuid;

        $sandbox = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_sandbox');

        $shipper_city = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_city');
        $shipper_country = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_country_code');
        $shipper_state = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_state');
        $shipper_postal = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_post_code');

        $report_id = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_report_id');

        if (!$report_id)
            $report_id = 9201;//todo: '9729'; 9201

        $shipper_street = '';

        $shipper_name = $model->restaurant->name;
        $shipper_email = $model->restaurant->restaurant_email ? $model->restaurant->restaurant_email: $model->restaurant->owner_email;
        $shipper_company = $model->restaurant->name;
        $shipper_phone = $model->restaurant->phone_number ? $model->restaurant->phone_number : $model->restaurant->owner_number;//'+'. $model->restaurant->phone_number_country_code . ' ' .

        //todo: configurable from order form page

        $domestic_methods = "CDA";// "LGS";// ArrayHelper::getColumn(self::domesticmethods(), 'value')[0];
        $domestic_additional_services = null;//'CHST';//  ArrayHelper::getColumn(self::domesticadditionalservices(), 'value')[0];
        $international_methods = "DPX";// ArrayHelper::getColumn(self::internationalmethods(), 'value')[0];
        $international_additional_services = null;// ArrayHelper::getColumn(self::internationaladditionalservices(), 'value')[0];

        $weight_unit = 'KG';//todo: configurable

        ##################### customer shipment details ################

        $shipment_receiver_name = $model->customer_name;

        $shipment_receiver_street = '';

        if(strtolower($model->unit_type) == Order::UNIT_TYPE_HOUSE) {
            $shipment_receiver_street .= "House number: " . $model->house_number;
        }

        if($model->apartment)
            $shipment_receiver_street .= "Apartment: " . $model->apartment;

        if($model->floor)
            $shipment_receiver_street .= "Floor: " . $model->floor;

        if($model->block)
            $shipment_receiver_street .= "Block: " . $model->block;

        if($model->street)
            $shipment_receiver_street .= "Street: " . $model->street;

        if($model->avenue)
            $shipment_receiver_street .= "Avenue: " . $model->avenue;

        if($model->building)
            $shipment_receiver_street .= "Building: " . $model->building;

        if($model->office)
            $shipment_receiver_street .= "Office: " . $model->office;

        $receiver_name = $shipment_receiver_name;
        $receiver_email = $model->customer_email;
        $receiver_company = '';
        $receiver_street = $shipment_receiver_street;
        $receiver_country = $model->country?$model->country->iso: "KW";
        $receiver_city =  $model->city ;
        $receiver_postal = $model->postalcode;
        $receiver_state = '';//todo: state
        $receiver_phone =  '+' .$model->customer_phone_country_code . $model->customer_phone_number;//
//6666666 todo: phone number not working

        #################

        $reference = $model->order_uuid;

        $shipper_account = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_account_number');

        $aramex_shipment_info_billing_account = 1;

        if (strtolower($receiver_country) == strtolower($shipper_country)) {
            $product_group = 'DOM';
            $product_type = $domestic_methods;
            $additional_service_type = $domestic_additional_services;
        } else {
            $product_group = 'EXP';
            $product_type = $international_methods;
            $additional_service_type = $international_additional_services;
        }

        /// COD
        $services = array();
        $additional_service_type_array = array();

        if($additional_service_type)
            $additional_service_type_array[] = $additional_service_type;

        if($product_type == "CDA"){
            if( $additional_service_type == null ){
                array_push($services, "CODS");
            }elseif ( !in_array("CODS", $additional_service_type_array)){
                $services = array_merge($services, $additional_service_type_array);
                array_push($services, "CODS");
            }else{
                $services = array_merge($services, $additional_service_type_array);
            }
        }else{
            if($additional_service_type == null){
                $additional_service_type = array();
            }
            $services = array_merge($services, $additional_service_type_array);
        }

        $services = implode(',', $services);
/// COD
        $payment_type = 'P';
        $cod_amount = 0;
        $payment_option = 'ACCT';//ASCC/ARCC/CASH/ACCT/PPST/CRDT

        if ($model->paymentMethod->payment_method_code == PaymentMethod::CODE_CASH) {
            $payment_option = 'CASH';
            $payment_type = 'C';//collect
        }
        $cod_amount = number_format($model->total_price * $model->currency_rate, 2);
        $currency_code = $model->currency_code;

        $custom_amount = $model->total_price * $model->currency_rate;

        $info_comment = '';
        $foreignhawb = '';

        ########### product list ##########

        $weighttot = 0;
        $totalWeight = 0;
        $totalItems = 0;
        $product_arr = [];

        $orderItems = $model->getOrderItems()
            ->filterShipping()
            ->all();

        foreach ($orderItems as $orderItem) {

            $product_arr[] = $orderItem['item_name'];
            $weight = number_format($orderItem['weight'], 2);

            $aramex_items[] = array(
                'PackageType' => 'Box',
                'Quantity' => $orderItem['qty'],
                'Weight' => array(
                    'Value' => $weight,
                    'Unit' => $weight_unit
                ),
                'Comments' => $orderItem['item_name'] , //'',$orderItem['customer_instruction']
                'Reference' => ''
            );
            $totalWeight += ($weight * $orderItem['qty']);
            $totalItems += $orderItem['qty'];
        }

        //echo $totalWeight;
        $total = number_format($model->total_price * $model->currency_rate, 2);

        if (count($product_arr)) {
            $aramex_shipment_description = implode(", ", $product_arr);
        }

        ################## create shipment ###########

        $baseUrl = self::getWsdlPath($sandbox);

        //SOAP object
        $soapClient = new \SoapClient($baseUrl . '/shipping.wsdl', array(
            'exceptions'=>true,
            'cache_wsdl'=>WSDL_CACHE_NONE,
            'encoding'=>'utf-8',
            "trace" => 1
        ));

        $aramex_errors = false;

        $flag = true;
        $error = "";

        try {

            $aramex_atachments = array();

            $params = array();

            //shipper parameters
            $params['Shipper'] = array(
                'Reference1' => $reference, //'ref11111',
                'Reference2' => '',
                'AccountNumber' => $shipper_account, //'43871',
                //Party Address
                'PartyAddress' => array(
                    'Line1' => $shipper_street, //'13 Mecca St',
                    'Line2' => '',
                    'Line3' => '',
                    'City' => $shipper_city, //'Dubai',
                    'StateOrProvinceCode' => $shipper_state, //'',
                    'PostCode' => $shipper_postal,
                    'CountryCode' => $shipper_country, //'AE'
                ),
                //Contact Info
                'Contact' => array(
                    'Department' => '',
                    'PersonName' => $shipper_name, //'Suheir',
                    'Title' => '',
                    'CompanyName' => $shipper_company, //'Aramex',
                    'PhoneNumber1' => $shipper_phone, //'55555555',
                    'PhoneNumber1Ext' => '',
                    'PhoneNumber2' => '',
                    'PhoneNumber2Ext' => '',
                    'FaxNumber' => '',
                    'CellPhone' => $shipper_phone,
                    'EmailAddress' => $shipper_email, //'',
                    'Type' => ''
                ),
            );

            //consinee parameters
            $params['Consignee'] = array(
                'Reference1' => $reference, //'',
                'Reference2' => '',
                'AccountNumber' => ($aramex_shipment_info_billing_account == 2) ? $aramex_shipment_info_billing_account : '',
                //Party Address
                'PartyAddress' => array(
                    'Line1' => $receiver_street, //'15 ABC St',
                    'Line2' => $model->address_1 . ' ' . $model->address_2 ,
                    'Line3' => $model->special_directions,
                    'City' => $receiver_city, //'Amman',
                    'StateOrProvinceCode' => '',
                    'PostCode' => $receiver_postal,
                    'CountryCode' => $receiver_country, //'JO'
                ),
                //Contact Info
                'Contact' => array(
                    'Department' => '',
                    'PersonName' => $receiver_name, //'Mazen',
                    'Title' => '',
                    'CompanyName' => $receiver_email, //'Aramex',
                    'PhoneNumber1' => $model->customer_phone_number,//'6666666',
                    'PhoneNumber1Ext' => '',//+962 '+'.$model->customer_phone_country_code,
                    'PhoneNumber2' => '',
                    'PhoneNumber2Ext' => '',
                    'FaxNumber' => '',
                    'CellPhone' => $receiver_phone,
                    'EmailAddress' => $receiver_email, //'mazen@aramex.com',
                    'Type' => ''
                )
            );

            //new

            if ($aramex_shipment_info_billing_account == 3) {
                $params['ThirdParty'] = array(
                    'Reference1' => $reference, //'ref11111',
                    'Reference2' => '',
                    'AccountNumber' => $shipper_account, //'43871',
                    //Party Address
                    'PartyAddress' => array(
                        'Line1' => $shipper_street, //'13 Mecca St',
                        'Line2' => '',
                        'Line3' => '',
                        'City' => $shipper_city, //'Dubai',
                        'StateOrProvinceCode' => $shipper_state, //'',
                        'PostCode' => $shipper_postal,
                        'CountryCode' => $shipper_country, //'AE'
                    ),
                    //Contact Info
                    'Contact' => array(
                        'Department' => '',
                        'PersonName' => $shipper_name, //'Suheir',
                        'Title' => '',
                        'CompanyName' => $shipper_company, //'Aramex',
                        'PhoneNumber1' => $shipper_phone, //'55555555',
                        'PhoneNumber1Ext' => '',
                        'PhoneNumber2' => '',
                        'PhoneNumber2Ext' => '',
                        'FaxNumber' => '',
                        'CellPhone' => $shipper_phone,
                        'EmailAddress' => $shipper_email, //'',
                        'Type' => ''
                    ),
                );
            }

            // Other Main Shipment Parameters
            $params['Reference1'] = $reference; //'Shpt0001';
            $params['Reference2'] = '';
            $params['Reference3'] = '';
            $params['ForeignHAWB'] = $foreignhawb;

            $params['TransportType'] = 0;
            $params['ShippingDateTime'] = time(); //date('m/d/Y g:i:sA');
            $params['DueDate'] = time() + (7 * 24 * 60 * 60); //date('m/d/Y g:i:sA');
            $params['PickupLocation'] = 'Reception';
            $params['PickupGUID'] = '';
            $params['Comments'] = $info_comment;
            $params['AccountingInstrcutions'] = '';
            $params['OperationsInstructions'] = '';
            $params['Details'] = array(
                'Dimensions' => array(
                    'Length' => '0',
                    'Width' => '0',
                    'Height' => '0',
                    'Unit' => 'cm'
                ),
                'ActualWeight' => array(
                    'Value' => $totalWeight,
                    'Unit' => $weight_unit
                ),
                'ProductGroup' => $product_group, //'EXP',
                'ProductType' => $product_type, //,'PDX'
                'PaymentType' => $payment_type,
                'PaymentOptions' => $payment_option, //$post['aramex_shipment_info_payment_option']
                'Services' => $services,
                'NumberOfPieces' => $totalItems,
                'DescriptionOfGoods' => $aramex_shipment_description,
                'GoodsOriginCountry' => $shipper_country, //'JO',
                'Items' => $aramex_items,
            );

            /*$aramex_atachments[] = array(
                "FileName" => "invoice-" . $model->order_uuid,
                "FileExtension" => "pdf",
                "FileContents" => base64_encode("test")
            );

            //if (count($aramex_atachments)) {
                $params['Attachments'] = $aramex_atachments;
            //}
            */

            if($cod_amount > 0) {
                $params['Details']['CashOnDeliveryAmount'] = array(
                    'Value' => $cod_amount,
                    'CurrencyCode' => $currency_code
                );
            }

            $params['Details']['CustomsValueAmount'] = array(
                'Value' => $custom_amount,
                'CurrencyCode' => $currency_code
            );

            $major_par['Shipments'][] = $params;
            $clientInfo = self::getClientInfo($model->restaurant_uuid);
            $major_par['ClientInfo'] = $clientInfo;

            $major_par['LabelInfo'] = array(
                'ReportID' => $report_id, //'9201',
                'ReportType' => 'RPT'//URL
            );

            try {
                //create shipment call

                $auth_call = $soapClient->CreateShipments($major_par);

                //$auth_call = $soapClient->__soapCall('CreateShipments', $major_par);

                if ($auth_call->HasErrors) {

                    if (empty($auth_call->Shipments)) {
                        if (count($auth_call->Notifications->Notification) > 1) {
                            $message = '';

                            foreach ($auth_call->Notifications->Notification as $notify_error) {
                                $message .=  'Aramex: ' . $notify_error->Code . ' - ' .$notify_error->Message .' ';
                            }

                            return self::createShipmentFail($model->order_uuid, $message);
                        } else {
                            $message = 'Aramex: ' . $auth_call->Notifications->Notification->Code . ' - ' . $auth_call->Notifications->Notification->Message;
                            return self::createShipmentFail($model->order_uuid, $message);
                        }
                    } else {

                        if (is_array($auth_call->Shipments->ProcessedShipment->Notifications->Notification) &&
                            isset($auth_call->Shipments->ProcessedShipment->Notifications->Notification[0])
                        ) {
                            $notification_string = '';
                            foreach ($auth_call->Shipments->ProcessedShipment->Notifications->Notification as $notification_error) {
                                $notification_string .= $notification_error->Code . ' - ' . $notification_error->Message . ' <br />';
                            }
                            $message = $notification_string;
                            return self::createShipmentFail($model->order_uuid, $message);
                        } else {
                            $message = 'Aramex: ' . $auth_call->Shipments->ProcessedShipment->Notifications->Notification->Code . ' - ' . $auth_call->Shipments->ProcessedShipment->Notifications->Notification->Message;
                            return self::createShipmentFail($model->order_uuid, $message);
                        }
                    }

                } else {

                    $message = "AWB No. " . $auth_call->Shipments->ProcessedShipment->ID .
                        " - Order No. " . $auth_call->Shipments->ProcessedShipment->Reference1;

                    //$model->aramex_shipment_id = $auth_call->Shipments->ProcessedShipment->ID;
                    //$model->save(false);

                    Order::updateAll([
                        'aramex_shipment_id' => $auth_call->Shipments->ProcessedShipment->ID
                    ], [
                        'order_uuid' => $model->order_uuid
                    ]);

                    return [
                        "operation" => "success",
                        "message" => $message
                    ];

                    /*

                    $is_email = 1;
                    $message = array(
                        'notify' => $is_email,
                        'comment' => $shipmenthistory
                    );
                    $this->addOrderHistory($model->order_uuid, $message);
                    //change status
                    $order_status = 2; //Processing
                   */
                }
            } catch (Exception $e) {
                $aramex_errors = true;
                $message = $e->getMessage();
                return self::createShipmentFail($model->order_uuid, $message);
            } catch (\yii\base\ErrorException $e) {
                $message = $e->getMessage();
                return self::createShipmentFail($model->order_uuid, $message);
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            return self::createShipmentFail($model->order_uuid, $message);
        } catch (\yii\base\ErrorException $e) {
            $message = $e->getMessage();
            return self::createShipmentFail($model->order_uuid, $message);
        }
    }

    /**
     * schedule store pickup by aramex
     * @param $model
     * @return array
     * @throws \SoapFault
     */
    public static function schedulePickup($model) {

        $store = $model->restaurant;

        $vehicle = Yii::$app->request->getBodyParam('pickup_vehicle');


        $shipper_name = Yii::$app->request->getBodyParam('shipper_name', $store->name);
        $shipper_company = Yii::$app->request->getBodyParam('shipper_company', $store->name);
        $shipper_phone = Yii::$app->request->getBodyParam('shipper_phone', $store->owner_number);
        $shipper_phone_prefix = Yii::$app->request->getBodyParam('shipper_phone_prefix', '+' . $store->owner_phone_country_code);
        $shipper_email = Yii::$app->request->getBodyParam('shipper_email', $store->owner_email);
        $shipper_address = Yii::$app->request->getBodyParam('shipper_address');
        $shipper_location  = Yii::$app->request->getBodyParam('shipper_location');

        $product_group  = Yii::$app->request->getBodyParam('product_group');
        $product_type  = Yii::$app->request->getBodyParam('product_type');
        $payment_type = Yii::$app->request->getBodyParam('payment_type');
        $no_shipments  = Yii::$app->request->getBodyParam('no_shipments');
        $total_counts  = Yii::$app->request->getBodyParam('total_count');

        $status = Yii::$app->request->getBodyParam('status');
        $ready_time = Yii::$app->request->getBodyParam('ready_time');
        $closing_time = Yii::$app->request->getBodyParam('closing_time');

        $date = Yii::$app->request->getBodyParam('date', date('Y-m-d'));

        $comment = Yii::$app->request->getBodyParam('comment', date('Y-m-d'));

        $sandbox = Setting::getConfig($store->restaurant_uuid, "Aramex", 'shipping_aramex_sandbox');
        $shipper_city = Setting::getConfig($store->restaurant_uuid, "Aramex", 'shipping_aramex_city');
        $shipper_country = Setting::getConfig($store->restaurant_uuid, "Aramex", 'shipping_aramex_country_code');
        $shipper_state = Setting::getConfig($store->restaurant_uuid, "Aramex", 'shipping_aramex_state');
        $shipper_postal = Setting::getConfig($store->restaurant_uuid, "Aramex", 'shipping_aramex_post_code');

                ##################### customer shipment details ################

                $shipment_receiver_name ='';
                $shipment_receiver_street ='';

                $destination_country = $model->country?$model->country->iso: "KW";
                $destination_city    = $model->city ;
                $destination_zipcode  = $model->postalcode;
                //$destination_state   = ($order_info['shipping_zone'])?$order_info['shipping_zone']:'';

                ################## create shipment ###########

        $orderItems = $model->getOrderItems()
            ->filterShipping()
            ->all();

        $totalWeight = $totalItems = 0;

        foreach ($orderItems as $orderItem) {

            $weight = number_format($orderItem['weight'], 2);
            $totalWeight += ($weight * $orderItem['qty']);
            $totalItems += $orderItem['qty'];
        }

                    $clientInfo = self::getClientInfo($model->restaurant_uuid);

                    try {

                        $pickupDate = strtotime($date);

                        $readyTime = strtotime($ready_time);

                        $closingTime = strtotime($closing_time);

                        $weight_unit ='KG' ;

                        $params = array(
                            'ClientInfo'  	=> $clientInfo,

                            'Transaction' 	=> array(
                                'Reference1'			=> $model->order_uuid
                            ),

                            'Pickup'		=>array(
                                'PickupContact'				=>array(
                                    'PersonName'			=> $shipper_name,
                                    'CompanyName'			=> $shipper_company,
                                    'PhoneNumber1'			=> $shipper_phone,
                                    'PhoneNumber1Ext'		=> $shipper_phone_prefix,
                                    'CellPhone'				=> $shipper_phone,
                                    'EmailAddress'			=> $shipper_email
                                ),
                                'PickupAddress'				=>array(
                                    'Line1'					=> $shipper_address,
                                    'City'					=> $shipper_city,
                                    'StateOrProvinceCode'	=> $shipper_state,
                                    'PostCode'				=> $shipper_postal,
                                    'CountryCode'			=> $shipper_country
                                ),

                                'PickupLocation'		=> $shipper_location,
                                'PickupDate'			=> $pickupDate,
                                'ReadyTime'				=> $readyTime,
                                'LastPickupTime'		=> $closingTime,
                                'ClosingTime'			=> $closingTime,
                                'Comments'				=> $comment,
                                'Reference1'			=> $model->order_uuid,
                                'Reference2'			=>'',
                                'Vehicle'				=> $vehicle,
                                'Shipments'				=>array(
                                    'Shipment'					=>array()
                                ),
                                'PickupItems'			=>array(
                                    'PickupItemDetail'=>array(
                                        'ProductGroup'	=> $product_group,
                                        'ProductType'	=> $product_type,
                                        'Payment'		=> $payment_type,
                                        'NumberOfShipments'=> $no_shipments,
                                        'NumberOfPieces'=> $total_counts,
                                        'ShipmentWeight' => array('Value'=> $totalWeight, 'Unit'=> $weight_unit),
                                    ),
                                ),
                                'Status' => $status
                            )
                        );

                        $baseUrl = self::getWsdlPath($sandbox);
                        $soapClient = new \SoapClient($baseUrl.'/shipping.wsdl', array('trace' => 1));

                        try{
                            $results = $soapClient->CreatePickup($params);

                            if($results->HasErrors) {

                                $errors = [];

                                if(is_array($results->Notifications->Notification)){
                                    foreach($results->Notifications->Notification as $notify_error){
                                        $errors[] = $notify_error->Code .' - '. $notify_error->Message;
                                    }
                                }else{
                                    $errors[] = $results->Notifications->Notification->Code . ' - '. $results->Notifications->Notification->Message;
                                }

                                return [
                                    "operation" => "error",
                                    "message" => $errors
                                ];

                            } else {

                                /*$comment = "Pickup reference number ( <strong>".$results->ProcessedPickup->ID."</strong> ).";

                                $message = array(
                                    'notify' => 0,
                                    'comment' => $comment
                                );*/

                                Order::updateAll([
                                    'aramex_pickup_id' => $results->ProcessedPickup->ID
                                ], [
                                    'order_uuid' => $model->order_uuid
                                ]);

                                return [
                                    "operation" => "success",
                                    'ID' => $results->ProcessedPickup->ID,
                                    "message" => "Pickup reference number #" . $results->ProcessedPickup->ID
                                ];
                            }

                        } catch (Exception $e) {

                            return [
                                "operation" => "error",
                                "message" =>$e->getMessage()
                            ];
                        }
                    }
                    catch (Exception $e) {
                        return [
                            "operation" => "error",
                            "message" =>$e->getMessage()
                        ];
                    }

    }

    public static function createShipmentFail($order_uuid, $message) {

        Yii::error("Order shipment failed #". $order_uuid. " with message: ". $message);

        return [
            "operation" => "error",
            "message" => $message
        ];
    }

    public static function getWsdlPath($sandbox) {

        //$wsdlBasePath = Yii::getAlias("@common/models/shipping/util/aramex/wsdl");

        //$wsdlBasePath = Url::to()

        $wsdlBasePath = "http://localhost:8888/plugn/agent/web/wsdl";

        if ($sandbox) {
            $wsdlBasePath .='/TestMode';
        }

        return $wsdlBasePath;
    }

    public static function getQuote($restaurant_uuid, $address) {

        $error = '';

        $quote_data = array();

        /*--------------- params start -----------------------*/

        $sandbox = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_sandbox');

        $accountNumber = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_account_number');
        $accountEntity = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_account_entity');
        $accountPin = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_account_pin');
        $username = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_username');
        $password = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_password');

        $fromCity = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_city');
        $fromCountryCode = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_country_code');
        $fromState = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_state');
        $fromPostcode = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_post_code');
         
        $products = [
            [
               "weight" => 1000,
                "shipping" => 1,
                "quantity" => 1,
                "price" => 9000,
                "width" => 1,
                "height" => 1,
                "length" => 1
            ]
        ];//$this->cart->getProducts();

        $paymentType = "P";//P/C/3,

        //account

        /**$accountCountryCode = "GB";
        $accountEntity = "LON";
        $accountNumber = "102331";
        $accountPin = "321321";
        $username = "testingapi@aramex.com";//
        $password = 'R123456789$r';

        $accountCountryCode = "JO";
        $accountEntity = "AMM";
        $accountNumber = "NNNNN";
        $accountPin = "NNNNNN";
        $username = "AAAA@AAA.com";
        $password = 'XXXXXXXXX';
        
        //source
        $fromCity = "Bristol";
        $fromCountryCode= "GB";
        $fromState ="";
        $fromPostcode = "BS7 8BA";*/

        //destination
        $county_code_to = "IN";//$address['country'][''];
        $to_city = "Vadodara";
        $zoneCode = "GJ";
        $postcode = "391410";

        $aramex_country = $county_code_to;//$this->config->get('shipping_hitaramex_country_code');

        $total_config_values = ShippingHelper::get_currency();
        $selected_currency = $total_config_values[$aramex_country]['currency'];

        $currency = Currency::findOne(['code' => $selected_currency]);

        //todo: rate same as order table
        $currency_rate = $currency->rate;

        /*--------------- params end -----------------------*/

            $is_dutiable = $fromCountryCode == $county_code_to ||
                ShippingHelper::is_eu_country($fromCountryCode, $county_code_to) ? "N" : "Y";

            $get_product = array();

            foreach($products as $sing_product)
            {
                if(isset($sing_product['shipping']) && $sing_product['shipping'] == 1)
                {
                    $get_product[] = $sing_product;
                }
            }

            $aramex_packs =	ShippingHelper::packages($get_product, $selected_currency);

            $total_value= 0;

            foreach($aramex_packs as $pack)
            {
                $total_value += $pack['InsuredValue']['Amount'];
            }

            if ($sandbox) {
                $url = 'https://ws.dev.aramex.net/ShippingAPI.V2/RateCalculator/Service_1_0.svc?singleWsdl';
            } else {
                $url = 'https://ws.aramex.net/ShippingAPI.V2/RateCalculator/Service_1_0.svc?singleWsdl';
            }

            $dutiable_content = ($is_dutiable == "Y") ? "EXP" : "DOM";

            /*if ( $this->config->get('shipping_hitshippo_aramex_translation') == true && !empty($this->config->get('shipping_hitshippo_aramex_translation_key')) ) {
                if (!empty($to_city)) {
                    if (!preg_match('%^[ -~]+$%', $to_city))			//Cheks english or not	/[^A-Za-z0-9]+/
                    {
                        $response =array();
                        try{
                            $translate = new TranslateClient(['key' => $this->config->get('shipping_hitshippo_aramex_translation_key')]);
                            // Tranlate text
                            $response = $translate->translate($to_city, [
                                'target' => 'en',
                            ]);
                        }catch(exception $e){
                            // echo "\n Exception Caught" . $e->getMessage(); //Error handling
                        }
                        if (!empty($response) && isset($response['text']) && !empty($response['text'])) {
                            $to_city = $response['text'];
                        }
                    }
                }
            }*/

            $destination_postcode_city = ShippingHelper::get_postcode_city($county_code_to, $to_city, $postcode);

            $ref = srand(time());

            $all_carrier = $selected_services_aaray = [
                //"PDX" => "Priority Document Express",
                "PPX" => "Priority Parcel Express",
                //"PLX" => "Priority Letter Express",
                //"DDX" => "Deferred Document Express",
                "DPX" => "Deferred Parcel Express",
                //"GDX" => "Ground Document Express",
                "GPX" => "Ground Parcel Express",
            ];

            // Whoever introduced xml to shipping companies should be flogged
            $total_res = array();

            if (isset($all_carrier) && !empty($all_carrier)) {

                foreach ($all_carrier as $carrier => $carriername) {

                    foreach ($aramex_packs as $pck) {

                        $params = array(
                            'ClientInfo'  			=> array(
                                'AccountCountryCode'	=> $fromCountryCode,
                                'AccountEntity'		 	=> $accountEntity,
                                'AccountNumber'		 	=> $accountNumber,
                                'AccountPin'		 	=> $accountPin,
                                'UserName'			 	=> $username,
                                'Password'			 	=> $password,
                                'Version'			 	=> 'v1.0'
                            ),

                            'Transaction' 			=> array(
                                'Reference1'			=> $ref
                            ),

                            'OriginAddress' => array(
                                'City'					=> $fromCity,
                                'CountryCode'			=> $fromCountryCode,
                                'State'					=> $fromState,
                                'PostCode'				=> $fromPostcode,
                            ),

                            'DestinationAddress' 	=> array(
                                'City'					=> $to_city,
                                'CountryCode'			=> $county_code_to,
                                'State'					=> $zoneCode,
                                'PostCode'				=> $postcode,
                            ),

                            'ShipmentDetails'		=> array(
                                'PaymentType'			 => $paymentType,//P/C/3,
                                'ProductGroup'			 => $dutiable_content,
                                'ProductType'			 => $carriername, //$current_carrier,
                                'ActualWeight' 			 => array('Value' => $pck['Weight']['Value'], 'Unit' => $pck['Weight']['Units']),
                                'ChargeableWeight' 	     => array('Value' => $pck['Weight']['Value'], 'Unit' => $pck['Weight']['Units']),
                                'NumberOfPieces'		 => count($pck['packed_products'])
                            )
                        );

                        $soapClient = new \SoapClient($url, array('trace' => 1));
                        $results = $soapClient->CalculateRate($params);
                        // return $results;
                        $results->carrier = $carriername;
                        $request[] = $params;
                        $total_res[] = $results;
                    }
                }
            }

            if (isset($total_res) && !empty($total_res)) {
                $filter_arr = array();
                foreach ($all_carrier as $carrer => $carr_name) {
                    foreach ($total_res as $res_key => $single_res) {

                        if ($carr_name == $single_res->carrier) {
                            $filter_arr[$carr_name]['currency'] = $single_res->TotalAmount->CurrencyCode;
                            if (isset($filter_arr[$carr_name]['value']) &&  $filter_arr[$carr_name]['value'] > 0) {
                                $filter_arr[$carr_name]['value'] += $single_res->TotalAmount->Value;
                            } else {
                                $filter_arr[$carr_name]['value'] = $single_res->TotalAmount->Value;
                            }
                            $filter_arr[$carr_name]['carrier'] = $single_res->carrier;
                        }
                    }
                }
            }

            if ($filter_arr && !empty($filter_arr)) {

                foreach ($filter_arr as $quote) {

                    $rate_code = ((string) $quote['carrier']);
                    $rate_title = ((string) $quote['carrier']);

                    $rate_cost = (float)((string) $quote['value']);

                    if(in_array($rate_code, $selected_services_aaray))
                    {
                        $quote_data[$rate_code] = array(
                            'code'         => 'aramex.' . $rate_code,
                            'title'        => 'aramex '.$rate_title,
                            'cost'         => $rate_cost,
                            'tax_class_id' => '',
                            'text'         => Yii::$app->formatter->asCurrency($rate_cost * $currency_rate, $currency->code, [
                                \NumberFormatter::MAX_FRACTION_DIGITS => $currency->decimal_place
                            ]),
                            'service_code' => $rate_code,
                            'mod_name'		=> 'aramex'
                        );
                    }
                }
            }

        $method_data = array();

        if ($quote_data || $error) {

            $method_data = array(
                'code'       => 'aramex',
                'title'      => "Aramex",
                'quote'      => $quote_data,
                //'sort_order' => $this->config->get('shipping_hitshippo_aramex_sort_order'),
                'error'      => $error
            );
        }
        echo "<pre>";
print_r($total_res);
        die();
        return $method_data;
    }

    public static function getClientInfo($restaurant_uuid)
    {
        $account = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_account_number');
        $entity = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_account_entity');
        $pin = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_account_pin');
        $username = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_username');
        $password = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_password');
        $country_code = Setting::getConfig($restaurant_uuid, "Aramex", 'shipping_aramex_country_code');

        return array(
            'AccountCountryCode' => $country_code,
            'AccountEntity' => $entity,
            'AccountNumber' => $account,
            'AccountPin' => $pin,
            'UserName' => $username,
            'Password' => $password,
            'Version' => 'v1.0',
            'Source' => 53
        );
    }

    public static function domesticmethods() {

        $arr[] = array('value'=>'BLK', 'label'=>'Special: Bulk Mail Delivery');
        $arr[] = array('value'=>'BLT', 'label'=>'Domestic - Bullet Delivery');
        $arr[] = array('value'=>'CDA', 'label'=>'Special Delivery');
        $arr[] = array('value'=>'CDS', 'label'=>'Special: Credit Cards Delivery');
        $arr[] = array('value'=>'CGO', 'label'=>'Air Cargo (India)');

        $arr[] = array('value'=>'COM', 'label'=>'Special: Cheque Collection');
        $arr[] = array('value'=>'DEC', 'label'=>'Special: Invoice Delivery');
        $arr[] = array('value'=>'EMD', 'label'=>'Early Morning delivery');
        $arr[] = array('value'=>'FIX', 'label'=>'Special: Bank Branches Run');
        $arr[] = array('value'=>'LGS', 'label'=>'Logistic Shipment');

        $arr[] = array('value'=>'OND', 'label'=>'Overnight (Document)');
        $arr[] = array('value'=>'ONP', 'label'=>'Overnight (Parcel)');
        $arr[] = array('value'=>'P24', 'label'=>'Road Freight 24 hours service');
        $arr[] = array('value'=>'P48', 'label'=>'Road Freight 48 hours service');
        $arr[] = array('value'=>'PEC', 'label'=>'Economy Delivery');

        $arr[] = array('value'=>'PEX', 'label'=>'Road Express');
        $arr[] = array('value'=>'SFC', 'label'=>'Surface  Cargo (India)');
        $arr[] = array('value'=>'SMD', 'label'=>'Same Day (Document)');
        $arr[] = array('value'=>'SMP', 'label'=>'Same Day (Parcel)');
        $arr[] = array('value'=>'SDD', 'label'=>'Same Day Delivery');
        $arr[] = array('value'=>'HVY', 'label'=>'Heavy (20kgs and more)');
        $arr[] = array('value'=>'SPD', 'label'=>'Special: Legal Branches Mail Service');
        $arr[] = array('value'=>'SPL', 'label'=>'Special : Legal Notifications Delivery');

        return $arr;
    }

    public static function domesticAdditionalServices()
    {

        $arr[] = array('value'=>'AM10', 'label'=>'Morning delivery');
        $arr[] = array('value'=>'CHST', 'label'=>'Chain Stores Delivery');
        $arr[] = array('value'=>'CODS', 'label'=>'Cash On Delivery Service');
        $arr[] = array('value'=>'COMM', 'label'=>'Commercial');
        $arr[] = array('value'=>'CRDT', 'label'=>'Credit Card');

        $arr[] = array('value'=>'DDP', 'label'=>'DDP - Delivery Duty Paid - For European Use');
        $arr[] = array('value'=>'DDU', 'label'=>'DDU - Delivery Duty Unpaid - For the European Freight');
        $arr[] = array('value'=>'EXW', 'label'=>'Not An Aramex Customer - For European Freight');
        $arr[] = array('value'=>'INSR', 'label'=>'Insurance');
        $arr[] = array('value'=>'RTRN', 'label'=>'Return');

        $arr[] = array('value'=>'SPCL', 'label'=>'Special Services');

        return $arr;
    }

    public static function internationalmethods()
    {
        $arr[] = array('value'=>'DPX', 'label'=>'Value Express Parcels');
        $arr[] = array('value'=>'EDX', 'label'=>'Economy Document Express');
        $arr[] = array('value'=>'EPX', 'label'=>'Economy Parcel Express');
        $arr[] = array('value'=>'GDX', 'label'=>'Ground Document Express');
        $arr[] = array('value'=>'GPX', 'label'=>'Ground Parcel Express');

        $arr[] = array('value'=>'IBD', 'label'=>'International defered');
        $arr[] = array('value'=>'PDX', 'label'=>'Priority Document Express');
        $arr[] = array('value'=>'PLX', 'label'=>'Priority Letter Express (<.5 kg Docs)');
        $arr[] = array('value'=>'PPX', 'label'=>'Priority Parcel Express');

        return $arr;
    }

    public static function internationalAdditionalServices()
    {
        $arr[] = array('value'=>'AM10', 'label'=>'Morning delivery');
        $arr[] = array('value'=>'CODS', 'label'=>'Cash On Delivery');
        $arr[] = array('value'=>'CSTM', 'label'=>'CSTM');
        $arr[] = array('value'=>'EUCO', 'label'=>'NULL');
        $arr[] = array('value'=>'FDAC', 'label'=>'FDAC');

        /*$arr[] = array('value'=>'FRD1', 'label'=>'Free Domicile');*/
        $arr[] = array('value'=>'FRDM', 'label'=>'FRDM');
        $arr[] = array('value'=>'INSR', 'label'=>'Insurance');
        $arr[] = array('value'=>'NOON', 'label'=>'Noon Delivery');
        $arr[] = array('value'=>'ODDS', 'label'=>'Over Size');

        $arr[] = array('value'=>'RTRN', 'label'=>'RTRN');
        $arr[] = array('value'=>'SIGR', 'label'=>'Signature Required');
        $arr[] = array('value'=>'SPCL', 'label'=>'Special Services');

        return $arr;
    }
}