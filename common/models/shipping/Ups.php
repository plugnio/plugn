<?php

namespace common\models\shipping;

class Ups
{
    function getQuote($address) {

        $this->load->language('extension/shipping/hitups_auto');

        if($this->config->get('shipping_hitups_auto_realtime_rates') == true)
        {
            $status = true;
        }
        else
        {
            $status = false;
        }
        $rate_array = array();
        $error = '';
        $isoUPSCurrency = '';
        $carriers = array(
            //"Public carrier name" => "technical name",
            '12'                    => '3 Day Select',
            '03'                    => 'Ground',
            '02'                    => '2nd Day Air',
            '59'                    => '2nd Day Air AM',
            '01'                    => 'Next Day Air',
            '13'                    => 'Next Day Air Saver',
            '14'                    => 'Next Day Air Early AM',
            '11'                    => 'Standard',
            '07'                    => 'Express',
            '08'                    => 'Expedited',
            '54'                    => 'Express Plus',
            '65'                    => 'Saver',
            '92'                    => 'SurePost Less than 1 lb',
            '93'                    => 'SurePost 1 lb or Greater',
            '94'                    => 'SurePost BPM',
            '95'                    => 'SurePost Media',
            '08'                    => 'ExpeditedSM',
            '82'                    => 'Today Standard',
            "83"					 => "Today Dedicated Courier",
            "84"					=> "Today Intercity",
            "85"					 => "Today Express",
            "86" 					=> "Today Express Saver",
            'M2'                    => 'First Class Mail',
            'M3'                    => 'Priority Mail',
            'M4'                    => 'Expedited Mail Innovations',
            'M5'                    => 'Priority Mail Innovations',
            'M6'                    => 'EconomyMail Innovations',
            '70'                    => 'Access Point Economy',
            '96'                    => 'Worldwide Express Freight'
        );
        $quote_data = array();

        if ($status) {

            $products = $this->cart->getProducts();
            $ups_country = $this->config->get('shipping_hitups_auto_country_code');
            $total_config_values = $this->hit_get_currency();
            $selected_currency = $total_config_values[$ups_country]['currency'];
            $get_product = array();
            foreach($products as $sing_product)
            {
                if(isset($sing_product['shipping']) && $sing_product['shipping'] == 1)
                {
                    $get_product[] = $sing_product;
                }
            }

            $custom_settings = [];
            if ( ($this->config->get('shipping_hitups_auto_multi_ven') == "yes") && ($this->config->get('shipping_hitups_auto_rate_ven') == "yes")) {
                $hitups_auto_config_ven_grp = $this->config->get('shipping_hitups_auto_ven_grp');

                foreach ($get_product as $value) {
                    $vendor_data = $this->db->query("SELECT `ven_products`, `ven_meta`, `user_id` FROM " . DB_PREFIX . "hit_ups_auto_ven_details WHERE `active` = '1' AND `one` = '". $hitups_auto_config_ven_grp ."' AND `ven_products` like '%\"". $value['product_id'] ."\"%'");
                    if (!empty($vendor_data->row)) {
                        $vendor_id = $vendor_data->row['user_id'];
                        $vendor_credentials = json_decode($vendor_data->row['ven_meta'], true);

                        if ( isset($vendor_credentials['hitups_ven_ups_id']) && isset($vendor_credentials['hitups_ven_ups_pass']) && isset($vendor_credentials['hitups_ven_ups_acc']) && isset($vendor_credentials['hitups_ven_ups_access']) && !empty($vendor_credentials['hitups_ven_ups_id']) && !empty($vendor_credentials['hitups_ven_ups_pass']) && !empty($vendor_credentials['hitups_ven_ups_acc']) && !empty($vendor_credentials['hitups_ven_ups_access']) ) {
                            if (!isset($custom_settings[$vendor_id])) {
                                $custom_settings[$vendor_id]['ups_id'] = $vendor_credentials['hitups_ven_ups_id'];
                                $custom_settings[$vendor_id]['ups_pass'] = $vendor_credentials['hitups_ven_ups_pass'];
                                $custom_settings[$vendor_id]['ups_acc_no'] = $vendor_credentials['hitups_ven_ups_acc'];
                                $custom_settings[$vendor_id]['ups_access_key'] = $vendor_credentials['hitups_ven_ups_access'];

                                if (isset($vendor_credentials['hitups_ven_ups_name']) && !empty($vendor_credentials['hitups_ven_ups_name'])) {
                                    $custom_settings[$vendor_id]['ups_ship_name'] = $vendor_credentials['hitups_ven_ups_name'];
                                }else {
                                    $custom_settings[$vendor_id]['ups_ship_name'] = $this->config->get('shipping_hitups_auto_shipper_name');
                                }

                                if (isset($vendor_credentials['hitups_ven_ups_company']) && !empty($vendor_credentials['hitups_ven_ups_company'])) {
                                    $custom_settings[$vendor_id]['ups_ship_company'] = $vendor_credentials['hitups_ven_ups_company'];
                                }else {
                                    $custom_settings[$vendor_id]['ups_ship_company'] = $this->config->get('shipping_hitups_auto_company_name');
                                }

                                if (isset($vendor_credentials['hitups_ven_ups_phone']) && !empty($vendor_credentials['hitups_ven_ups_phone'])) {
                                    $custom_settings[$vendor_id]['ups_ship_phone'] = $vendor_credentials['hitups_ven_ups_phone'];
                                }else {
                                    $custom_settings[$vendor_id]['ups_ship_phone'] = $this->config->get('shipping_hitups_auto_phone_num');
                                }

                                if (isset($vendor_credentials['hitups_ven_ups_email']) && !empty($vendor_credentials['hitups_ven_ups_email'])) {
                                    $custom_settings[$vendor_id]['ups_ship_email'] = $vendor_credentials['hitups_ven_ups_email'];
                                }else {
                                    $custom_settings[$vendor_id]['ups_ship_email'] = $this->config->get('shipping_hitups_auto_email_addr');
                                }

                                if (isset($vendor_credentials['hitups_ven_ups_addr1']) && !empty($vendor_credentials['hitups_ven_ups_addr1'])) {
                                    $custom_settings[$vendor_id]['ups_ship_addr1'] = $vendor_credentials['hitups_ven_ups_addr1'];
                                }else {
                                    $custom_settings[$vendor_id]['ups_ship_addr1'] = $this->config->get('shipping_hitups_auto_address1');
                                }

                                $custom_settings[$vendor_id]['ups_ship_addr2'] = isset($vendor_credentials['hitups_ven_ups_addr1']) ? $vendor_credentials['hitups_ven_ups_addr1'] : '';

                                if (isset($vendor_credentials['hitups_ven_ups_city']) && !empty($vendor_credentials['hitups_ven_ups_city'])) {
                                    $custom_settings[$vendor_id]['ups_ship_city'] = $vendor_credentials['hitups_ven_ups_city'];
                                }else {
                                    $custom_settings[$vendor_id]['ups_ship_city'] = $this->config->get('shipping_hitups_auto_city');
                                }

                                if (isset($vendor_credentials['hitups_ven_ups_state']) && !empty($vendor_credentials['hitups_ven_ups_state'])) {
                                    $custom_settings[$vendor_id]['ups_ship_state'] = $vendor_credentials['hitups_ven_ups_state'];
                                }else {
                                    $custom_settings[$vendor_id]['ups_ship_state'] = $this->config->get('shipping_hitups_auto_state');
                                }

                                if (isset($vendor_credentials['hitups_ven_ups_zip']) && !empty($vendor_credentials['hitups_ven_ups_zip'])) {
                                    $custom_settings[$vendor_id]['ups_ship_zip'] = $vendor_credentials['hitups_ven_ups_zip'];
                                }else {
                                    $custom_settings[$vendor_id]['ups_ship_zip'] = $this->config->get('shipping_hitups_auto_postcode');
                                }

                                if (isset($vendor_credentials['hitups_ven_ups_country']) && !empty($vendor_credentials['hitups_ven_ups_country'])) {
                                    $custom_settings[$vendor_id]['ups_ship_country'] = $vendor_credentials['hitups_ven_ups_country'];
                                }else {
                                    $custom_settings[$vendor_id]['ups_ship_country'] = $this->config->get('shipping_hitups_auto_country_code');
                                }
                            }

                            $custom_settings[$vendor_id]['products'][] = $value;
                        }else {
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
            }else{
                //default
                $vendor_id = "default";
                $custom_settings[$vendor_id] = $this->getDefaultVenConfig("default");
                $custom_settings[$vendor_id]['products'] = $get_product;
            }

            $shipping_rates = [];
            $exe = 1;
            foreach ($custom_settings as $key => $custom_setting) {
                $ups_packs	=	$this->hit_get_ups_packages( $custom_setting['products'],$selected_currency );
                $total_value = 0;
                $total_weight = 0;
                $weight_unit = ($this->config->get('shipping_hitups_auto_weight') == true) ? 'LBS' : 'KGS';
                $dim_unit = ($this->config->get('shipping_hitups_auto_weight') == true) ? 'IN' : 'CM';
                $packages_xml = "";
                foreach($ups_packs as $pack)
                {
                    $total_value += $pack['InsuredValue']['Amount'];
                    $total_weight += $pack['Weight']['Value'];

                    $packages_xml .= '<Package>
											<PackagingType>
												<Code>02</Code>
											</PackagingType>';

                    if (isset($pack['Dimensions'])) {
                        $packages_xml .= '	<Dimensions>
								               <UnitOfMeasurement>  
								                 <Code>'.$dim_unit.'</Code>  
								               </UnitOfMeasurement>  
								                <Length>'.$pack['Dimensions']['Length'].'</Length>  
								                <Width>'.$pack['Dimensions']['Width'].'</Width>  
								                <Height>'.$pack['Dimensions']['Height'].'</Height>  
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

                $xml  = '<?xml version="1.0"?>';
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
                $xml .= '		<Name>'. $custom_settings[$key]['ups_ship_name'] .'</Name>';
                $xml .= '		<ShipperNumber>'. $custom_settings[$key]['ups_acc_no'] .'</ShipperNumber>';
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
                $xml .= 	$packages_xml;
                $xml .= '		<RateInformation><NegotiatedRatesIndicator/></RateInformation>';
                $xml .= '	</Shipment>';
                $xml .= '</RatingServiceSelectionRequest>';
                // echo '<pre>';print_r(htmlspecialchars($xml));die();
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
                $xmloutput = !empty($result) ? simplexml_load_string(utf8_encode($result)) : "";

                if($this->config->get('shipping_hitups_auto_rate_error') == '1'){
                    echo '<h3>Package '.$exe.'</h3><br><h4>Request</h4>';
                    // if(isset($xmloutput->Response->Error) && $xmloutput->Response->Error->ErrorSeverity = 'Hard'){
                    // 	print_r("UPS: ");
                    // 	print_r((string)$xmloutput->Response->Error->ErrorDescription);
                    // }
                    echo htmlspecialchars($xml); echo "<br><pre><h4>Response</h4><br>";
                    print_r($xmloutput);
                    if (count($custom_settings) == $exe) {
                        die();
                    }else {
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
                            if ( $this->config->get('shipping_hitups_auto_rate_type') == 'ACCOUNT' && isset( $quote->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue ) )
                            {
                                $isoUPSCurrency = (string) $quote->NegotiatedRates->NetSummaryCharges->GrandTotal->CurrencyCode;
                                $rate_cost = (float) $quote->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue;
                            }
                            else
                            {
                                $isoUPSCurrency = (string) $quote->TotalCharges->CurrencyCode;
                                $rate_cost = (float) $quote->TotalCharges->MonetaryValue;
                            }

                            $rate[$rate_code] = $rate_cost;
                        }
                    }
                    $shipping_rates[$key] = $rate;
                }else {
                    return false;
                }
            }

            $final_rates = [];
            if (!empty($shipping_rates)) {
                foreach ($shipping_rates as $rate_ky => $ship_rate) {
                    if (empty($final_rates)) {
                        $final_rates = $ship_rate;
                    }else {
                        foreach ($final_rates as $f_ky => $f_rate) {
                            if (array_key_exists($f_ky, $ship_rate)) {
                                $final_rates[$f_ky] += $ship_rate[$f_ky];
                            }else {
                                unset($final_rates[$f_ky]);
                            }
                        }
                    }
                }
            }

            // echo '<pre>';print_r($shipping_rates);print_r($final_rates);die();
        }

        if(!empty($final_rates)){
            foreach ($final_rates as $rate_code => $rate_cost) {
                $rate_title = $carriers[$rate_code];
                $quote_data[$rate_code] = array(
                    'code'         => 'hitups_auto.' . $rate_code,
                    'title'        => 'UPS '.$rate_title,
                    'cost'         => $this->currency->convert($rate_cost, $isoUPSCurrency, $this->config->get('config_currency')),
                    'tax_class_id' => '',
                    'text'         => $this->currency->format($this->currency->convert($rate_cost, $isoUPSCurrency, $this->session->data['currency']), $this->session->data['currency'], 1.0000000),
                    'carrier'	   => 'hitups_auto',
                    'service_code' => $rate_code,
                );
            }
        }

        $method_data = array();

        if ($quote_data || $error) {
            $title = $this->language->get('text_title');

            //	if ($this->config->get('shipping_ups_display_weight')) {
            //	$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('shipping_ups_weight_class_id')) . ')';
            //	}

            $method_data = array(
                'code'       => 'ups',
                'title'      => $title,
                'quote'      => $quote_data,
                'sort_order' => $this->config->get('shipping_hitups_auto_sort_order'),
                'error'      => $error
            );
        }
        return $method_data;
    }

    public function hit_ups_is_eu_country ($countrycode, $destinationcode) {
        $eu_countrycodes = array(
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE',
            'ES', 'FI', 'FR', 'GB', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV',
            'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK',
            'HR', 'GR'

        );
        return(in_array($countrycode, $eu_countrycodes) && in_array($destinationcode, $eu_countrycodes));
    }

    public function hit_get_ups_packages($package,$orderCurrency,$chk = false)
    {
        switch ($this->config->get('shipping_hitups_auto_packing_type')) {
            case 'weight_based' :
                return $this->weight_based_shipping($package,$orderCurrency,$chk);
                break;
            case 'per_item' :
            default :
                return $this->per_item_shipping($package,$orderCurrency,$chk);
                break;
        }
    }

    public function weight_based_shipping($package,$orderCurrency,$chk='')
    {
        $maximum_weight = ($this->config->get('shipping_hitups_auto_wight_b') !='') ? $this->config->get('shipping_hitups_auto_wight_b') : '50';

        if ( ! class_exists( 'WeightPack' ) ) {
            include_once 'class-hit-weight-packing.php';
        }

        $weight_pack=new WeightPack('simple');
        $weight_pack->set_max_weight($maximum_weight);

        $package_total_weight = 0;
        $insured_value = 0;
        $ctr = 0;
        foreach ($package as $item_id => $values) {
            $ctr++;
            if (!$values['weight']) {
                $values['weight'] = 0.01;
            }

            $chk_qty = $values['quantity'];

            $prod_weight = $values['weight'];

            $weight_pack->add_item($prod_weight, $values, $chk_qty);
        }

        $pack   =   $weight_pack->pack_items();
        $errors =   $pack->get_errors();
        if( !empty($errors) ){
            //do nothing
            return;
        } else {
            $boxes    =   $pack->get_packed_boxes();
            $unpacked_items =   $pack->get_unpacked_items();
            $insured_value        =   0;

            $packages      =   array_merge( $boxes, $unpacked_items ); // merge items if unpacked are allowed
            $package_count  =   sizeof($packages);
            // get all items to pass if item info in box is not distinguished
            $packable_items =   $weight_pack->get_packable_items();
            $all_items    =   array();
            if(is_array($packable_items)){
                foreach($packable_items as $packable_item){
                    $all_items[]    =   $packable_item['data'];
                }
            }
            //pre($packable_items);
            $order_total = '';

            $to_ship  = array();
            $group_id = 1;
            foreach($packages as $package){//pre($package);
                $packed_products = array();

                if(($package_count  ==  1) && isset($order_total)){
                    $insured_value  =   $values['price'] * $chk_qty;
                }else{
                    $insured_value  =   0;
                    if(!empty($package['items'])){
                        foreach($package['items'] as $item){

                            $insured_value        =   $insured_value; //+ $item->price;
                        }
                    }else{
                        if( isset($order_total) && $package_count){
                            $insured_value  =   $order_total/$package_count;
                        }
                    }
                }
                $packed_products    =   isset($package['items']) ? $package['items'] : $all_items;
                // Creating package request
                $package_total_weight   = $package['weight'];

                $insurance_array = array(
                    'Amount' => $insured_value,
                    'Currency' => $orderCurrency
                );

                $group = array(
                    'GroupNumber' => $group_id,
                    'GroupPackageCount' => 1,
                    'Weight' => array(
                        'Value' => round($package_total_weight, 3),
                        'Units' => 'KG'
                    ),
                    'packed_products' => $packed_products,
                );
                $group['InsuredValue'] = $insurance_array;
                $group['packtype'] = 'OD';

                $to_ship[] = $group;
                $group_id++;
            }
        }
        return $to_ship;
    }

    private function per_item_shipping($package,$orderCurrency,$chk = false) {
        $to_ship = array();
        $group_id = 1;

        // Get weight of order
        foreach ($package as $item_id => $values) {


            if (!$values['weight']) {
                //   $this->debug(sprintf(__('Product # is missing weight. Aborting.', 'wf-shipping-ups'), $item_id), 'error');
                return;
            }

            $group = array();
            $insurance_array = array(
                'Amount' => round($values['price']),
                'Currency' => $orderCurrency
            );

            if($values['weight'] < 0.001){
                $ups_per_item_weight = 0.001;
            }else{
                $ups_per_item_weight = round($values['weight'], 3);
            }
            $group = array(
                'GroupNumber' => $group_id,
                'GroupPackageCount' => 1,
                'Weight' => array(
                    'Value' => $ups_per_item_weight,
                    'Units' => ($this->config->get('shipping_hitups_auto_weight') == true) ? 'LBS' : 'KG'
                ),
                'packed_products' => $package
            );

            if ($values['width'] && $values['height'] && $values['length']) {

                $group['Dimensions'] = array(
                    'Length' => max(1, round($values['length'],3)),
                    'Width' => max(1, round($values['width'],3)),
                    'Height' => max(1, round($values['height'],3)),
                    'Units' => ($this->config->get('shipping_hitups_auto_weight') == true) ? 'IN' : 'CM'
                );
            }
            $group['packtype'] = $this->config->get('shipping_hitups_auto_per_item');
            $group['InsuredValue'] = $insurance_array;

            $chk_qty = $chk ? $values['quantity'] : $values['quantity'];

            for ($i = 0; $i < $chk_qty; $i++)
                $to_ship[] = $group;

            $group_id++;
        }

        return $to_ship;
    }
    private function hit_get_postcode_city($country, $city, $postcode) {
        $no_postcode_country = array('AE', 'AF', 'AG', 'AI', 'AL', 'AN', 'AO', 'AW', 'BB', 'BF', 'BH', 'BI', 'BJ', 'BM', 'BO', 'BS', 'BT', 'BW', 'BZ', 'CD', 'CF', 'CG', 'CI', 'CK',
            'CL', 'CM', 'CO', 'CR', 'CV', 'DJ', 'DM', 'DO', 'EC', 'EG', 'ER', 'ET', 'FJ', 'FK', 'GA', 'GD', 'GH', 'GI', 'GM', 'GN', 'GQ', 'GT', 'GW', 'GY', 'HK', 'HN', 'HT', 'IE', 'IQ', 'IR',
            'JM', 'JO', 'KE', 'KH', 'KI', 'KM', 'KN', 'KP', 'KW', 'KY', 'LA', 'LB', 'LC', 'LK', 'LR', 'LS', 'LY', 'ML', 'MM', 'MO', 'MR', 'MS', 'MT', 'MU', 'MW', 'MZ', 'NA', 'NE', 'NG', 'NI',
            'NP', 'NR', 'NU', 'OM', 'PA', 'PE', 'PF', 'PY', 'QA', 'RW', 'SA', 'SB', 'SC', 'SD', 'SL', 'SN', 'SO', 'SR', 'SS', 'ST', 'SV', 'SY', 'TC', 'TD', 'TG', 'TL', 'TO', 'TT', 'TV', 'TZ',
            'UG', 'UY', 'VC', 'VE', 'VG', 'VN', 'VU', 'WS', 'XA', 'XB', 'XC', 'XE', 'XL', 'XM', 'XN', 'XS', 'YE', 'ZM', 'ZW');

        $postcode_city = !in_array( $country, $no_postcode_country ) ? $postcode_city = "<Postalcode>{$postcode}</Postalcode>" : '';
        if( !empty($city) ){
            $postcode_city .= "<City>{$city}</City>";
        }
        return $postcode_city;
    }
    private function hit_get_package_piece($ups_packages) {
        $pieces = 0;
        if ($ups_packages) {
            foreach ($ups_packages as $key => $parcel) {
                $package_total_weight   =(string) $parcel['Weight']['Value'];
                $package_total_weight   = str_replace(',','.',$package_total_weight);
                if($package_total_weight<0.001){
                    $package_total_weight = 0.001;
                }else{
                    $package_total_weight = round((float)$package_total_weight,3);
                }
                $pieces += $package_total_weight;
            }
        }
        return $pieces;
    }
    private function hit_get_pack_type($selected) {
        $pack_type = 'BOX';
        if ($selected == 'FLY') {
            $pack_type = 'FLY';
        }
        return $pack_type;
    }
    public function hit_get_currency()
    {

        $value = array();
        $value['AD'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['AE'] = array('region' => 'AP', 'currency' =>'AED', 'weight' => 'KG_CM');
        $value['AF'] = array('region' => 'AP', 'currency' =>'AFN', 'weight' => 'KG_CM');
        $value['AG'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
        $value['AI'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
        $value['AL'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['AM'] = array('region' => 'AP', 'currency' =>'AMD', 'weight' => 'KG_CM');
        $value['AN'] = array('region' => 'AM', 'currency' =>'ANG', 'weight' => 'KG_CM');
        $value['AO'] = array('region' => 'AP', 'currency' =>'AOA', 'weight' => 'KG_CM');
        $value['AR'] = array('region' => 'AM', 'currency' =>'ARS', 'weight' => 'KG_CM');
        $value['AS'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
        $value['AT'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['AU'] = array('region' => 'AP', 'currency' =>'AUD', 'weight' => 'KG_CM');
        $value['AW'] = array('region' => 'AM', 'currency' =>'AWG', 'weight' => 'LB_IN');
        $value['AZ'] = array('region' => 'AM', 'currency' =>'AZN', 'weight' => 'KG_CM');
        $value['AZ'] = array('region' => 'AM', 'currency' =>'AZN', 'weight' => 'KG_CM');
        $value['GB'] = array('region' => 'EU', 'currency' =>'GBP', 'weight' => 'KG_CM');
        $value['BA'] = array('region' => 'AP', 'currency' =>'BAM', 'weight' => 'KG_CM');
        $value['BB'] = array('region' => 'AM', 'currency' =>'BBD', 'weight' => 'LB_IN');
        $value['BD'] = array('region' => 'AP', 'currency' =>'BDT', 'weight' => 'KG_CM');
        $value['BE'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['BF'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
        $value['BG'] = array('region' => 'EU', 'currency' =>'BGN', 'weight' => 'KG_CM');
        $value['BH'] = array('region' => 'AP', 'currency' =>'BHD', 'weight' => 'KG_CM');
        $value['BI'] = array('region' => 'AP', 'currency' =>'BIF', 'weight' => 'KG_CM');
        $value['BJ'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
        $value['BM'] = array('region' => 'AM', 'currency' =>'BMD', 'weight' => 'LB_IN');
        $value['BN'] = array('region' => 'AP', 'currency' =>'BND', 'weight' => 'KG_CM');
        $value['BO'] = array('region' => 'AM', 'currency' =>'BOB', 'weight' => 'KG_CM');
        $value['BR'] = array('region' => 'AM', 'currency' =>'BRL', 'weight' => 'KG_CM');
        $value['BS'] = array('region' => 'AM', 'currency' =>'BSD', 'weight' => 'LB_IN');
        $value['BT'] = array('region' => 'AP', 'currency' =>'BTN', 'weight' => 'KG_CM');
        $value['BW'] = array('region' => 'AP', 'currency' =>'BWP', 'weight' => 'KG_CM');
        $value['BY'] = array('region' => 'AP', 'currency' =>'BYR', 'weight' => 'KG_CM');
        $value['BZ'] = array('region' => 'AM', 'currency' =>'BZD', 'weight' => 'KG_CM');
        $value['CA'] = array('region' => 'AM', 'currency' =>'CAD', 'weight' => 'LB_IN');
        $value['CF'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
        $value['CG'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
        $value['CH'] = array('region' => 'EU', 'currency' =>'CHF', 'weight' => 'KG_CM');
        $value['CI'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
        $value['CK'] = array('region' => 'AP', 'currency' =>'NZD', 'weight' => 'KG_CM');
        $value['CL'] = array('region' => 'AM', 'currency' =>'CLP', 'weight' => 'KG_CM');
        $value['CM'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
        $value['CN'] = array('region' => 'AP', 'currency' =>'CNY', 'weight' => 'KG_CM');
        $value['CO'] = array('region' => 'AM', 'currency' =>'COP', 'weight' => 'KG_CM');
        $value['CR'] = array('region' => 'AM', 'currency' =>'CRC', 'weight' => 'KG_CM');
        $value['CU'] = array('region' => 'AM', 'currency' =>'CUC', 'weight' => 'KG_CM');
        $value['CV'] = array('region' => 'AP', 'currency' =>'CVE', 'weight' => 'KG_CM');
        $value['CY'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['CZ'] = array('region' => 'EU', 'currency' =>'CZF', 'weight' => 'KG_CM');
        $value['DE'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['DJ'] = array('region' => 'EU', 'currency' =>'DJF', 'weight' => 'KG_CM');
        $value['DK'] = array('region' => 'AM', 'currency' =>'DKK', 'weight' => 'KG_CM');
        $value['DM'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
        $value['DO'] = array('region' => 'AP', 'currency' =>'DOP', 'weight' => 'LB_IN');
        $value['DZ'] = array('region' => 'AM', 'currency' =>'DZD', 'weight' => 'KG_CM');
        $value['EC'] = array('region' => 'EU', 'currency' =>'USD', 'weight' => 'KG_CM');
        $value['EE'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['EG'] = array('region' => 'AP', 'currency' =>'EGP', 'weight' => 'KG_CM');
        $value['ER'] = array('region' => 'EU', 'currency' =>'ERN', 'weight' => 'KG_CM');
        $value['ES'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['ET'] = array('region' => 'AU', 'currency' =>'ETB', 'weight' => 'KG_CM');
        $value['FI'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['FJ'] = array('region' => 'AP', 'currency' =>'FJD', 'weight' => 'KG_CM');
        $value['FK'] = array('region' => 'AM', 'currency' =>'GBP', 'weight' => 'KG_CM');
        $value['FM'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
        $value['FO'] = array('region' => 'AM', 'currency' =>'DKK', 'weight' => 'KG_CM');
        $value['FR'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['GA'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
        $value['GB'] = array('region' => 'EU', 'currency' =>'GBP', 'weight' => 'KG_CM');
        $value['GD'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
        $value['GE'] = array('region' => 'AM', 'currency' =>'GEL', 'weight' => 'KG_CM');
        $value['GF'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['GG'] = array('region' => 'AM', 'currency' =>'GBP', 'weight' => 'KG_CM');
        $value['GH'] = array('region' => 'AP', 'currency' =>'GBS', 'weight' => 'KG_CM');
        $value['GI'] = array('region' => 'AM', 'currency' =>'GBP', 'weight' => 'KG_CM');
        $value['GL'] = array('region' => 'AM', 'currency' =>'DKK', 'weight' => 'KG_CM');
        $value['GM'] = array('region' => 'AP', 'currency' =>'GMD', 'weight' => 'KG_CM');
        $value['GN'] = array('region' => 'AP', 'currency' =>'GNF', 'weight' => 'KG_CM');
        $value['GP'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['GQ'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
        $value['GR'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['GT'] = array('region' => 'AM', 'currency' =>'GTQ', 'weight' => 'KG_CM');
        $value['GU'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
        $value['GW'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
        $value['GY'] = array('region' => 'AP', 'currency' =>'GYD', 'weight' => 'LB_IN');
        $value['HK'] = array('region' => 'AM', 'currency' =>'HKD', 'weight' => 'KG_CM');
        $value['HN'] = array('region' => 'AM', 'currency' =>'HNL', 'weight' => 'KG_CM');
        $value['HR'] = array('region' => 'AP', 'currency' =>'HRK', 'weight' => 'KG_CM');
        $value['HT'] = array('region' => 'AM', 'currency' =>'HTG', 'weight' => 'LB_IN');
        $value['HU'] = array('region' => 'EU', 'currency' =>'HUF', 'weight' => 'KG_CM');
        $value['IC'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['ID'] = array('region' => 'AP', 'currency' =>'IDR', 'weight' => 'KG_CM');
        $value['IE'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['IL'] = array('region' => 'AP', 'currency' =>'ILS', 'weight' => 'KG_CM');
        $value['IN'] = array('region' => 'AP', 'currency' =>'INR', 'weight' => 'KG_CM');
        $value['IQ'] = array('region' => 'AP', 'currency' =>'IQD', 'weight' => 'KG_CM');
        $value['IR'] = array('region' => 'AP', 'currency' =>'IRR', 'weight' => 'KG_CM');
        $value['IS'] = array('region' => 'EU', 'currency' =>'ISK', 'weight' => 'KG_CM');
        $value['IT'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['JE'] = array('region' => 'AM', 'currency' =>'GBP', 'weight' => 'KG_CM');
        $value['JM'] = array('region' => 'AM', 'currency' =>'JMD', 'weight' => 'KG_CM');
        $value['JO'] = array('region' => 'AP', 'currency' =>'JOD', 'weight' => 'KG_CM');
        $value['JP'] = array('region' => 'AP', 'currency' =>'JPY', 'weight' => 'KG_CM');
        $value['KE'] = array('region' => 'AP', 'currency' =>'KES', 'weight' => 'KG_CM');
        $value['KG'] = array('region' => 'AP', 'currency' =>'KGS', 'weight' => 'KG_CM');
        $value['KH'] = array('region' => 'AP', 'currency' =>'KHR', 'weight' => 'KG_CM');
        $value['KI'] = array('region' => 'AP', 'currency' =>'AUD', 'weight' => 'KG_CM');
        $value['KM'] = array('region' => 'AP', 'currency' =>'KMF', 'weight' => 'KG_CM');
        $value['KN'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
        $value['KP'] = array('region' => 'AP', 'currency' =>'KPW', 'weight' => 'LB_IN');
        $value['KR'] = array('region' => 'AP', 'currency' =>'KRW', 'weight' => 'KG_CM');
        $value['KV'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['KW'] = array('region' => 'AP', 'currency' =>'KWD', 'weight' => 'KG_CM');
        $value['KY'] = array('region' => 'AM', 'currency' =>'KYD', 'weight' => 'KG_CM');
        $value['KZ'] = array('region' => 'AP', 'currency' =>'KZF', 'weight' => 'LB_IN');
        $value['LA'] = array('region' => 'AP', 'currency' =>'LAK', 'weight' => 'KG_CM');
        $value['LB'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
        $value['LC'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'KG_CM');
        $value['LI'] = array('region' => 'AM', 'currency' =>'CHF', 'weight' => 'LB_IN');
        $value['LK'] = array('region' => 'AP', 'currency' =>'LKR', 'weight' => 'KG_CM');
        $value['LR'] = array('region' => 'AP', 'currency' =>'LRD', 'weight' => 'KG_CM');
        $value['LS'] = array('region' => 'AP', 'currency' =>'LSL', 'weight' => 'KG_CM');
        $value['LT'] = array('region' => 'EU', 'currency' =>'LTL', 'weight' => 'KG_CM');
        $value['LU'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['LV'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['LY'] = array('region' => 'AP', 'currency' =>'LYD', 'weight' => 'KG_CM');
        $value['MA'] = array('region' => 'AP', 'currency' =>'MAD', 'weight' => 'KG_CM');
        $value['MC'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['MD'] = array('region' => 'AP', 'currency' =>'MDL', 'weight' => 'KG_CM');
        $value['ME'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['MG'] = array('region' => 'AP', 'currency' =>'MGA', 'weight' => 'KG_CM');
        $value['MH'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
        $value['MK'] = array('region' => 'AP', 'currency' =>'MKD', 'weight' => 'KG_CM');
        $value['ML'] = array('region' => 'AP', 'currency' =>'COF', 'weight' => 'KG_CM');
        $value['MM'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
        $value['MN'] = array('region' => 'AP', 'currency' =>'MNT', 'weight' => 'KG_CM');
        $value['MO'] = array('region' => 'AP', 'currency' =>'MOP', 'weight' => 'KG_CM');
        $value['MP'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
        $value['MQ'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['MR'] = array('region' => 'AP', 'currency' =>'MRO', 'weight' => 'KG_CM');
        $value['MS'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
        $value['MT'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['MU'] = array('region' => 'AP', 'currency' =>'MUR', 'weight' => 'KG_CM');
        $value['MV'] = array('region' => 'AP', 'currency' =>'MVR', 'weight' => 'KG_CM');
        $value['MW'] = array('region' => 'AP', 'currency' =>'MWK', 'weight' => 'KG_CM');
        $value['MX'] = array('region' => 'AM', 'currency' =>'MXN', 'weight' => 'KG_CM');
        $value['MY'] = array('region' => 'AP', 'currency' =>'MYR', 'weight' => 'KG_CM');
        $value['MZ'] = array('region' => 'AP', 'currency' =>'MZN', 'weight' => 'KG_CM');
        $value['NA'] = array('region' => 'AP', 'currency' =>'NAD', 'weight' => 'KG_CM');
        $value['NC'] = array('region' => 'AP', 'currency' =>'XPF', 'weight' => 'KG_CM');
        $value['NE'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
        $value['NG'] = array('region' => 'AP', 'currency' =>'NGN', 'weight' => 'KG_CM');
        $value['NI'] = array('region' => 'AM', 'currency' =>'NIO', 'weight' => 'KG_CM');
        $value['NL'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['NO'] = array('region' => 'EU', 'currency' =>'NOK', 'weight' => 'KG_CM');
        $value['NP'] = array('region' => 'AP', 'currency' =>'NPR', 'weight' => 'KG_CM');
        $value['NR'] = array('region' => 'AP', 'currency' =>'AUD', 'weight' => 'KG_CM');
        $value['NU'] = array('region' => 'AP', 'currency' =>'NZD', 'weight' => 'KG_CM');
        $value['NZ'] = array('region' => 'AP', 'currency' =>'NZD', 'weight' => 'KG_CM');
        $value['OM'] = array('region' => 'AP', 'currency' =>'OMR', 'weight' => 'KG_CM');
        $value['PA'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'KG_CM');
        $value['PE'] = array('region' => 'AM', 'currency' =>'PEN', 'weight' => 'KG_CM');
        $value['PF'] = array('region' => 'AP', 'currency' =>'XPF', 'weight' => 'KG_CM');
        $value['PG'] = array('region' => 'AP', 'currency' =>'PGK', 'weight' => 'KG_CM');
        $value['PH'] = array('region' => 'AP', 'currency' =>'PHP', 'weight' => 'KG_CM');
        $value['PK'] = array('region' => 'AP', 'currency' =>'PKR', 'weight' => 'KG_CM');
        $value['PL'] = array('region' => 'EU', 'currency' =>'PLN', 'weight' => 'KG_CM');
        $value['PR'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
        $value['PT'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['PW'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'KG_CM');
        $value['PY'] = array('region' => 'AM', 'currency' =>'PYG', 'weight' => 'KG_CM');
        $value['QA'] = array('region' => 'AP', 'currency' =>'QAR', 'weight' => 'KG_CM');
        $value['RE'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['RO'] = array('region' => 'EU', 'currency' =>'RON', 'weight' => 'KG_CM');
        $value['RS'] = array('region' => 'AP', 'currency' =>'RSD', 'weight' => 'KG_CM');
        $value['RU'] = array('region' => 'AP', 'currency' =>'RUB', 'weight' => 'KG_CM');
        $value['RW'] = array('region' => 'AP', 'currency' =>'RWF', 'weight' => 'KG_CM');
        $value['SA'] = array('region' => 'AP', 'currency' =>'SAR', 'weight' => 'KG_CM');
        $value['SB'] = array('region' => 'AP', 'currency' =>'SBD', 'weight' => 'KG_CM');
        $value['SC'] = array('region' => 'AP', 'currency' =>'SCR', 'weight' => 'KG_CM');
        $value['SD'] = array('region' => 'AP', 'currency' =>'SDG', 'weight' => 'KG_CM');
        $value['SE'] = array('region' => 'EU', 'currency' =>'SEK', 'weight' => 'KG_CM');
        $value['SG'] = array('region' => 'AP', 'currency' =>'SGD', 'weight' => 'KG_CM');
        $value['SH'] = array('region' => 'AP', 'currency' =>'SHP', 'weight' => 'KG_CM');
        $value['SI'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['SK'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['SL'] = array('region' => 'AP', 'currency' =>'SLL', 'weight' => 'KG_CM');
        $value['SM'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['SN'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
        $value['SO'] = array('region' => 'AM', 'currency' =>'SOS', 'weight' => 'KG_CM');
        $value['SR'] = array('region' => 'AM', 'currency' =>'SRD', 'weight' => 'KG_CM');
        $value['SS'] = array('region' => 'AP', 'currency' =>'SSP', 'weight' => 'KG_CM');
        $value['ST'] = array('region' => 'AP', 'currency' =>'STD', 'weight' => 'KG_CM');
        $value['SV'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'KG_CM');
        $value['SY'] = array('region' => 'AP', 'currency' =>'SYP', 'weight' => 'KG_CM');
        $value['SZ'] = array('region' => 'AP', 'currency' =>'SZL', 'weight' => 'KG_CM');
        $value['TC'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
        $value['TD'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
        $value['TG'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
        $value['TH'] = array('region' => 'AP', 'currency' =>'THB', 'weight' => 'KG_CM');
        $value['TJ'] = array('region' => 'AP', 'currency' =>'TJS', 'weight' => 'KG_CM');
        $value['TL'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
        $value['TN'] = array('region' => 'AP', 'currency' =>'TND', 'weight' => 'KG_CM');
        $value['TO'] = array('region' => 'AP', 'currency' =>'TOP', 'weight' => 'KG_CM');
        $value['TR'] = array('region' => 'AP', 'currency' =>'TRY', 'weight' => 'KG_CM');
        $value['TT'] = array('region' => 'AM', 'currency' =>'TTD', 'weight' => 'LB_IN');
        $value['TV'] = array('region' => 'AP', 'currency' =>'AUD', 'weight' => 'KG_CM');
        $value['TW'] = array('region' => 'AP', 'currency' =>'TWD', 'weight' => 'KG_CM');
        $value['TZ'] = array('region' => 'AP', 'currency' =>'TZS', 'weight' => 'KG_CM');
        $value['UA'] = array('region' => 'AP', 'currency' =>'UAH', 'weight' => 'KG_CM');
        $value['UG'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
        $value['US'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
        $value['UY'] = array('region' => 'AM', 'currency' =>'UYU', 'weight' => 'KG_CM');
        $value['UZ'] = array('region' => 'AP', 'currency' =>'UZS', 'weight' => 'KG_CM');
        $value['VC'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
        $value['VE'] = array('region' => 'AM', 'currency' =>'VEF', 'weight' => 'KG_CM');
        $value['VG'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
        $value['VI'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
        $value['VN'] = array('region' => 'AP', 'currency' =>'VND', 'weight' => 'KG_CM');
        $value['VU'] = array('region' => 'AP', 'currency' =>'VUV', 'weight' => 'KG_CM');
        $value['WS'] = array('region' => 'AP', 'currency' =>'WST', 'weight' => 'KG_CM');
        $value['XB'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'LB_IN');
        $value['XC'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'LB_IN');
        $value['XE'] = array('region' => 'AM', 'currency' =>'ANG', 'weight' => 'LB_IN');
        $value['XM'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'LB_IN');
        $value['XN'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
        $value['XS'] = array('region' => 'AP', 'currency' =>'SIS', 'weight' => 'KG_CM');
        $value['XY'] = array('region' => 'AM', 'currency' =>'ANG', 'weight' => 'LB_IN');
        $value['YE'] = array('region' => 'AP', 'currency' =>'YER', 'weight' => 'KG_CM');
        $value['YT'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
        $value['ZA'] = array('region' => 'AP', 'currency' =>'ZAR', 'weight' => 'KG_CM');
        $value['ZM'] = array('region' => 'AP', 'currency' =>'ZMW', 'weight' => 'KG_CM');
        $value['ZW'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');

        return $value;
    }

    public function getDefaultVenConfig($vendor_id = "default")
    {
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