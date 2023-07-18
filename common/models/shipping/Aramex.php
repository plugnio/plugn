<?php

namespace common\models\shipping;

use common\models\Setting;
use Yii;
use common\models\Currency;
use common\models\shipping\util\ShippingHelper;

class Aramex
{
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

            $is_dutiable = $accountCountryCode == $county_code_to ||
                ShippingHelper::is_eu_country($accountCountryCode, $county_code_to) ? "N" : "Y";

            $get_product = array();

            foreach($products as $sing_product)
            {
                if(isset($sing_product['shipping']) && $sing_product['shipping'] == 1)
                {
                    $get_product[] = $sing_product;
                }
            }

            $aramex_packs		=	ShippingHelper::packages($get_product, $selected_currency);

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
                                'AccountCountryCode'	=> $accountCountryCode,
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
}