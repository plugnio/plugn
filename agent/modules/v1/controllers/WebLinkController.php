<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\WebLink;

class WebLinkController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors ();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className (),
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
            'class' => \yii\filters\auth\HttpBearerAuth::className (),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions ();
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
    public function actionList($store_uuid)
    {

        $keyword = Yii::$app->request->get ('keyword');

        Yii::$app->accountManager->getManagedAccount ($store_uuid);


        $query = WebLink::find ();

        if ($keyword) {
            $query->where (['like', 'url', $keyword]);
            $query->orWhere (['like', 'web_link_title', $keyword]);
            $query->orWhere (['like', 'web_link_title_ar', $keyword]);
        }

        $query->andWhere (['restaurant_uuid' => $store_uuid]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }


    /**
     * Create Web Link
     * @return array
     */
    public function actionCreate()
    {

        $store_uuid = Yii::$app->request->getBodyParam ("store_uuid");
        $store_model = Yii::$app->accountManager->getManagedAccount ($store_uuid);

        $model = new WebLink();
        $model->restaurant_uuid = $store_model->restaurant_uuid;
        $model->web_link_type = Yii::$app->request->getBodyParam ("web_link_type");
        $model->url = Yii::$app->request->getBodyParam ("url");
        $model->web_link_title = Yii::$app->request->getBodyParam ("web_link_title");
        $model->web_link_title_ar = Yii::$app->request->getBodyParam ("web_link_title_ar");


        if (!$model->save ()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Web Link created successfully"),
            "model" => WebLink::findOne ($model->web_link_id)
        ];
    }

    /**
     * Update Web Link
     */
    public function actionUpdate($web_link_id, $store_uuid)
    {
        $model = $this->findModel ($web_link_id, $store_uuid);

        $model->web_link_type = Yii::$app->request->getBodyParam ("web_link_type");
        $model->url = Yii::$app->request->getBodyParam ("url");
        $model->web_link_title = Yii::$app->request->getBodyParam ("web_link_title");
        $model->web_link_title_ar = Yii::$app->request->getBodyParam ("web_link_title_ar");

        if (!$model->save ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem updating the Web Link")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Web Link updated successfully"),
            "model" => $model
        ];
    }


    /**
     * Return web link detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($store_uuid, $web_link_id)
    {
        return $this->findModel ($web_link_id, $store_uuid);
    }

    /**
     * Delete Web Link
     */
    public function actionDelete($web_link_id, $store_uuid)
    {
        $model = $this->findModel ($web_link_id, $store_uuid);


        if (!$model->delete ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem deleting Web link")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Web Link deleted successfully")
        ];
    }

    /**
     * Finds the Web Link model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($web_link_id, $store_uuid)
    {
        $store_model = Yii::$app->accountManager->getManagedAccount ($store_uuid);

        $model = WebLink::find ()
            ->andWhere ([
                'web_link_id' => $web_link_id,
                'restaurant_uuid' => $store_model->restaurant_uuid
            ])
            ->one ();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
