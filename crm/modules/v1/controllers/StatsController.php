<?php

namespace crm\modules\v1\controllers;

use Yii;
use common\models\Ticket;
use yii\db\Expression;
use yii\rest\Controller;

class StatsController extends Controller
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
            'collectionOptions' => ['GET', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    /**
     * @return array
     */
    public function actionView() {

        $start_date = Yii::$app->request->get('start_date');
        $end_date = Yii::$app->request->get('end_date');

        $data['avg_response_time'] = Ticket::find()
            ->andWhere(['!=', 'ticket_status', Ticket::STATUS_PENDING])
            ->filterByDateRange($start_date, $end_date)
            ->average('response_time');

        $data['avg_resolution_time'] = Ticket::find()
            ->andWhere(['!=', 'ticket_status', Ticket::STATUS_PENDING])
            ->filterByDateRange($start_date, $end_date)
            ->average('resolution_time');

        $data['assigned'] = Ticket::find()
            ->andWhere(['!=', 'ticket_status', Ticket::STATUS_COMPLETED])
            ->andWhere(['staff_id' => Yii::$app->user->getId()])
            ->filterByDateRange($start_date, $end_date)
            ->count();

        $data['unassigned'] = Ticket::find()
            ->andWhere(['!=', 'ticket_status', Ticket::STATUS_COMPLETED])
            ->andWhere(new Expression('staff_id IS NULL'))
            ->filterByDateRange($start_date, $end_date)
            ->count();

        $data['totalPending'] = Ticket::find()
            ->andWhere(['ticket_status' => Ticket::STATUS_PENDING])
            ->filterByDateRange($start_date, $end_date)
            ->count();

        $data['totalInProgress'] = Ticket::find()
            ->andWhere(['ticket_status' => Ticket::STATUS_IN_PROGRESS])
            ->filterByDateRange($start_date, $end_date)
            ->count();

        $data['totalCompleted'] = Ticket::find()
            ->andWhere(['ticket_status' => Ticket::STATUS_COMPLETED])
            ->filterByDateRange($start_date, $end_date)
            ->count();

        return $data;
    }
}