<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use agent\models\Country;

class CountryController extends Controller {

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

        if ($keyword){
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
