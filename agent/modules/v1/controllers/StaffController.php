<?php
namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;;
use yii\web\NotFoundHttpException;
use agent\models\AgentAssignment;
use agent\models\Agent;

class StaffController extends BaseController {

    /**
     * only owner will have access
     */
    private function ownerCheck()
    {
        if(!Yii::$app->accountManager->isOwner()) {
            throw new \yii\web\BadRequestHttpException(
                Yii::t('agent', 'You are not allowed to view discounts. Please contact with store owner')
            );
        }

        //should have access to store
        Yii::$app->accountManager->getManagedAccount();
        return true;
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
    public function actionList() {

        $this->ownerCheck();

        $keyword = Yii::$app->request->get('keyword');

        $store = Yii::$app->accountManager->getManagedAccount();

        $query =  AgentAssignment::find()
            ->joinWith('agent');

        if ($keyword) {
            $query->andWhere([
                'or', 
                ['like', 'agent.agent_name', $keyword],
                ['like', 'assignment_agent_email', $keyword],
                ['like', 'role', $keyword]
            ]);
        }

        $query->andWhere(['restaurant_uuid' => $store->restaurant_uuid]);

        return new ActiveDataProvider([
          'query' => $query
        ]);
    }

    /**
     * Create voucher
     * @return array
     */
    public function actionCreate() {
        $this->ownerCheck();

        $store = Yii::$app->accountManager->getManagedAccount();

        /*if(!$this->_isOwner($store_uuid)) {
         return [
             'operation' => 'error',
             'message' => Yii::t('agent', 'You are not allowed to add staff member. Please contact with store owner'),
         ];
       }*/

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
        $model->restaurant_uuid = $store->restaurant_uuid;
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

            Yii::$app->eventManager->track('Staff Added', [
                "name" => $agent->agent_name,
                "role" => $model->role
            ], null, $store->restaurant_uuid);

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Staff created successfully"),
            "data" => AgentAssignment::findOne($model->assignment_id)
        ];
    }

     /**
      * Update voucher
      */
     public function actionUpdate($assignment_id, $store_uuid = null)
     {
         $this->ownerCheck();
         $model = $this->findModel($assignment_id);

         /*if(!$this->_isOwner($store_uuid)) {
          return [
              'operation' => 'error',
              'message' => Yii::t('agent', 'You are not allowed to update staff role. Please contact with store owner'),
          ];
        }*/

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
      public function actionDetail($assignment_id, $store_uuid = null) {
          $this->ownerCheck();
          return $this->findModel($assignment_id);

          /*if(!$this->_isOwner($store_uuid)) {
           return [
               'operation' => 'error',
               'message' => Yii::t('agent', 'You are not allowed to perform this action. Please contact with store owner'),

           ];
         }*/
    }

    /**
     * Delete Agent Assignment
     */
    public function actionDelete($assignment_id, $store_uuid = null)
    {
        $this->ownerCheck();

        $model =  $this->findModel($assignment_id);

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

            Yii::$app->eventManager->track('Staff Removed', [
                "name" => $model->agent->agent_name,
                "role" => $model->role
            ], null, $model->restaurant_uuid);

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Staff deleted successfully")
        ];
    }

    /**
    * Finds the Agent Assignment model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $assignment_id
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findModel($assignment_id)
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = AgentAssignment::find()
            ->where([
                'assignment_id' => $assignment_id,
                'restaurant_uuid' => $store->restaurant_uuid
            ])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
