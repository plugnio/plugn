<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use agent\models\Country;

class CountryController extends BaseController {

    public function behaviors() {
        $behaviors = parent::behaviors();

              // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
              $behaviors['authenticator']['except'] = ['options', 'list'];

              return $behaviors;
    }

    /**
    * Get all countries data
     * @param number $id
     * @param string $store_uuid
     * @return ActiveDataProvider
     * 
     * @api {get} /country Get all countries data
     * @apiName ListCountries
     * @apiParam {string} keyword Keyword.
     * @apiParam {string} page Page.
     * @apiGroup Country
     *
     * @apiSuccess {Array} countries List of countries.
     */
    public function actionList() {

        $keyword = Yii::$app->request->get('keyword');
        $page = Yii::$app->request->get('page');

        //Yii::$app->accountManager->getManagedAccount($store_uuid);

        $query =  Country::find();

        if ($keyword) {
          $query->andWhere(['like', 'country_name', $keyword]);
          $query->orWhere(['like', 'country_name_ar', $keyword]);
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
    * Return Country detail
     * @param string $store_uuid
     * @param string $order_uuid
     * @return Country
     * 
     * @api {get} /country/:id Get country detail
     * @apiName GetCountryDetail
     * 
     * @apiParam {string} country_id Country ID.
     * 
     * @apiGroup Country
     *
     * @apiSuccess {Array} country Country.
     */
    public function actionDetail() {

        $country_id = Yii::$app->request->get('country_id');

      //validate

      Yii::$app->accountManager->getManagedAccount();

      return Country::find()
                  ->andWhere(['country_id' => $country_id])
                  ->with('cities','cities.areas')
                  ->asArray()
                  ->one();
  }
}
