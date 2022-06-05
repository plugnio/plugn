<?php

namespace staff\modules\v1\controllers;

use Yii;
use common\models\Ticket;
use common\models\TicketComment;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class TicketController extends Controller
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => \Yii::$app->params['allowedOrigins'],
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
     * Get all tickets
     * @param $store_uuid
     * @return ActiveDataProvider
     */
    public function actionList() {

        $store = Yii::$app->accountManager->getManagedAccount();

        $query = $store->getTickets()
            ->orderBy('updated_at DESC');

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Create voucher
     * @return array
     */
    public function actionCreate() {

        $store = Yii::$app->accountManager->getManagedAccount();

        $model = new Ticket();
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->staff_id =  Yii::$app->user->getId();
        $model->ticket_detail =  Yii::$app->request->getBodyParam("detail");
        $model->ticket_status = Ticket::STATUS_PENDING;
        $model->attachments =  Yii::$app->request->getBodyParam("attachments");

        if (!$model->save()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('app', "Ticket created successfully"),
        ];
    }

    /**
     * Create voucher
     * @return array
     */
    public function actionComment($ticket_uuid) {

        //validate access

        $this->findModel($ticket_uuid);

        $model = new TicketComment();
        $model->ticket_uuid = $ticket_uuid;
        $model->staff_id =  Yii::$app->user->getId();
        $model->ticket_comment_detail =  Yii::$app->request->getBodyParam("comment_detail");
        $model->attachments =  Yii::$app->request->getBodyParam("attachments");

        if (!$model->save()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('app', "Ticket comment added successfully"),
        ];
    }

    /**
     * return ticket comments
     */  
    public function actionComments($id)
    {
        return $this->findModel($id)->ticketComments;
    }

    /**
     * Return Ticket detail
     * @param $ticket_uuid
     * @return Ticket|array
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->findModel($id);
    }

    /**
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $ticket_uuid
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ticket_uuid)
    {
        $model = Ticket::find ()
            ->where([
                'ticket_uuid' => $ticket_uuid
            ])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}

