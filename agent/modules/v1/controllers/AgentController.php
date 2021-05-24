<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\models\Agent;

class AgentController extends Controller {

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



    public function actionDetail() {
        $agent = Yii::$app->user->identity;
        if($agent){
          $accessToken = $agent->accessToken->token_value;

          return [
              "operation" => "success",
              "body" => [
                "id" => $agent->agent_id,
                "agent_name" => $agent->agent_name,
                "agent_email" => $agent->agent_email
              ]
          ];
        } else {
          return [
              'operation' => 'error',
              'message' => 'No results found'
          ];
        }


    }



        public function actionUpdateAgentProfile($store_uuid) {

            $model = Yii::$app->user->identity;

            if (!isset($model->agent_id)) {
                return [
                    "operation" => "error",
                    "message" => 'Invalid Agent ID'
                ];
            }

            $agentAssignment  = $model->getAgentAssignments()->where(['restaurant_uuid' => $store_uuid])->one();

            if (!isset($agentAssignment->restaurant_uuid)) {
              return [
                  "operation" => "error",
                  "message" => 'You do not own this store.'
              ];
          }


            $model->agent_name = Yii::$app->request->getBodyParam("agent_name");
            $model->agent_email = Yii::$app->request->getBodyParam("agent_email");

            if (!$model->save()) {
                  return [
                      "operation" => "error",
                      "message" => $model->errors
                  ];
              } else {

                $agentAssignment->assignment_agent_email = Yii::$app->request->getBodyParam("agent_email");


                if(Yii::$app->request->getBodyParam("email_notification") != null )
                  $agentAssignment->email_notification = Yii::$app->request->getBodyParam("email_notification");

                if(Yii::$app->request->getBodyParam("reminder_email") != null )
                  $agentAssignment->reminder_email = Yii::$app->request->getBodyParam("reminder_email");

                if(Yii::$app->request->getBodyParam("receive_weekly_stats") != null )
                  $agentAssignment->receive_weekly_stats = Yii::$app->request->getBodyParam("receive_weekly_stats");

                $agentAssignment->save(false);

            }

            return [
              'model' => $model,
              "operation" => "success",
              "message" => "Agent profile updated successfully"
            ];

        }



          /**
          * Return agent model
          * @param type $employer_uuid
          * @return \common\models\Agent
          */
         private function findModel($agent_id) {
             $model = Agent::findIdentity($agent_id);

             if (!$model) {
                 return false;
             }

             return $model;
         }




}
