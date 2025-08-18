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
        $queueStatus = $request->getBodyParam('queue_status');

        if (empty($queueStatus)) {
            Yii::$app->response->statusCode = 400; // Bad Request
            return [
                'success' => false,
                'message' => 'queue_status is required',
            ];
        }

        // Get all active restaurants
        $activeRestaurants = Restaurant::find()
            ->select('restaurant_uuid')
            ->where(['restaurant_status' => 1])
            ->asArray()
            ->all();

        if (empty($activeRestaurants)) {
            return [
                'success' => false,
                'message' => 'No active restaurants found',
            ];
        }

        $successCount = 0;
        $errors = [];

        // Create queue entries for all active restaurants
        foreach ($activeRestaurants as $restaurant) {
            $model = new Queue();
            $model->restaurant_uuid = $restaurant['restaurant_uuid'];
            $model->queue_status = $queueStatus;
            
            if ($model->save()) {
                $successCount++;
            } else {
                $errors[$restaurant['restaurant_uuid']] = $model->getErrors();
            }
        }

        if ($successCount > 0) {
            $response = [
                'success' => true,
                'message' => "Successfully created queue entries for $successCount restaurants",
                'data' => [
                    'success_count' => $successCount,
                    'total_restaurants' => count($activeRestaurants)
                ]
            ];

            // Add errors if any
            if (!empty($errors)) {
                $response['data']['errors'] = $errors;
                $response['data']['error_count'] = count($errors);
            }

            return $response;
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
