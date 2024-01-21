<?php

namespace agent\modules\v1\controllers;


use Yii;
use yii\db\Expression;


class AgentController extends BaseController
{
    /**
     * return stores assigned
     * @return mixed
     */
    public function actionStores()
    {
        return Yii::$app->user->identity
            ->getAgentAssignments ()
            ->all ();
    }

    /**
     * return store assignment details
     * @return mixed
     */
    public function actionStoreProfile()
    {
        $restaurant = Yii::$app->accountManager->getManagedAccount ();

        return Yii::$app->user->identity
            ->getAgentAssignments ()
            ->andWhere (['agent_assignment.restaurant_uuid' => $restaurant->restaurant_uuid])
            ->one ();
    }

    /**
     * return user profile
     * @return \yii\web\IdentityInterface|null
     */
    public function actionDetail()
    {
        return Yii::$app->user->identity;
    }

    public function actionDelete()
    {
        $model = Yii::$app->user->identity;

        //if there is store

        $stores = $model->getAccountsManaged()->count();

        if($stores > 0) {
            return [
                "operation" => "error",
                "message" => Yii::t('agent', "Please delete store(s) first to delete profile!"),
            ];
        }

        $model->setScenario(Agent::SCENARIO_DELETE);
        $model->deleted = true;
        $model->agent_deleted_at = new Expression("NOW()");

        if (!$model->save ()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        if (YII_ENV == 'prod') {
            $restaurantUuid = Yii::$app->request->headers->get('Store-Id');

            Yii::$app->eventManager->track('Profile Deleted', [
                "profile_status" => "Deleted",
            ], null, $restaurantUuid);
        }

        return [
            'model' => Yii::t('agent', "Agent profile updated successfully"),
            "operation" => "success",
        ];
    }

    /**
     * update store profile
     * @param $store_uuid
     * @return array|string[]
     */
    public function actionUpdateAgentProfile($store_uuid = null)
    {
        $model = Yii::$app->user->identity;

        $agentAssignment = Yii::$app->accountManager->getAssignment($store_uuid);

        $email = Yii::$app->request->getBodyParam ("agent_email");

        if($email != $model->agent_email) {
            $model->agent_new_email = $email;
        }

        $model->agent_name = Yii::$app->request->getBodyParam ("agent_name");

        if (!$model->save ()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }
        
        $agentAssignment->assignment_agent_email = Yii::$app->request->getBodyParam ("agent_email");
        $agentAssignment->email_notification = (int) Yii::$app->request->getBodyParam ("email_notification");
        $agentAssignment->reminder_email = (int) Yii::$app->request->getBodyParam ("reminder_email");
        $agentAssignment->receive_weekly_stats = (int) Yii::$app->request->getBodyParam ("receive_weekly_stats");

        $agentAssignment->save (false);

        //if new email 

        $message = Yii::t('agent', "Agent profile updated successfully");
        
        if($model->agent_new_email) 
        {
            $model->sendVerificationEmail();

            $message = Yii::t('agent', "Please click on the link sent to you by email to verify your account");
        }

        return [
            'model' => $model,
            "operation" => "success",
            "message" => $message
        ];
    }

    /**
     * update language preferency
     * @return array
     */
    public function actionLanguagePref()
    {
        $agent = Yii::$app->user->identity;
        $agent->agent_language_pref = Yii::$app->request->getBodyParam ('language_pref');

        $agent->scenario = 'updateLanguagePref';

        if(!$agent->save())
        {
            return [
                "operation" => "error",
                "message" => $agent->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Language Preferency Updated Successfully")
        ];
    }

    /**
     * change password
     */
    public function actionChangePassword()
    {
        $agent = Yii::$app->user->identity;

        $oldPassword = Yii::$app->request->getBodyParam ("oldPassword");
        $newPassword = Yii::$app->request->getBodyParam ("newPassword");
        $confirmPassword = Yii::$app->request->getBodyParam ("confirmPassword");

        if (!$oldPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t('agent', 'Old Password field required')
            ];
        }

        if (!$confirmPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t('agent','Confirm Password field required')
            ];
        }

        if (!$newPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t('agent','Password field required')
            ];
        }

        if ($confirmPassword != $newPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t('agent','Password not matching')
            ];
        }

        if (!$agent->validatePassword ($oldPassword)) {
            return [
                'operation' => 'error',
                'message' => Yii::t('agent', 'Old Password not valid')
            ];
        }

        $agent->setPassword ($newPassword);
        $agent->save (false);

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Account Password Updated Successfully")
        ];
    }
}
