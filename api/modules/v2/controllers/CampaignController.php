<?php

namespace api\modules\v2\controllers;

use common\models\Campaign;
use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;


class CampaignController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'click'];

        return $behaviors;
    }

    /**
     * @param $id
     * @return array|string[]
     * 
     * @api {GET} /campaigns/:id Click campaign
     * @apiName ClickCampaign
     * @apiGroup Campaign
     * 
     * @apiSuccess {string} message Message.
     */
    public function actionClick($id)
    {
        $model = Campaign::find()->where([
            'utm_uuid' => $id
        ])->one();

        $model->no_of_clicks = $model->no_of_clicks + 1;

        if(!$model->save()) {
            return [
                'operation' => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success"
        ];
    }
}
