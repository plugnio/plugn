<?php

namespace common\models\shipping;

use common\models\Currency;
use common\models\shipping\util\ShippingHelper;

class Ups
{
    function getQuote($address)
    {
        $rate_array = array();
        $error = '';
        $isoUPSCurrency = '';
        $carriers = array(
            //"Public carrier name" => "technical name",
            '12' => '3 Day Select',
            '03' => 'Ground',
            '02' => '2nd Day Air',
            '59' => '2nd Day Air AM',
            '01' => 'Next Day Air',
            '13' => 'Next Day Air Saver',
            '14' => 'Next Day Air Early AM',
            '11' => 'Standard',
            '07' => 'Express',
            '08' => 'Expedited',
            '54' => 'Express Plus',
            '65' => 'Saver',
            '92' => 'SurePost Less than 1 lb',
            '93' => 'SurePost 1 lb or Greater',
            '94' => 'SurePost BPM',
            '95' => 'SurePost Media',
            '08' => 'ExpeditedSM',
            '82' => 'Today Standard',
            "83" => "Today Dedicated Courier",
            "84" => "Today Intercity",
            "85" => "Today Express",
            "86" => "Today Express Saver",
            'M2' => 'First Class Mail',
            'M3' => 'Priority Mail',
            'M4' => 'Expedited Mail Innovations',
            'M5' => 'Priority Mail Innovations',
            'M6' => 'EconomyMail Innovations',
            '70' => 'Access Point Economy',
            '96' => 'Worldwide Express Freight'
        );
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

        $ups_country = "IN";
        $total_config_values = ShippingHelper::get_currency();
        $selected_currency = $total_config_values[$ups_country]['currency'];



        $currency = Currency::findOne(['code' => $selected_currency]);

        //todo: rate same as order table
        $currency_rate = $currency->rate;

        /*--------------- params end -----------------------*/




        $get_product = array();

        foreach ($products as $sing_product) {
            if (isset($sing_product['shipping']) && $sing_product['shipping'] == 1) {
                $get_product[] = $sing_product;
            }
        }

        $custom_settings = [];
        if (($this->config->get('shipping_hitups_auto_multi_ven') == "yes") && ($this->config->get('shipping_hitups_auto_rate_ven') == "yes")) {

            $hitups_auto_config_ven_grp = $this->config->get('shipping_hitups_auto_ven_grp');

            foreach ($get_product as $value) {
                $vendor_data = $this->db->query("SELECT `ven_products`, `ven_meta`, `user_id` FROM " . DB_PREFIX . "hit_ups_auto_ven_details WHERE `active` = '1' AND `one` = '" . $hitups_auto_config_ven_grp . "' AND `ven_products` like '%\"" . $value['product_id'] . "\"%'");
                if (!empty($vendor_data->row)) {
                    $vendor_id = $vendor_data->row['user_id'];
                    $vendor_credentials = json_decode($vendor_data->row['ven_meta'], true);

                    if (isset($vendor_credentials['hitups_ven_ups_id']) && isset($vendor_credentials['hitups_ven_ups_pass']) && isset($vendor_credentials['hitups_ven_ups_acc']) && isset($vendor_credentials['hitups_ven_ups_access']) && !empty($vendor_credentials['hitups_ven_ups_id']) && !empty($vendor_credentials['hitups_ven_ups_pass']) && !empty($vendor_credentials['hitups_ven_ups_acc']) && !empty($vendor_credentials['hitups_ven_ups_access'])) {
                        if (!isset($custom_settings[$vendor_id])) {
                            $custom_settings[$vendor_id]['ups_id'] = $vendor_credentials['hitups_ven_ups_id'];
                            $custom_settings[$vendor_id]['ups_pass'] = $vendor_credentials['hitups_ven_ups_pass'];
                            $custom_settings[$vendor_id]['ups_acc_no'] = $vendor_credentials['hitups_ven_ups_acc'];
                            $custom_settings[$vendor_id]['ups_access_key'] = $vendor_credentials['hitups_ven_ups_access'];

                            if (isset($vendor_credentials['hitups_ven_ups_name']) && !empty($vendor_credentials['hitups_ven_ups_name'])) {
                                $custom_settings[$vendor_id]['ups_ship_name'] = $vendor_credentials['hitups_ven_ups_name'];
                            } else {
                                $custom_settings[$vendor_id]['ups_ship_name'] = $this->config->get('shipping_hitups_auto_shipper_name');
                            }

                            if (isset($vendor_credentials['hitups_ven_ups_company']) && !empty($vendor_credentials['hitups_ven_ups_company'])) {
                                $custom_settings[$vendor_id]['ups_ship_company'] = $vendor_credentials['hitups_ven_ups_company'];
                            } else {
                                $custom_settings[$vendor_id]['ups_ship_company'] = $this->config->get('shipping_hitups_auto_company_name');
                            }

                            if (isset($vendor_credentials['hitups_ven_ups_phone']) && !empty($vendor_credentials['hitups_ven_ups_phone'])) {
                                $custom_settings[$vendor_id]['ups_ship_phone'] = $vendor_credentials['hitups_ven_ups_phone'];
                            } else {
                                $custom_settings[$vendor_id]['ups_ship_phone'] = $this->config->get('shipping_hitups_auto_phone_num');
                            }

                            if (isset($vendor_credentials['hitups_ven_ups_email']) && !empty($vendor_credentials['hitups_ven_ups_email'])) {
                                $custom_settings[$vendor_id]['ups_ship_email'] = $vendor_credentials['hitups_ven_ups_email'];
                            } else {
                                $custom_settings[$vendor_id]['ups_ship_email'] = $this->config->get('shipping_hitups_auto_email_addr');
                            }

                            if (isset($vendor_credentials['hitups_ven_ups_addr1']) && !empty($vendor_credentials['hitups_ven_ups_addr1'])) {
                                $custom_settings[$vendor_id]['ups_ship_addr1'] = $vendor_credentials['hitups_ven_ups_addr1'];
                            } else {
                                $custom_settings[$vendor_id]['ups_ship_addr1'] = $this->config->get('shipping_hitups_auto_address1');
                            }

                            $custom_settings[$vendor_id]['ups_ship_addr2'] = isset($vendor_credentials['hitups_ven_ups_addr1']) ? $vendor_credentials['hitups_ven_ups_addr1'] : '';

                            if (isset($vendor_credentials['hitups_ven_ups_city']) && !empty($vendor_credentials['hitups_ven_ups_city'])) {
                                $custom_settings[$vendor_id]['ups_ship_city'] = $vendor_credentials['hitups_ven_ups_city'];
                            } else {
                                $custom_settings[$vendor_id]['ups_ship_city'] = $this->config->get('shipping_hitups_auto_city');
                            }

                            if (isset($vendor_credentials['hitups_ven_ups_state']) && !empty($vendor_credentials['hitups_ven_ups_state'])) {
                                $custom_settings[$vendor_id]['ups_ship_state'] = $vendor_credentials['hitups_ven_ups_state'];
                            } else {
                                $custom_settings[$vendor_id]['ups_ship_state'] = $this->config->get('shipping_hitups_auto_state');
                            }

                            if (isset($vendor_credentials['hitups_ven_ups_zip']) && !empty($vendor_credentials['hitups_ven_ups_zip'])) {
                                $custom_settings[$vendor_id]['ups_ship_zip'] = $vendor_credentials['hitups_ven_ups_zip'];
                            } else {
                                $custom_settings[$vendor_id]['ups_ship_zip'] = $this->config->get('shipping_hitups_auto_postcode');
                            }

                            if (isset($vendor_credentials['hitups_ven_ups_country']) && !empty($vendor_credentials['hitups_ven_ups_country'])) {
                                $custom_settings[$vendor_id]['ups_ship_country'] = $vendor_credentials['hitups_ven_ups_country'];
                            } else {
                                $custom_settings[$vendor_id]['ups_ship_country'] = $this->config->get('shipping_hitups_auto_country_code');
                            }
                        }

                        $custom_settings[$vendor_id]['products'][] = $value;
                    } else {
                        //default
                        if (!isset($custom_settings[$vendor_id])) {
                            $custom_settings[$vendor_id] = $this->getDefaultVenConfig("default");
                        }
                        $custom_settings[$vendor_id]['products'][] = $value;
                    }

                } else {
                    //default
                    $vendor_id = "default";
                    if (!isset($custom_settings[$vendor_id])) {
                        $custom_settings[$vendor_id] = $this->getDefaultVenConfig("default");
                    }
                    $custom_settings[$vendor_id]['products'][] = $value;
                }
            }
        } else {
            //default
            $vendor_id = "default";
            $custom_settings[$vendor_id] = $this->getDefaultVenConfig("default");
            $custom_settings[$vendor_id]['products'] = $get_product;
        }

        $shipping_rates = [];
        $exe = 1;
        foreach ($custom_settings as $key => $custom_setting) {
            $ups_packs = ShippingHelper::packages($custom_setting['products'], $selected_currency);
            $total_value = 0;
            $total_weight = 0;
            $weight_unit = 'KGS';//($this->config->get('shipping_hitups_auto_weight') == true) ? 'LBS' :
            $dim_unit = 'CM';//($this->config->get('shipping_hitups_auto_weight') == true) ? 'IN' :
            $packages_xml = "";
            foreach ($ups_packs as $pack) {
                $total_value += $pack['InsuredValue']['Amount'];
                $total_weight += $pack['Weight']['Value'];

                $packages_xml .= '<Package>
											<PackagingType>
												<Code>02</Code>
											</PackagingType>';

                if (isset($pack['Dimensions'])) {
                    $packages_xml .= '	<Dimensions>
								               <UnitOfMeasurement>  
								                 <Code>' . $dim_unit . '</Code>  
								               </UnitOfMeasurement>  
								                <Length>' . $pack['Dimensions']['Length'] . '</Length>  
								                <Width>' . $pack['Dimensions']['Width'] . '</Width>  
								                <Height>' . $pack['Dimensions']['Height'] . '</Height>  
								            </Dimensions>';
                }

                $packages_xml .= '		<PackageWeight>
												<UnitOfMeasurement>
													<Code>' . $weight_unit . '</Code>
												</UnitOfMeasurement>
												<Weight>' . $pack['Weight']['Value'] . '</Weight>
											</PackageWeight>
										</Package>';
            }

            if (!$this->config->get('shipping_hitups_auto_test')) {
                $url = 'https://onlinetools.ups.com/ups.app/xml/Rate';
            } else {
                $url = 'https://wwwcie.ups.com/ups.app/xml/Rate';
            }

            // $fetch_accountrates = ($this->config->get('shipping_hitups_auto_rate_type') == 'ACCOUNT') ? "<PaymentAccountNumber>" . $custom_settings[$key]['ups_acc_no'] . "</PaymentAccountNumber>" : "";

            $xml = '<?xml version="1.0"?>';
            $xml .= '<AccessRequest xml:lang="en-US">';
            $xml .= '	<AccessLicenseNumber>' . $custom_settings[$key]['ups_access_key'] . '</AccessLicenseNumber>';
            $xml .= '	<UserId>' . $custom_settings[$key]['ups_id'] . '</UserId>';
            $xml .= '	<Password>' . $custom_settings[$key]['ups_pass'] . '</Password>';
            $xml .= '</AccessRequest>';
            $xml .= '<?xml version="1.0"?>';
            $xml .= '<RatingServiceSelectionRequest xml:lang="en-US">';
            $xml .= '	<Request>';
            $xml .= '		<TransactionReference>';
            $xml .= '			<CustomerContext>hIT TECH Rate Request</CustomerContext>';
            $xml .= '			<XpciVersion>1.0001</XpciVersion>';
            $xml .= '		</TransactionReference>';
            $xml .= '		<RequestAction>Rate</RequestAction>';
            $xml .= '		<RequestOption>shop</RequestOption>';
            $xml .= '	</Request>';
            $xml .= '	<Shipment>';
            $xml .= '		<Shipper>';
            $xml .= '		<Name>' . $custom_settings[$key]['ups_ship_name'] . '</Name>';
            $xml .= '		<ShipperNumber>' . $custom_settings[$key]['ups_acc_no'] . '</ShipperNumber>';
            $xml .= '			<Address>';
            $xml .= '				<City>' . $custom_settings[$key]['ups_ship_city'] . '</City>';
            $xml .= '				<StateProvinceCode>' . $custom_settings[$key]['ups_ship_state'] . '</StateProvinceCode>';
            $xml .= '				<CountryCode>' . $custom_settings[$key]['ups_ship_country'] . '</CountryCode>';
            $xml .= '				<PostalCode>' . $custom_settings[$key]['ups_ship_zip'] . '</PostalCode>';
            $xml .= '			</Address>';
            $xml .= '		</Shipper>';
            $xml .= '		<ShipTo>';
            $xml .= '			<Address>';
            $xml .= ' 				<City>' . $address['city'] . '</City>';
            $xml .= '				<StateProvinceCode>' . $address['zone_code'] . '</StateProvinceCode>';
            $xml .= '				<CountryCode>' . $address['iso_code_2'] . '</CountryCode>';
            $xml .= '				<PostalCode>' . $address['postcode'] . '</PostalCode>';
            $xml .= '			</Address>';
            $xml .= '		</ShipTo>';
            $xml .= '		<ShipFrom>';
            $xml .= '			<Address>';
            $xml .= '				<City>' . $custom_settings[$key]['ups_ship_city'] . '</City>';
            $xml .= '				<StateProvinceCode>' . $custom_settings[$key]['ups_ship_state'] . '</StateProvinceCode>';
            $xml .= '				<CountryCode>' . $custom_settings[$key]['ups_ship_country'] . '</CountryCode>';
            $xml .= '				<PostalCode>' . $custom_settings[$key]['ups_ship_zip'] . '</PostalCode>';
            $xml .= '			</Address>';
            $xml .= '		</ShipFrom>';
            $xml .= $packages_xml;
            $xml .= '		<RateInformation><NegotiatedRatesIndicator/></RateInformation>';
            $xml .= '	</Shipment>';
            $xml .= '</RatingServiceSelectionRequest>';
            // echo '<pre>';print_r(htmlspecialchars($xml));die();
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_HEADER => false,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
            ));
            $result = utf8_encode(curl_exec($curl));
            $xmloutput = !empty($result) ? simplexml_load_string(utf8_encode($result)) : "";

            if ($this->config->get('shipping_hitups_auto_rate_error') == '1') {
                echo '<h3>Package ' . $exe . '</h3><br><h4>Request</h4>';
                // if(isset($xmloutput->Response->Error) && $xmloutput->Response->Error->ErrorSeverity = 'Hard'){
                // 	print_r("UPS: ");
                // 	print_r((string)$xmloutput->Response->Error->ErrorDescription);
                // }
                echo htmlspecialchars($xml);
                echo "<br><pre><h4>Response</h4><br>";
                print_r($xmloutput);
                if (count($custom_settings) == $exe) {
                    die();
                } else {
                    $exe++;
                    continue;
                }
            }

            if (!empty($xmloutput) && isset($xmloutput->RatedShipment) && !empty($xmloutput->RatedShipment)) {
                $selected_services_aaray = $this->config->get('shipping_hitups_auto_service');
                $rate = [];
                foreach ($xmloutput->RatedShipment as $quote) {
                    $rate_cost = 0;
                    $rate_code = (string)$quote->Service->Code;

                    if (in_array($rate_code, $selected_services_aaray)) {
                        if ($this->config->get('shipping_hitups_auto_rate_type') == 'ACCOUNT' && isset($quote->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue)) {
                            $isoUPSCurrency = (string)$quote->NegotiatedRates->NetSummaryCharges->GrandTotal->CurrencyCode;
                            $rate_cost = (float)$quote->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue;
                        } else {
                            $isoUPSCurrency = (string)$quote->TotalCharges->CurrencyCode;
                            $rate_cost = (float)$quote->TotalCharges->MonetaryValue;
                        }

                        $rate[$rate_code] = $rate_cost;
                    }
                }
                $shipping_rates[$key] = $rate;
            } else {
                return false;
            }
        }

        $final_rates = [];
        if (!empty($shipping_rates)) {
            foreach ($shipping_rates as $rate_ky => $ship_rate) {
                if (empty($final_rates)) {
                    $final_rates = $ship_rate;
                } else {
                    foreach ($final_rates as $f_ky => $f_rate) {
                        if (array_key_exists($f_ky, $ship_rate)) {
                            $final_rates[$f_ky] += $ship_rate[$f_ky];
                        } else {
                            unset($final_rates[$f_ky]);
                        }
                    }
                }
            }
        }

        // echo '<pre>';print_r($shipping_rates);print_r($final_rates);die();

        if (!empty($final_rates)) {
            foreach ($final_rates as $rate_code => $rate_cost) {
                $rate_title = $carriers[$rate_code];
                $quote_data[$rate_code] = array(
                    'code' => 'ups.' . $rate_code,
                    'title' => 'UPS ' . $rate_title,
                    'cost' => $rate_cost,
                    'tax_class_id' => '',
                    //'text' => $this->currency->format($this->currency->convert($rate_cost, $isoUPSCurrency, $this->session->data['currency']), $this->session->data['currency'], 1.0000000),
                    'text'         => Yii::$app->formatter->asCurrency($rate_cost * $currency_rate, $currency->code, [
                        \NumberFormatter::MAX_FRACTION_DIGITS => $currency->decimal_place
                    ]),
                    'carrier' => 'hitups_auto',
                    'service_code' => $rate_code,
                );
            }
        }

        $method_data = array();

        if ($quote_data || $error) {

            $method_data = array(
                'code' => 'ups',
                'title' => "UPS",
                'quote' => $quote_data,
                //'sort_order' => $this->config->get('shipping_hitups_auto_sort_order'),
                'error' => $error
            );
        }
        return $method_data;
    }

    public function getDefaultVenConfig($vendor_id = "default"){
        $custom_settings['ups_id'] = $this->config->get('shipping_hitups_auto_key');
        $custom_settings['ups_pass'] = $this->config->get('shipping_hitups_auto_password');
        $custom_settings['ups_acc_no'] = $this->config->get('shipping_hitups_auto_account');
        $custom_settings['ups_access_key'] = $this->config->get('shipping_hitups_auto_akey');
        $custom_settings['ups_ship_name'] = $this->config->get('shipping_hitups_auto_shipper_name');
        $custom_settings['ups_ship_company'] = $this->config->get('shipping_hitups_auto_company_name');
        $custom_settings['ups_ship_phone'] = $this->config->get('shipping_hitups_auto_phone_num');
        $custom_settings['ups_ship_email'] = $this->config->get('shipping_hitups_auto_email_addr');
        $custom_settings['ups_ship_addr1'] = $this->config->get('shipping_hitups_auto_address1');
        $custom_settings['ups_ship_addr2'] = $this->config->get('shipping_hitups_auto_address2');
        $custom_settings['ups_ship_city'] = $this->config->get('shipping_hitups_auto_city');
        $custom_settings['ups_ship_state'] = $this->config->get('shipping_hitups_auto_state');
        $custom_settings['ups_ship_zip'] = $this->config->get('shipping_hitups_auto_postcode');
        $custom_settings['ups_ship_country'] = $this->config->get('shipping_hitups_auto_country_code');
        return $custom_settings;
    }
}