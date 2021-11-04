<?php

namespace agent\modules\v1\controllers;

use agent\models\Currency;
use common\models\Restaurant;
use common\models\RestaurantCurrency;
use yii\rest\Controller;


class CurrencyController extends Controller {

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

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'list'];

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
     * Get all currency data
     * @return type
     */
    public function actionList() {

        $keyword = Yii::$app->request->get('keyword');
        $page = Yii::$app->request->get('page');

        $query =  Currency::find();

        if ($keyword) {
            $query->andWhere(['like', 'title', $keyword]);
            $query->orWhere(['like', 'code', $keyword]);
        }

        if($page == -1) {
            return new ActiveDataProvider([
                'query' => $query,
                'pagination' => false
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * return store currency data
     * @return type
     */
    public function actionStoreCurrencies() {

        $keyword = Yii::$app->request->get('keyword');
        $page = Yii::$app->request->get('page');

        $store = Yii::$app->accountManager->getManagedAccount();

        $query =  $store->getCurrencies();

        if ($keyword) {
            $query->andWhere(['like', 'title', $keyword]);
            $query->orWhere(['like', 'code', $keyword]);
        }

        if($page == -1) {
            return new ActiveDataProvider([
                'query' => $query,
                'pagination' => false
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * update store currencies
     */
    public function actionUpdate()
    {
        $currencies = Yii::$app->request->getBodyParam('currencies');
        $currency_id = Yii::$app->request->getBodyParam('currency_id');

        $store = Yii::$app->accountManager->getManagedAccount();

        //should have default store currency

        if(!in_array ($currency_id, $currencies))
        {
            return [
                'operation' => 'error',
                'message' => Yii::t('agent', 'Missing default currency in store currencies'),
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();

        //update store currency

        $store->setScenario(Restaurant::SCENARIO_CURRENCY);

        $store->currency_id = $currency_id;

        if(!$store->save())
        {
            $transaction->revert();

            return [
                'operation' => 'error',
                'message' => $store->errors
            ];
        }

        RestaurantCurrency::deleteAll ([
            'restaurant_uuid' => $store->restaurant_uuid
        ]);

        foreach($currencies as $currency)
        {
            $rc = new RestaurantCurrency;
            $rc->restaurant_uuid = $store->restaurant_uuid;
            $rc->currency_id = $currency;

            if(!$rc->save()) {

                $transaction->revert();

                return [
                    'operation' => 'error',
                    'message' => $rc->errors
                ];
            }
        }

        $transaction->commit();

        return [
            'operation' => 'success',
            'message' => Yii::t('agent', 'Currencies updated successfully'),
        ];
    }
}