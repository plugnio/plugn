<?php

namespace agent\modules\v1\controllers;

use Yii;
use agent\models\Currency;
use common\models\Restaurant;
use common\models\RestaurantCurrency;
use yii\data\ActiveDataProvider;


/**
 * CurrencyController implements the CRUD actions for Currency model.
 */
class CurrencyController extends BaseController {

    public function behaviors() {
        $behaviors = parent::behaviors();

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'list'];

        return $behaviors;
    }

    /**
     * only owner will have access
     */
    public function beforeAction($action)
    {
        parent::beforeAction ($action);

        if(in_array ($action->id, ['options', 'list'])) {
            return true;
        }

        if(!Yii::$app->accountManager->isOwner() && !in_array ($action->id, ['store-currencies'])) {
            throw new \yii\web\BadRequestHttpException(
                Yii::t('agent', 'You are not allowed to manage currency. Please contact with store owner')
            );

            return false;
        }

        //should have access to store

        Yii::$app->accountManager->getManagedAccount();

        return true;
    }

    /**
     * Get all currency data
     * @return ActiveDataProvider
     * 
     * @api {get} /currencies Get all currency data
     * @apiName ListCurrencies
     * @apiGroup Currency
     *
     * @apiSuccess {Array} currencies List of currencies.
     */
    public function actionList() {

        //to fix : hide apply button for jobs already applied
        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            Yii::$app->user->loginByAccessToken($matches[1]);
        }

        $keyword = Yii::$app->request->get('keyword');
        $page = Yii::$app->request->get('page');

        $query =  Currency::find();
            //->andWhere(['status' => Currency::STATUS_ACTIVE]);

        if ($keyword) {
            $query->andWhere([
                'OR',
                ['like', 'title', $keyword],
                ['like', 'code', $keyword]
            ]);
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
     * @return ActiveDataProvider
     * 
     * @api {get} /currencies/store-currencies Get store currency data
     * @apiName StoreCurrencies
     * 
     * @apiParam {string} keyword Keyword.
     * @apiParam {string} page Page.
     * 
     * @apiGroup Currency
     *
     * @apiSuccess {Array} currencies List of currencies.
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
     * 
     * @api {post} /currencies Update store currencies
     * @apiName UpdateStoreCurrencies
     * 
     * @apiParam {string} currencies Currencies.
     * @apiParam {string} currency_id Currency ID.
     * 
     * @apiGroup Currency
     *
     * @apiSuccess {string} operation success|error.
     * @apiSuccess {string} message Message.
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

            $props = [
                "currency_active" => $currencies
            ];

            Yii::$app->eventManager->track("Currency Activated", $props, null, $store->restaurant_uuid);

        return [
            'operation' => 'success',
            'message' => Yii::t('agent', 'Currencies updated successfully'),
        ];
    }
}