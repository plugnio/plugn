<?php

namespace agent\modules\v1\controllers;


use Yii;
use yii\db\Expression;


class AgentController extends BaseController
{
    /**
     * return stores assigned
     * @return mixed
     * 
     * @api {get} /agent/stores Get stores assigned
     * @apiName GetStores
     * @apiGroup Agent
     *
     * @apiSuccess {Array} List of stores assigned to the agent.
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
     * 
     * @api {get} /agent/store-profile Get store profile
     * @apiName GetStoreProfile
     * @apiGroup Agent
     *
     * @apiSuccess {Array} Store profile details.
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
     * 
     * @api {get} /agent Get user profile
     * @apiName GetUserProfile
     * @apiGroup Agent
     *
     * @apiSuccess {Array} User profile details.
     */
    public function actionDetail()
    {
        return Yii::$app->user->identity;
    }

    /**
     * Delete agent profile
     * @return array
     * 
     * @api {delete} /agent/delete Delete agent profile
     * @apiName DeleteAgentProfile
     * @apiGroup Agent
     *
     * @apiSuccess {Array} Agent profile deleted successfully.
     */
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

            $restaurantUuid = Yii::$app->request->headers->get('Store-Id');

            Yii::$app->eventManager->track('Profile Deleted', [
                "profile_status" => "Deleted",
            ], null, $restaurantUuid);


        return [
            'model' => Yii::t('agent', "Agent profile updated successfully"),
            "operation" => "success",
        ];
    }

    /**
     * update store profile
     * @param $store_uuid
     * @return array|string[]
     * 
     * @api {put} /agent/update Update agent profile
     * @apiName UpdateAgentProfile
     * @apiGroup Agent
     *
     * @apiParam {string} agent_name Agent name.
     * @apiParam {string} agent_email Agent email.
     * @apiParam {string} email_notification Email notification.
     * @apiParam {string} reminder_email Reminder email.
     * @apiParam {string} receive_weekly_stats Receive weekly stats.
     * 
     * @apiSuccess {Array} Agent profile updated successfully.
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

        $restaurantUuid = Yii::$app->request->headers->get('Store-Id');

        Yii::$app->eventManager->track('Profile Updated', [
            "email_notification" => $model->email_notification,
            "reminder_email_order_acceptance" => $model->reminder_email,
            "weekly_stats_email" => $model->receive_weekly_stats,
            "user_name" => $model->agent_name,
            "email" => $model->agent_email
        ], null, $restaurantUuid);

        return [
            'model' => $model,
            "operation" => "success",
            "message" => $message
        ];
    }

    /**
     * update language preferency
     * @return array
     * 
     * @api {PATCH} /agent/language-pref Update language preferency
     * @apiName UpdateLanguagePref
     * @apiGroup Agent
     *
     * @apiParam {string} language_pref Language preferency.
     * @apiSuccess {Array} Language preferency updated successfully.
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
     * @return array
     * 
     * @api {POST} /agent/change-password Change password
     * @apiName ChangePassword
     * @apiGroup Agent
     *
     * @apiParam {string} oldPassword Old password.
     * @apiParam {string} newPassword New password.
     * @apiParam {string} confirmPassword Confirm password.
     * 
     * @apiSuccess {Array} Password changed successfully.
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
