<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use common\models\Queue;
use common\models\Restaurant;
use yii\web\Response;

class QueueController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // Add authentication for all actions except options
        $behaviors['authenticator']['except'] = ['options', 'create'];
        
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
        ];
        return $actions;
    }

    /**
     * Creates a new queue entry
     * 
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        
        // Only allow POST requests
        if (!$request->isPost) {
            Yii::$app->response->statusCode = 405; // Method Not Allowed
            return [
                'success' => false,
                'message' => 'Only POST method is allowed',
            ];
        }

        // Get and validate input
        $restaurantUuid = $request->getBodyParam('restaurant_uuid');
        $queueStatus = $request->getBodyParam('queue_status');

        if (empty($restaurantUuid) || empty($queueStatus)) {
            Yii::$app->response->statusCode = 400; // Bad Request
            return [
                'success' => false,
                'message' => 'restaurant_uuid and queue_status are required',
            ];
        }

        // Verify restaurant exists and user has access
        $restaurant = Restaurant::findOne(['restaurant_uuid' => $restaurantUuid]);
        if (!$restaurant) {
            Yii::$app->response->statusCode = 404; // Not Found
            return [
                'success' => false,
                'message' => 'Restaurant not found',
            ];
        }

        // Create and save queue entry
        $model = new Queue();
        $model->restaurant_uuid = $restaurantUuid;
        $model->queue_status = $queueStatus;
        
        if ($model->save()) {
            return [
                'success' => true,
                'message' => 'Queue created successfully',
                'data' => [
                    'queue_id' => $model->queue_id,
                    'restaurant_uuid' => $model->restaurant_uuid,
                    'queue_status' => $model->queue_status,
                ]
            ];
        }

        // If save failed
        Yii::$app->response->statusCode = 422; // Unprocessable Entity
        return [
            'success' => false,
            'message' => 'Failed to create queue',
            'errors' => $model->getErrors()
        ];
    }
}
