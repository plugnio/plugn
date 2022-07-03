<?php

namespace agent\modules\v1\controllers;


use Yii;
use yii\rest\Controller;


class AgentController extends Controller
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
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

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
            ->andWhere (['restaurant_uuid' => $restaurant->restaurant_uuid])
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
