<?php

namespace common\models\shipping;

use common\models\Currency;
use common\models\shipping\util\ShippingHelper;

class Fedex
{
    public static function getQuote($address) {

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
         $fedex_key = "";
         $fedex_password = "";
         $fedex_account = "";
         $fedex_meter = "";
         $fedex_dropoff_type = "DROP_BOX";
/**
REGULAR_PICKUP
REQUEST_COURIER
DROP_BOX
BUSINESS_SERVICE_CENTER
STATION
*/

         $fedex_fedpack_type = "FEDEX_BOX";
/*
YOUR_PACKAGING
FEDEX_BOX
FEDEX_PAK
        FEDEX_TUBE
        FEDEX_10KG_BOX
        FEDEX_25KG_BOX
        FEDEX_ENVELOPE
        FEDEX_EXTRA_LARGE_BOX
        FEDEX_LARGE_BOX
        FEDEX_MEDIUM_BOX
        FEDEX_SMALL_BOX*/

        //source
        $fedex_country_code= "IN";
        $fedex_postcode = "451010";

        //destination
        $county_code_to = "IN";//$address['country'][''];
        $post_code_to = "391410";

        $aramex_country = $county_code_to;//$this->config->get('shipping_hitaramex_country_code');

        $total_config_values = ShippingHelper::get_currency();
        $selected_currency = $total_config_values[$aramex_country]['currency'];

        $currency = Currency::findOne(['code' => $selected_currency]);

        //todo: rate same as order table
        $currency_rate = $currency->rate;

        /*--------------- params end -----------------------*/

        $selected_services_aaray = [
            'FIRST_OVERNIGHT',
            'PRIORITY_OVERNIGHT',
            'STANDARD_OVERNIGHT',
            'FEDEX_2_DAY_AM',
            'FEDEX_2_DAY',
            'SAME_DAY',
            'SAME_DAY_CITY',
            'SAME_DAY_METRO_AFTERNOON',
            'SAME_DAY_METRO_MORNING',
            'SAME_DAY_METRO_RUSH',
            'FEDEX_EXPRESS_SAVER',
            'GROUND_HOME_DELIVERY',
            'FEDEX_GROUND',
            'INTERNATIONAL_ECONOMY',
            'INTERNATIONAL_ECONOMY_DISTRIBUTION',
            'INTERNATIONAL_FIRST',
            'INTERNATIONAL_GROUND',
            'INTERNATIONAL_PRIORITY',
            'INTERNATIONAL_PRIORITY_DISTRIBUTION',
            'EUROPE_FIRST_INTERNATIONAL_PRIORITY',
            'INTERNATIONAL_PRIORITY_EXPRESS',
            'FEDEX_INTERNATIONAL_PRIORITY_PLUS',
            'INTERNATIONAL_DISTRIBUTION_FREIGHT',
            'FEDEX_1_DAY_FREIGHT',
            'FEDEX_2_DAY_FREIGHT',
            'FEDEX_3_DAY_FREIGHT',
            'INTERNATIONAL_ECONOMY_FREIGHT',
            'INTERNATIONAL_PRIORITY_FREIGHT',
            'SMART_POST',
            'FEDEX_FIRST_FREIGHT',
            'FEDEX_FREIGHT_ECONOMY',
            'FEDEX_FREIGHT_PRIORITY',
            'FEDEX_CARGO_AIRPORT_TO_AIRPORT',
            'FEDEX_CARGO_FREIGHT_FORWARDING',
            'FEDEX_CARGO_INTERNATIONAL_EXPRESS_FREIGHT',
            'FEDEX_CARGO_INTERNATIONAL_PREMIUM',
            'FEDEX_CARGO_MAIL',
            'FEDEX_CARGO_REGISTERED_MAIL',
            'FEDEX_CARGO_SURFACE_MAIL',
            'FEDEX_CUSTOM_CRITICAL_AIR_EXPEDITE_EXCLUSIVE_USE',
            'FEDEX_CUSTOM_CRITICAL_AIR_EXPEDITE_NETWORK',
            'FEDEX_CUSTOM_CRITICAL_CHARTER_AIR',
            'FEDEX_CUSTOM_CRITICAL_POINT_TO_POINT',
            'FEDEX_CUSTOM_CRITICAL_SURFACE_EXPEDITE',
            'FEDEX_CUSTOM_CRITICAL_SURFACE_EXPEDITE_EXCLUSIVE_USE',
            'FEDEX_CUSTOM_CRITICAL_TEMP_ASSURE_AIR',
            'FEDEX_CUSTOM_CRITICAL_TEMP_ASSURE_VALIDATED_AIR',
            'FEDEX_CUSTOM_CRITICAL_WHITE_GLOVE_SERVICES',
            'TRANSBORDER_DISTRIBUTION_CONSOLIDATION',
            'FEDEX_DISTANCE_DEFERRED',
            'FEDEX_NEXT_DAY_EARLY_MORNING',
            'FEDEX_NEXT_DAY_MID_MORNING',
            'FEDEX_NEXT_DAY_AFTERNOON',
            'FEDEX_NEXT_DAY_END_OF_DAY',
            'FEDEX_NEXT_DAY_FREIGHT'
        ];

            $fedex_country = $county_code_to;//$this->config->get('shipping_hitshippo_fedex_country_code');

            $timestamp = date( 'c' , strtotime( '+1 Weekday' ) );

            $residential_del ='true';// ($this->config->get('shipping_hitshippo_fedex_residential') == true) ?  : 'false';

            $rate_type = "NONE";// "LIST";// $this->config->get('shipping_hitshippo_fedex_rate_type');

            $get_product = array();
            foreach($products as $sing_product)
            {
                if(isset($sing_product['shipping']) && $sing_product['shipping'] == 1)
                {
                    $get_product[] = $sing_product;
                }
            }

            $fedex_packs = ShippingHelper::packages( $get_product,$selected_currency );

            $xml_line_item ='';
            $total_value = 0;
            $total_weight = 0;

            foreach($fedex_packs as $key => $pack)
            {
                $total_value += $pack['InsuredValue']['Amount'];
                $total_weight += $pack['Weight']['Value'];

                $xml_line_item .= '<v22:RequestedPackageLineItems>
								   <v22:SequenceNumber>'.($key+1).'</v22:SequenceNumber>
								   <v22:GroupNumber>'.$pack['GroupNumber'].'</v22:GroupNumber>
								   <v22:GroupPackageCount>'.$pack['GroupPackageCount'].'</v22:GroupPackageCount>
								   <v22:Weight>
									  <v22:Units>'.$pack['Weight']['Units'].'</v22:Units>
									  <v22:Value>'.$pack['Weight']['Value'].'</v22:Value>
								   </v22:Weight>';
                if(isset($pack['Dimensions']))
                {
                    $xml_line_item .='<v22:Dimensions>
									  <v22:Length>'.$pack['Dimensions']['Length'].'</v22:Length>
									  <v22:Width>'.$pack['Dimensions']['Width'].'</v22:Width>
									  <v22:Height>'.$pack['Dimensions']['Height'].'</v22:Height>
									  <v22:Units>'.$pack['Dimensions']['Units'].'</v22:Units>
								   </v22:Dimensions>';
                }
                $xml_line_item .='</v22:RequestedPackageLineItems>';
            }

            if (!$sandbox) {
                $url = 'https://ws.fedex.com:443/web-services/rate';
            } else {
                $url = 'https://wsbeta.fedex.com:443/web-services/rate';
            }

            $weight_unit = 'KG';//($this->config->get('shipping_hitshippo_fedex_weight') == true) ? 'LB' :
            $dim_unit = 'CM';//($this->config->get('shipping_hitshippo_fedex_weight') == true) ? 'IN' :

            $req_xml = "<soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:v22='http://fedex.com/ws/rate/v22'>
				   <soapenv:Header/>
				   <soapenv:Body>
				      <v22:RateRequest>
				         <v22:WebAuthenticationDetail>
				            <v22:UserCredential>
				               <v22:Key>".$fedex_key."</v22:Key>
				               <v22:Password>".$fedex_password."</v22:Password>
				            </v22:UserCredential>
				         </v22:WebAuthenticationDetail>
				         <v22:ClientDetail>
				            <v22:AccountNumber>".$fedex_account."</v22:AccountNumber>
				            <v22:MeterNumber>".$fedex_meter."</v22:MeterNumber>
				         </v22:ClientDetail>
				         <v22:TransactionDetail>
				            <v22:CustomerTransactionId>*** PS Rate Request ***</v22:CustomerTransactionId>
				         </v22:TransactionDetail>
				         <v22:Version>
				            <v22:ServiceId>crs</v22:ServiceId>
				            <v22:Major>22</v22:Major>
				            <v22:Intermediate>0</v22:Intermediate>
				            <v22:Minor>0</v22:Minor>
				         </v22:Version>
				         <v22:RequestedShipment>
				            <v22:ShipTimestamp>".$timestamp."</v22:ShipTimestamp>
				            <v22:DropoffType>".$fedex_dropoff_type."</v22:DropoffType>
				            <v22:PackagingType>".$fedex_fedpack_type."</v22:PackagingType>
				            <v22:TotalWeight>
				               <v22:Units>".$weight_unit."</v22:Units>
				               <v22:Value>".$total_weight."</v22:Value>
				            </v22:TotalWeight>
				            <v22:PreferredCurrency>".$selected_currency."</v22:PreferredCurrency>
				            <v22:Shipper>
				               <v22:AccountNumber>".$fedex_account."</v22:AccountNumber>
				               <v22:Address>
				                  <v22:PostalCode>".$fedex_postcode."</v22:PostalCode>
				                  <v22:CountryCode>".$fedex_country_code."</v22:CountryCode>
				               </v22:Address>
				            </v22:Shipper>
				            <v22:Recipient>
				               <v22:Address>
				                   <v22:PostalCode>".$post_code_to."</v22:PostalCode>
				                  <v22:CountryCode>".$county_code_to."</v22:CountryCode>
				                  <v22:Residential>".$residential_del."</v22:Residential>
				               </v22:Address>
				            </v22:Recipient>
				             <v22:ShippingChargesPayment>
				               <v22:PaymentType>SENDER</v22:PaymentType>
				               <v22:Payor>
				                  <v22:ResponsibleParty>
				                     <v22:AccountNumber>".$fedex_account."</v22:AccountNumber>
				                  </v22:ResponsibleParty>
				               </v22:Payor>
				            </v22:ShippingChargesPayment>
				            <v22:RateRequestTypes>".$rate_type."</v22:RateRequestTypes>
				            <v22:PackageCount>".sizeof($fedex_packs)."</v22:PackageCount>
				            ".$xml_line_item."
							</v22:RequestedShipment>
				      </v22:RateRequest>
				   </soapenv:Body>
				</soapenv:Envelope>";


            // echo '<pre>';
            // print_r($url);
            // print_r(htmlspecialchars($request));
            // die();

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POSTFIELDS, $req_xml);
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
            $result_xml = str_replace(array(':','-'), '', $result);
            // print_r($result);
            $xml = '';
            libxml_use_internal_errors(true);
            if(!empty($result))
            {
                $xml = simplexml_load_string(utf8_encode($result_xml));
            }

            $result = $xml->SOAPENVBody->RateReply;

            /*if($this->config->get('shipping_hitshippo_fedex_front_end_logs') == true)
            {
                echo "<pre>";
                echo '<h3>Request</h3>';
                print_r(htmlspecialchars($req_xml));
                echo '<h3>Response</h3>';
                print_r($xml);
                echo '<h3>Response - Result</h3>';
                print_r($result);
                die();
            }*/

            if ($result && !empty($result->RateReplyDetails)) {

                foreach ($result->RateReplyDetails as $quote) {

                    $rate_code = ((string) $quote->ServiceType);
                    $rate_title = ((string) $quote->ServiceType);
                    $rate_cost = 0;

                    if(in_array($rate_code,$selected_services_aaray))
                    {

                        $shipment_details = '';
                        foreach($quote->RatedShipmentDetails as $shipment_data)
                        {
                            if ( $rate_type == "LIST" ) {
                                if ( strstr( $shipment_data->ShipmentRateDetail->RateType, 'PAYOR_LIST' ) ) {
                                    $shipment_details = $shipment_data;
                                    break;
                                }
                            }else{
                                if ( strstr( $shipment_data->ShipmentRateDetail->RateType, 'PAYOR_ACCOUNT' ) ) {
                                    $shipment_details = $shipment_data;
                                    break;
                                }
                            }

                        }

                        if(empty($shipment_details))
                        {
                            $shipment_details = $quote->RatedShipmentDetails;
                        }

                        if(empty($shipment_details))
                        {
                            continue;
                        }

                        $rate_cost = (float)((string) $shipment_details->ShipmentRateDetail->TotalNetCharge->Amount);

                        $quote_data[$rate_code] = array(
                            'code'         => 'fedex.' . $rate_code,
                            'title'        => 'fedex '.$rate_title,
                            'cost'         => $rate_cost,
                            'tax_class_id' => '',
                            'text'         => Yii::$app->formatter->asCurrency($rate_cost * $currency_rate, $currency->code, [
                                \NumberFormatter::MAX_FRACTION_DIGITS => $currency->decimal_place
                            ]),
                            'service_code' => $rate_code,
                            'mod_name'		=> 'fedex'
                        );
                    }
                }
            }

        $method_data = array();

        if ($quote_data || $error) {

            $method_data = array(
                'code'       => 'fedex',
                'title'      => "Fedex",
                'quote'      => $quote_data,
                //'sort_order' => $this->config->get('shipping_hitshippo_fedex_sort_order'),
                'error'      => $error
            );
        }
        return $method_data;
    }
}