<?php

namespace agent\modules\v1\controllers;

use common\models\Campaign;
use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;


class CampaignController extends Controller
{
    public function behaviors()
    {
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
    public function actions()
    {
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
     * Get all store's categories
     * @param type $id
     * @param type $store_uuid
     * @return ActiveDataProvider
     */
    public function actionList($store_uuid = null)
    {
        $keyword = Yii::$app->request->get('keyword');

        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $query = $store->getCampaigns();

        if ($keyword) {
            $query->andWhere([
                'OR',
                ['like', 'utm_source', $keyword],
                ['like', 'utm_medium', $keyword],
                ['like', 'utm_campaign', $keyword],
            ]);
        }

        $query->orderBy([new \yii\db\Expression('created_at DESC')]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Create category
     * @return array
     */
    public function actionCreate()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = new Campaign();
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->utm_source = Yii::$app->request->getBodyParam("source");
        $model->utm_medium = Yii::$app->request->getBodyParam("medium");
        $model->utm_campaign = Yii::$app->request->getBodyParam("campaign");
        $model->utm_content = Yii::$app->request->getBodyParam("content");
        $model->utm_term = Yii::$app->request->getBodyParam("term");

        if (!$model->save()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Campaign created successfully"),
            "model" => Campaign::findOne($model->utm_uuid)
        ];
    }

    /**
     * Update campaign
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->utm_source = Yii::$app->request->getBodyParam("source");
        $model->utm_medium = Yii::$app->request->getBodyParam("medium");
        $model->utm_campaign = Yii::$app->request->getBodyParam("campaign");
        $model->utm_content = Yii::$app->request->getBodyParam("content");
        $model->utm_term = Yii::$app->request->getBodyParam("term");

        if (!$model->save()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem updating the campaign")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Campaign updated successfully"),
            "model" => $model
        ];
    }

    /**
     * Delete Category
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!$model->delete()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem deleting the campaign")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Campaign deleted successfully")
        ];
    }

    /**
     * Return Campaign detail
     * @param type $store_uuid
     * @param type $utm_uuid
     * @return type
     */
    public function actionDetail($id)
    {
        return $this->findModel($id);
    }

    /**
     * Finds the Campaign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $utm_uuid
     * @return Campaign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($utm_uuid)
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = $store->getCampaigns()->where([
            'utm_uuid' => $utm_uuid
        ])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
