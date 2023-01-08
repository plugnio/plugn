<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
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
     * @param type $id
     * @param type $store_uuid
     * @return type
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
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
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
