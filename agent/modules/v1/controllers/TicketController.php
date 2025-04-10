<?php

namespace agent\modules\v1\controllers;

use Yii;
use common\models\Ticket;
use common\models\TicketComment;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class TicketController extends BaseController
{
    /**
     * Get all tickets
     * @return ActiveDataProvider
     * 
     * @api {get} /tickets Get all tickets
     * @apiName GetAllTickets
     * @apiGroup Ticket
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
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
     * 
     * @api {post} /tickets Create ticket
     * @apiName CreateTicket
     * @apiGroup Ticket
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionCreate() {

        $attachments = Yii::$app->request->getBodyParam("attachments");

        $store = Yii::$app->accountManager->getManagedAccount();

        $model = new Ticket();
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->agent_id =  Yii::$app->user->getId();
        $model->ticket_detail =  Yii::$app->request->getBodyParam("detail");
        $model->ticket_status = Ticket::STATUS_PENDING;

        if($attachments) {
            $model->attachments = ArrayHelper::getColumn(
                $attachments,
                'Key'
            );
        }

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
     * 
     * @api {post} /tickets/:ticket_uuid Comment on ticket
     * @apiName CommentOnTicket
     * @apiGroup Ticket
     * 
     * @apiSuccess {string} message Message.
     */
    public function actionComment($ticket_uuid) {

        $attachments = Yii::$app->request->getBodyParam("attachments");

        //validate access

        $this->findModel($ticket_uuid);

        $model = new TicketComment();
        $model->ticket_uuid = $ticket_uuid;
        $model->agent_id =  Yii::$app->user->getId();
        $model->ticket_comment_detail =  Yii::$app->request->getBodyParam("comment_detail");
        //$model->attachments =  Yii::$app->request->getBodyParam("attachments");

        if($attachments) {    
            $model->attachments = ArrayHelper::getColumn(
                Yii::$app->request->getBodyParam("attachments"),
                'Key'
            );
        }

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
     * 
     * @api {get} /tickets/:ticket_uuid Comments on ticket
     * @apiName CommentsOnTicket
     * @apiGroup Ticket
     * 
     * @apiSuccess {string} message Message.
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
     * 
     * @api {get} /tickets/:ticket_uuid Ticket detail
     * @apiName TicketDetail
     * @apiGroup Ticket
     * 
     * @apiSuccess {string} message Message.
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
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = $store->getTickets()
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
