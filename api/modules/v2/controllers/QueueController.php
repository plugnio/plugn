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

        $errors = [];

        // Update existing failed queues (status 5) to pending (status 1) for today
        $today = date('Y-m-d');
        $updatedCount = Queue::updateAll(
            ['queue_status' => 1], // Set status to pending (1)
            [
                'AND',
                ['DATE(queue_created_at)' => $today],
                ['queue_status' => 5] // Current status is failed (5)
            ]
        );

        // Prepare response with details about the operation
        $response = [
            'success' => true,
            'message' => "Queue processing completed",
            'data' => [
                'updated_entries' => $updatedCount,
                'date' => $today
            ]
        ];

        // Add errors if any occurred during creation
        if (!empty($errors)) {
            $response['data']['errors'] = $errors;
            $response['data']['error_count'] = count($errors);
        }

        // If no updates or new entries were made
        if ($updatedCount === 0) {
            $response['message'] = 'No queue entries were updated or created';
        }

        return $response;
    }
}
