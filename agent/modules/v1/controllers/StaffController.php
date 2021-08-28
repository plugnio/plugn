<?php
namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;;
use yii\web\NotFoundHttpException;
use agent\models\AgentAssignment;
use agent\models\Agent;

class StaffController extends Controller {

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

    /**
     * Get all store's Staff members
     * @param $store_uuid
     * @return ActiveDataProvider
     */
    public function actionList($store_uuid) {

        $keyword = Yii::$app->request->get('keyword');
        Yii::$app->accountManager->getManagedAccount($store_uuid);

        $query =  AgentAssignment::find()->joinWith('agent');
        if ($keyword){
            $query->andWhere('or', [
                ['like', 'agent.agent_name', $keyword],
                ['like', 'assignment_agent_email', $keyword],
                ['like', 'role', $keyword]
            ]);
        }
        $query->andWhere(['restaurant_uuid' => $store_uuid]);

        return new ActiveDataProvider([
          'query' => $query
        ]);
    }

    /**
     * Create voucher
     * @return array
     */
    public function actionCreate() {

        $store_uuid = Yii::$app->request->getBodyParam("store_uuid");
        Yii::$app->accountManager->getManagedAccount($store_uuid);

        if(!$this->_isOwner($store_uuid)) {
         return [
             'operation' => 'error',
             'message' => Yii::t('agent', 'You are not allowed to add staff member. Please contact with store owner'),
         ];
       }

       $agentEmail = Yii::$app->request->getBodyParam("agent_email");

       $agent = Agent::findByEmail($agentEmail);

       // if($agent) {
       //     return [
       //         'operation' => 'error',
       //         'message' => Yii::t('agent', 'Email already in use'),
       //     ];
       // }
       //  $tempPassword = Yii::$app->security->generateRandomString(12);
       //
       //  $agent = new Agent();
       //  $agent->agent_name = Yii::$app->request->getBodyParam("agent_name");
       //  $agent->agent_email = $agentEmail;
       //  $agent->agent_status = Agent::STATUS_ACTIVE;
       //  $agent->tempPassword = $tempPassword;
       //
       //  if (!$agent->save()) {
       //      return [
       //          "operation" => "error",
       //          "message" => $agent->errors
       //      ];
       //  }
       $tempPassword = null;

       if(!$agent) {

          $tempPassword = Yii::$app->security->generateRandomString(12);

          $agent = new Agent();
          $agent->agent_name = Yii::$app->request->getBodyParam("agent_name");
          $agent->agent_email = $agentEmail;
          $agent->agent_status = Agent::STATUS_ACTIVE;
          $agent->tempPassword = $tempPassword;

          if (!$agent->save()) {
              return [
                  "operation" => "error",
                  "message" => $agent->errors
              ];
          }

        }

        $model = new AgentAssignment();
        $model->restaurant_uuid = $store_uuid;
        $model->agent_id =  $agent->agent_id;
        $model->business_location_id =  Yii::$app->request->getBodyParam("business_location_id");
        $model->role = (int) Yii::$app->request->getBodyParam("role");
        $model->assignment_agent_email =  $agentEmail;

        if (!$model->save()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        } else {
          if($tempPassword)
            $model->notificationMail($tempPassword);
          else
            $model->inviteAgent();
        }


        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Staff created successfully"),
            "data" => AgentAssignment::findOne($model->assignment_id)
        ];
    }

     /**
      * Update voucher
      */
     public function actionUpdate($assignment_id, $store_uuid)
     {
         $model = $this->findModel($assignment_id, $store_uuid);

         if(!$this->_isOwner($store_uuid)) {
          return [
              'operation' => 'error',
              'message' => Yii::t('agent', 'You are not allowed to update staff role. Please contact with store owner'),
          ];
        }

        $model->business_location_id =  Yii::$app->request->getBodyParam("business_location_id");
        $model->role = (int) Yii::$app->request->getBodyParam("role");

         if (!$model->save()) {
             if (isset($model->errors)) {
                 return [
                     "operation" => "error",
                     "message" => $model->errors
                 ];
             } else {
                 return [
                     "operation" => "error",
                     "message" => Yii::t('agent',"We've faced a problem updating the Staff")
                 ];
             }
         }

         return [
             "operation" => "success",
             "message" => Yii::t('agent',"Staff updated successfully"),
             "data" => $model
         ];
     }

    /**
     * Return Agent Assignment detail
     * @param $store_uuid
     * @param $assignment_id
     * @return Country|array
     * @throws NotFoundHttpException
     */
      public function actionDetail($store_uuid, $assignment_id) {

          $model =  $this->findModel($assignment_id, $store_uuid);

          if(!$this->_isOwner($store_uuid)) {
           return [
               'operation' => 'error',
               'message' => Yii::t('agent', 'You are not allowed to perform this action. Please contact with store owner'),

           ];
         }

        return $model;
    }

    /**
     * Delete Agent Assignment
     */
    public function actionDelete($assignment_id, $store_uuid)
    {
        $model =  $this->findModel($assignment_id, $store_uuid);

        if(!$this->_isOwner($store_uuid)) {
         return [
             'operation' => 'error',
             'message' => Yii::t('agent', 'You are not allowed to delete staff access. Please contact with store owner'),
         ];
       }

        if (!$model->delete()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem deleting Staff")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Staff deleted successfully")
        ];
    }

    /**
    * Finds the Agent Assignment model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $id
    * @return Country the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findModel($assignment_id, $store_uuid)
    {
        $store_model = Yii::$app->accountManager->getManagedAccount($store_uuid);

        if (($model = AgentAssignment::find()->where(['assignment_id' => $assignment_id, 'restaurant_uuid' => $store_model->restaurant_uuid])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }

    /**
     * Is login agent owner of given store
     * @param $store_uuid
     * @return bool
     */
     private function _isOwner($store_uuid)
     {
         $model = AgentAssignment::findOne(['agent_id' => Yii::$app->user->identity->agent_id , 'restaurant_uuid' => $store_uuid]);
         return ($model->role == AgentAssignment::AGENT_ROLE_OWNER);
     }
}
