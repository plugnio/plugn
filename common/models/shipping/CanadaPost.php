<?php

namespace common\models\shipping;

class CanadaPost {
    public function getQuote($address) {

        $this->load->language('extension/shipping/hitshipo_cpost');

        if($this->config->get('shipping_hitshipo_cpost_realtime_rates') == true)
        {
            $status = true;
        }
        else
        {
            $status = false;
        }

        $error = '';

        $quote_data = array();

        if ($status) {

            $username = $this->config->get('shipping_hitshipo_cpost_username');
            $password = $this->config->get('shipping_hitshipo_cpost_password');
            $cus_num = $this->config->get('shipping_hitshipo_cpost_customer_num');
            $con_id = $this->config->get('shipping_hitshipo_cpost_contract_id');
            $shipper_post_code = $this->config->get('shipping_hitshipo_cpost_postcode');
            // $shipper_country_code = $this->config->get('shipping_hitshipo_cpost_country_code');
            $all_currency = $this->get_currency();
            // $shipper_country_currency = $all_currency[$shipper_country_code]['currency'];
            $destination_country_code = $address['iso_code_2'];
            $destination_post_code = $address['postcode'];
            $destination_country_currency = $all_currency[$destination_country_code]['currency'];
            $selected_services = $this->config->get('shipping_hitshipo_cpost_service');

            $all_cart_products = $this->cart->getProducts();
            $cart_products_total_weight = 0;
            foreach ($all_cart_products as $value) {
                $cart_products_total_weight += $value['weight'];
            }

            $con_id_xml = "";

            if (!empty($con_id)) {
                $con_id_xml = "<contract-id>".$con_id."</contract-id>";
            }

// echo "<pre>";
// print_r($address);
// die();
            $xml_request = '<?xml version="1.0" encoding="UTF-8"?>
					<mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate-v4">
					<customer-number>'.$cus_num.'</customer-number>
					'.$con_id_xml.'
					<parcel-characteristics><weight>'.$cart_products_total_weight.'</weight></parcel-characteristics>
					<origin-postal-code>'.$shipper_post_code.'</origin-postal-code>';

            if ($destination_country_code == 'CA') {
                $xml_request .=	'<destination>
						<domestic>
						<postal-code>'.str_replace(' ', '', strtoupper($destination_post_code)).'</postal-code>
						</domestic>
						</destination>
						</mailing-scenario>';
            } elseif ($destination_country_code == 'US') {
                $xml_request .=	'<destination>
						<united-states>
						<zip-code>'.str_replace(' ', '', strtoupper($destination_post_code)).'</zip-code>
						</united-states>
						</destination>
						</mailing-scenario>';
            } else {
                $xml_request .=	'<destination>
						<international>
						<country-code>'.$destination_country_code.'</country-code>
						</international>
						</destination>
						</mailing-scenario>';
            }

            if (!$this->config->get('shipping_hitshipo_cpost_test')) {
                $url = 'https://soa-gw.canadapost.ca/rs/ship/price/';
            } else {
                $url = 'https://ct.soa-gw.canadapost.ca/rs/ship/price/';
            }

            $curl = curl_init($url); // Create REST Request
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $xml_request);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, $username . ':' . $password);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/vnd.cpc.ship.rate-v4+xml', 'Accept: application/vnd.cpc.ship.rate-v4+xml'));

            $result = utf8_encode(curl_exec($curl));

            $xml = '';
            libxml_use_internal_errors(true);
            if(!empty($result))
            {
                $xml = simplexml_load_string(utf8_encode($result));
            }
            $result = $xml;
            if($this->config->get('shipping_hitshipo_cpost_front_end_logs') == true)
            {
                echo "<pre>";
                print_r(htmlspecialchars($xml_request));
                print_r($result);
                // print_r($xml);
                die();
            }

            if ($result && !empty($result->{'price-quote'})) {
                foreach ($result->{'price-quote'} as $quote) {
                    $ship_service_code = str_replace('.', '_', $quote->{'service-code'});
                    $ship_service_name = $quote->{'service-name'};
                    // $ship_delivery_date = $quote->{'service-standard'}->{'expected-delivery-date'};

                    if($this->config->get('shipping_hitshipo_cpost_rate_tax') == true){
                        $ship_rate = $quote->{'price-details'}->due;
                    } else {
                        $ship_rate = $quote->{'price-details'}->base;
                    }
                    if($this->session->data['currency'] != 'CAD'){
                        $ship_rate = $this->currency->convert($ship_rate, 'CAD', $this->session->data['currency']);
                    }
                    if (in_array($ship_service_code, $selected_services)) {
                        $quote_data[$ship_service_code] = array(
                            'code'         => 'hitshipo_cpost.' . $ship_service_code,
                            'title'        => 'Automated Canada Post: '.$ship_service_name,
                            'cost'         => json_decode($ship_rate, true),
                            'tax_class_id' => '',
                            'text'  => $this->currency->format($ship_rate, $this->session->data['currency'], 1.0000000),
                            'service_code' => $ship_service_code,
                            'mod_name'		=> 'hitshippo_cpost'
                        );
                    }
                }
            }

        }
        $method_data = array();

        if ($quote_data || $error) {
            $title = $this->language->get('text_title');
            $method_data = array(
                'code'       => 'canada',
                'title'      => $title,
                'quote'      => $quote_data,
                //'sort_order' => $this->config->get('shipping_hitshipo_cpost_sort_order'),
                'error'      => $error
            );
        }
        // echo '<pre>';print_r($method_data);die();
        return $method_data;
    }
}
