<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\Order;
use common\models\OrderItem;
use common\models\OrderItemExtraOptions;


class OrderController extends Controller {

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

    /**
     * Place an order
     */
    public function actionCreateAnOrder($id) {

        $order = new Order();
        //TODO if restaurant_uuid not exist
        $order->restaurant_uuid = $id;
        
        //Save Customer Info
        $order->customer_name = Yii::$app->request->getBodyParam("name");
        $order->customer_phone_number = Yii::$app->request->getBodyParam("phone_number");
        $order->customer_email = Yii::$app->request->getBodyParam("email");//optional
        
        //payment method
        $order->payment_method_id = Yii::$app->request->getBodyParam("payment_method_id");
        $order->payment_method_name = Yii::$app->request->getBodyParam("payment_method_name");
        
        //save Customer address
        $order->area_id = Yii::$app->request->getBodyParam("area_id");
        $order->area_name = Yii::$app->request->getBodyParam("area_name");
        $order->area_name_ar = Yii::$app->request->getBodyParam("area_name_ar");
        $order->unit_type = Yii::$app->request->getBodyParam("unit_type");
        $order->block = Yii::$app->request->getBodyParam("block");
        $order->street = Yii::$app->request->getBodyParam("street");
        $order->avenue = Yii::$app->request->getBodyParam("avenue"); //optional
        $order->house_number = Yii::$app->request->getBodyParam("house_number");
        $order->special_directions = Yii::$app->request->getBodyParam("special_directions");//optional
        
        if (!$order->save()) {
            return [
                'operation' => 'error',
                'message' => $order->getErrors()
            ];
        }

        return [
            'operation' => 'success',
            'order_id' => $order->order_id,
            'message' => 'Order created successfully'
        ];

    }

    /**
     * save an item to order_item table as record 
     */
    public function actionAddItemToTheCart($id) {

        $orderItem = new OrderItem();
   
        $orderItem->order_id = (int) $id;
        $orderItem->item_uuid =  Yii::$app->request->getBodyParam("item_uuid");
        $orderItem->item_name = Yii::$app->request->getBodyParam("item_name");
        $orderItem->item_price = (int) Yii::$app->request->getBodyParam("item_price");
        $orderItem->qty = (int) Yii::$app->request->getBodyParam("qty");
        $orderItem->instructions = Yii::$app->request->getBodyParam("instructions");

        
        if (!$orderItem->save()) {
            return [
                'operation' => 'error',
                'message' => $orderItem->getErrors()
            ];
        }

        return [
            'operation' => 'success',
            'order_item_id' => $orderItem->order_item_id
        ];

    }
    
    /**
     * save an extraOption to order_extra_option table as record 
     */
    public function actionAddExtraOptionToTheCart($id) {

        $orderItemExtraOptions = new OrderItemExtraOptions();
   
        $orderItemExtraOptions->order_item_id = (int) $id;
        $orderItemExtraOptions->extra_option_id =  Yii::$app->request->getBodyParam("extra_option_id");
        $orderItemExtraOptions->extra_option_name = Yii::$app->request->getBodyParam("extra_option_name");
        $orderItemExtraOptions->extra_option_name_ar =  Yii::$app->request->getBodyParam("extra_option_name_ar");
        $orderItemExtraOptions->extra_option_price = (int) Yii::$app->request->getBodyParam("extra_option_price");

        
        if (!$orderItemExtraOptions->save()) {
            return [
                'operation' => 'error',
                'message' => $orderItemExtraOptions->getErrors()
            ];
        }

        return [
            'operation' => 'success'
        ];

    }

}
