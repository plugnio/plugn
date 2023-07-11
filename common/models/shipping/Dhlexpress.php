<?php

namespace common\models\shipping;

use common\models\Currency;
use common\models\shipping\util\ShippingHelper;

class Dhlexpress
{
    public function getQuote($address) {

        $error = '';

        $quote_data = array();

        /*--------------- params start -----------------------*/

        $sandbox = true;

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
        $dhlexpress_password = "BAAoK9nCB9rDNLPw";
        $dhlexpress_key = "";
        $dhlexpress_account = "";
        $rate_type = "";

        //source
        $fromCity = "Indore";
        $fromCountryCode= "IN";
        $fromState ="MP";
        $fromPostcode = "451010";

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

            $is_dutiable = ($fromCountryCode == $county_code_to ||
            ShippingHelper::is_eu_country($fromCountryCode, $county_code_to)) ? "N" : "Y";

            if($county_code_to == 'AT' && $fromCountryCode == 'CZ'){
                $is_dutiable = "N";
            }

            if($county_code_to == 'NL' && $fromCountryCode == 'SE'){
                $is_dutiable = "N";
            }

            $get_product = array();
            foreach($products as $sing_product)
            {
                if(isset($sing_product['shipping']) && $sing_product['shipping'] == 1)
                {
                    $get_product[] = $sing_product;
                }
            }

            $dhl_packs		= ShippingHelper::packages( $get_product,$selected_currency );
            $total_value= 0;
            foreach($dhl_packs as $pack)
            {
                $total_value += $pack['InsuredValue']['Amount'];
            }

            if (!$sandbox) {
                $url = 'https://xmlpi-ea.dhl.com/XMLShippingServlet';
            } else {
                $url = 'https://xmlpitest-ea.dhl.com/XMLShippingServlet';
            }

            $pieces = ShippingHelper::get_package_piece($dhl_packs);
            $weight_unit = 'KG';
            $dim_unit = 'CM';
            $fetch_accountrates = ($rate_type == 'ACCOUNT') ? "<PaymentAccountNumber>" . $dhlexpress_account . "</PaymentAccountNumber>" : "";

            $mailing_date = date('Y-m-d');
            $mailing_datetime = date('c');
            $origin_postcode_city = ShippingHelper::get_postcode_city($fromCountryCode, $fromCity, $fromPostcode);
            //$total_value = $this->cart->get_total();

            $dutiable_content = ($is_dutiable == "Y") ? "<Dutiable><DeclaredCurrency>{$selected_currency}</DeclaredCurrency><DeclaredValue>{$total_value}</DeclaredValue></Dutiable>" : "";

            //$insurance_details = ($this->config->get('shipping_dhlexpress_insurance') == true) ? "<InsuredValue>". $total_value ."</InsuredValue><InsuredCurrency>". $this->config->get('config_currency') ."</InsuredCurrency>" : "";
            //$additional_insurance_details = ($this->config->get('shipping_dhlexpress_insurance') == true)  ? "<QtdShp><QtdShpExChrg><SpecialServiceType>II</SpecialServiceType><LocalSpecialServiceType>XCH</LocalSpecialServiceType></QtdShpExChrg></QtdShp>" : ""; //

            /*if ( $this->config->get('shipping_dhlexpress_translation') == true && !empty($this->config->get('shipping_dhlexpress_translation_key')) ) {
                if (!empty($to_city)) {
                    if (!preg_match('%^[ -~]+$%', $to_city))			//Cheks english or not	/[^A-Za-z0-9]+/	
                    {
                        $response =array();
                        try{
                            $translate = new TranslateClient(['key' => $this->config->get('shipping_dhlexpress_translation_key')]);
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
            $payment_country = $county_code_to;// $this->config->get('shipping_dhlexpress_country_code');

            /*if ( !empty($this->config->get('shipping_dhlexpress_pay_con')) && $this->config->get('shipping_dhlexpress_pay_con') == "R" ) {
                $payment_country = $address['iso_code_2'];
            }elseif( !empty($this->config->get('shipping_dhlexpress_pay_con')) && $this->config->get('shipping_dhlexpress_pay_con') == "C" ) {
                if ( !empty($this->config->get('shipping_dhlexpress_cus_pay_con')) ) {
                    $payment_country = $this->config->get('shipping_dhlexpress_cus_pay_con');
                }
            }*/

            // Whoever introduced xml to shipping companies should be flogged
            $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<p:DCTRequest xmlns:p="http://www.dhl.com" xmlns:p1="http://www.dhl.com/datatypes" xmlns:p2="http://www.dhl.com/DCTRequestdatatypes" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.dhl.com DCT-req.xsd ">';
            $xml .= '	<GetQuote>';
            $xml .= '		<Request>';
            $xml .= '			<ServiceHeader>';
            $xml .= '				<MessageTime>'.$mailing_datetime.'</MessageTime>';
            $xml .= '					<MessageReference>1234567890123456789012345678901</MessageReference>';
            $xml .= '					<SiteID>'.$dhlexpress_key.'</SiteID>';
            $xml .= '					<Password>'.$dhlexpress_password.'</Password>';
            $xml .= '			</ServiceHeader>';
            $xml .= '		</Request>';
            $xml .= '		<From>';
            $xml .= '			<CountryCode>'.$fromCountryCode.'</CountryCode>';
            $xml .= '			'.$origin_postcode_city;
            $xml .= '		</From>';
            $xml .= '		<BkgDetails> ';
            $xml .= '			<PaymentCountryCode>'.$payment_country.'</PaymentCountryCode>';
            $xml .= '			<Date>'.$mailing_date.'</Date>';
            $xml .= '			<ReadyTime>PT10H21M</ReadyTime>';
            $xml .= '			<DimensionUnit>'.$dim_unit.'</DimensionUnit>';
            $xml .= '			<WeightUnit>'.$weight_unit.'</WeightUnit>';
            $xml .= '			<Pieces>';
            $xml .= '				'.$pieces;
            $xml .= '			</Pieces>';
            $xml .= '			'.$fetch_accountrates;
            $xml .= '			<IsDutiable>'.$is_dutiable.'</IsDutiable>';
            $xml .= '			<NetworkTypeCode>AL</NetworkTypeCode>';
            //$xml .= '			'.$additional_insurance_details;
            //$xml .= '			'.$insurance_details;
            $xml .= '		</BkgDetails>';
            $xml .= '		<To>';
            $xml .= '			<CountryCode>'.$county_code_to.'</CountryCode>';
            $xml .= '			'.$destination_postcode_city;
            $xml .= '		</To>';
            $xml .= '		'.$dutiable_content;
            $xml .= '	</GetQuote>';
            $xml .= '</p:DCTRequest>';

            $request = $xml;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt_array($curl, array(
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_HEADER         => false,
                CURLOPT_TIMEOUT        => 60,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => 'POST',
            ));

            $result = utf8_encode(curl_exec($curl));

            $xml = '';
            libxml_use_internal_errors(true);
            if(!empty($result))
            {
                $xml = simplexml_load_string(utf8_encode($result));
            }
            $result = $xml;

            /*if($this->config->get('shipping_dhlexpress_front_end_logs') == true)
            {
                echo "<pre>";
                echo '<h3>Request</h3>';
                print_r(htmlspecialchars($request));
                echo '<h3>Response</h3>';
                print_r($result);
                print_r($xml);
                die();
            }*/
        echo '<h3>Response</h3>';
        print_r($result);
        die();
            //echo "<PRE>";		//print_r($result);		//die();	//	die();	
            if ($result && !empty($result->GetQuoteResponse->BkgDetails->QtdShp)) {

                foreach ($result->GetQuoteResponse->BkgDetails->QtdShp as $quote) {

                    $rate_code = ((string) $quote->GlobalProductCode);
                    $rate_title = ((string) $quote->ProductShortName);
                    $delivery_date = ((string) $quote->DeliveryDate);
                    $rate_cost = (float)((string) $quote->ShippingCharge);
                    $rate_taxes = (float)((string) $quote->TotalTaxAmount);
                    $selected_services_aaray = $this->config->get('shipping_dhlexpress_service');
                    if(in_array($rate_code,$selected_services_aaray))
                    {
                        $quote_data[$rate_code] = array(
                            'code'         => 'dhlexpress.' . $rate_code,
                            'title'        => 'DHL '.$rate_title,
                            'cost'         => $rate_cost,
                            'tax_class_id' => '',
                            'text'         => Yii::$app->formatter->asCurrency($rate_cost * $currency_rate, $currency->code, [
                                \NumberFormatter::MAX_FRACTION_DIGITS => $currency->decimal_place
                            ]),
                            'service_code' => $rate_code,
                            'mod_name'		=> 'dhlexpress'
                        );
                    }
                }
            }

        $method_data = array();

        if ($quote_data || $error) {

            $method_data = array(
                'code'       => 'dhl',
                'title'      => "DHL",
                'quote'      => $quote_data,
                //'sort_order' => $this->config->get('shipping_dhlexpress_sort_order'),
                'error'      => $error
            );
        }
        return $method_data;
    }
}