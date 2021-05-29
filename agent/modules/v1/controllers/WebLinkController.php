<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\models\WebLink;

class WebLinkController extends Controller {

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
              $behaviors['authenticator']['except'] = ['options'];

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
    * Get all web links
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionList($store_uuid) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $webLinks =  WebLink::find()
                    ->where(['restaurant_uuid' => $store_uuid]);

          return new ActiveDataProvider([
              'query' => $webLinks
          ]);
      }

    }




      /**
      * Return a List of Voucher by keyword
      */
      public function actionFilter($store_uuid)
      {
        if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $keyword = Yii::$app->request->get('keyword');

          $query =  WebLink::find();

          if($keyword) {
                $query->where(['like', 'url', $keyword]);
                $query->orWhere(['like', 'web_link_title', $keyword]);
                $query->orWhere(['like', 'web_link_title_ar', $keyword]);
          }

          $query->andWhere(['restaurant_uuid' => $store_uuid]);

          return new ActiveDataProvider([
              'query' => $query
          ]);

        }
      }




    /**
    * Return web link detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($store_uuid, $web_link_id) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

        $webLink =  WebLink::find()
                  ->where(['restaurant_uuid' => $store_uuid])
                  ->andWhere(['web_link_id' => $web_link_id])
                  ->one();


          return $webLink;

      }

  }


}
