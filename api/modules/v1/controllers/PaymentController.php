<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\RestaurantPaymentMethod;
use common\models\PaymentMethod;
use api\models\Payment;
use api\models\Restaurant;

class PaymentController extends BaseController {

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
     *  Return Payment details
     */
    // public function actionPaymentDetail($id) {
    //
    //     $model = Payment::find()->where(['payment_uuid' => $id])->with('order')->asArray()->one();
    //
    //     return $model;
    // }
    //


    /**
     * return a list of payments method that restaurant's owner added on agent dashboard
     */
    public function actionListAllRestaurantsPaymentMethod($id) {

        $model = Restaurant::findOne($id);

        $currency = \Yii::$app->currency->getCode();

        if(!$currency) {
            $currency = $model->currency->code;
        }

        $query = $model->getPaymentMethods()
            ->joinWith('paymentMethodCurrencies')
            ->andWhere(['payment_method_currency.currency' => $currency]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
    }

}
