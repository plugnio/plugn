<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\web\NotFoundHttpException;

class LocationController extends Controller {

    public function behaviors() {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count'
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }


    public function actionReverseGeocodeing($lat,$lng) {
      $url = 'https://maps.google.com/maps/api/geocode/json?latlng='. $lat . ',' . $lng . '&key=AIzaSyCFeQ-wuP5iWVRTwMn5nZZeOE8yjGESFa8&language=en';

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($url)
                ->addHeaders([
                    'content-type' => 'application/json',
                ])
                ->send();

        if($response->isOk){


          $building = '';
          $block = '';
          $street = '';
          $area = '';
          $country = '';

          foreach ($response->data['results'] as $key => $address) {
            // code...


            if(in_array('neighborhood', $address['types'])){
               foreach ($address['address_components'] as $key => $adress_component) {
                 if(in_array('neighborhood', $adress_component['types'])){
                   $block = $adress_component['long_name'];
                 }
               }
           }

            else if(in_array('route', $address['types'])){
                foreach ($address['address_components'] as $key => $adress_component) {
                  if(in_array('route', $adress_component['types'])){
                    $street = $adress_component['long_name'];
                  }
                }
            }

            else if(in_array('premise', $address['types'])){
                foreach ($address['address_components'] as $key => $adress_component) {
                  if(in_array('premise', $adress_component['types'])){
                    $building = $adress_component['long_name'];
                  }
                }
            }


            else if(in_array('sublocality', $address['types'])){
                foreach ($address['address_components'] as $key => $adress_component) {
                  if(in_array('sublocality', $adress_component['types'])){
                    $area = $adress_component['long_name'];
                  }
                }
            }


            else if(in_array('political', $address['types'])){
                foreach ($address['address_components'] as $key => $adress_component) {
                  if(in_array('political', $adress_component['types'])){
                    $country = $adress_component['long_name'];
                  }
                }
            }




          }

          $address = '';
          if($building)
            $address = $building . ', ';


            $address .= $block . ', ' . $street . ', ' . $area . ', ' . $country;


          return $address;

        } else {
          Yii::error('[ ReverseGeocodeing ]' . json_encode($response) . ' lat: '. $lat . ', lng: '. $lng , __METHOD__);
        }
        return 'Location not found';
    }

}
