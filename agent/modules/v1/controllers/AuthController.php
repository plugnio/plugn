<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBasicAuth;
use common\models\Agent;
use agent\models\PasswordResetRequestForm;

/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class AuthController extends Controller {

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

        // Basic Auth accepts Base64 encoded username/password and decodes it for you
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'except' => ['options'],
            'auth' => function ($email, $password) {

                $agent = Agent::findByEmail($email);

                if ($agent && $agent->validatePassword($password)) {
                    return $agent;
                }

                return null;
            }
        ];

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        // also avoid for public actions like registration and password reset
        $behaviors['authenticator']['except'] = [
            'options',
            'request-reset-password',
            'update-password'
        ];

        return $behaviors;
    }


    /**
     * @inheritdoc
     */
    public function actions() {
        $actions = parent::actions();

        // Return Header explaining what options are available for next request
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];

        return $actions;
    }

    /**
     * Perform validation on the agent account (check if he's allowed login to platform)
     * If everything is alright,
     * Returns the BEARER access token required for futher requests to the API
     * @return array
     */
    public function actionLogin() {
        $agent = Yii::$app->user->identity;

        return $this->_loginResponse($agent);
    }




    /**
     * Sends password reset email to user
     * @return array
     */
    public function actionRequestResetPassword() {
        $emailInput = Yii::$app->request->getBodyParam("email");

        $model = new PasswordResetRequestForm();
        $model->email = $emailInput;

        $errors = false;

        if ($model->validate()) {

            $agent = Agent::findOne([
               'agent_email' => $model->email,
            ]);

            if ($agent) {


                if (!$model->sendEmail($agent)) {
                    $errors = 'Sorry, we are unable to reset a password for email provided.';
                }
            }
        } else if (isset($model->errors['agent_email'])) {
            $errors = $model->errors['agent_email'];
        }

        // If errors exist show them
        if ($errors) {
            return [
                'operation' => 'error',
                'message' => $errors
            ];
        }

        // Otherwise return success
        return [
            'operation' => 'success',
            'message' => 'Please check the link sent to you on your email to set new password.'
        ];
    }



    /**
     * Updates password based on passed token
     * @return array
     */
    public function actionUpdatePassword() {
        $token = Yii::$app->request->getBodyParam("token");
        $newPassword = Yii::$app->request->getBodyParam("newPassword");
        $cPassword = Yii::$app->request->getBodyParam("cPassword");

        $agent = Agent::findByPasswordResetToken($token);

        if (!$agent) {
            return [
                'operation' => 'error',
                'message' => 'Invalid password reset token.'
            ];
        }
        if (!$newPassword) {
            return [
                'operation' => 'error',
                'message' => 'Password field required'
            ];
        }
        if (!$cPassword) {
            return [
                'operation' => 'error',
                'message' => 'Confirm Password field required'
            ];
        }

        if ($cPassword != $newPassword) {
            return [
                'operation' => 'error',
                'message' => 'Password & Confirm Password does not match'
            ];
        }

        $agent->setPassword($newPassword);
        $agent->removePasswordResetToken();
        $agent->save(false);

        //Whenever a user changes his password using any method (password reset email / profile page), we need to send out the following email to confirm that his password was set
        // \Yii::$app->mailer->htmlLayout = "layouts/text";
        //
        // \Yii::$app->mailer->compose([
        //                     'html' => 'employer/password-reset-confirmed'
        //                         ])
        //                 ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->params['appName']])
        //                 ->setTo($agent->email)
        //                 ->setSubject(Yii::t('employer', 'Your password reset was a success'))
        //                 ->send();

        return $this->_loginResponse($agent);
    }


    /**
     * Return agent data after successful login
     * @param type $agent
     * @return type
     */
    private function _loginResponse($agent) {

        // Return Agent access token if everything valid

        $accessToken = $agent->accessToken->token_value;

        return [
            "operation" => "success",
            "token" => $accessToken,
            "id" => $agent->agent_id,
            "username" => $agent->agent_id,
            "agent_name" => $agent->agent_name,
            "agent_email" => $agent->agent_email,
            "selectedStore" => $agent->getAccountsManaged()
            ->select([
              'restaurant_uuid',
              'name',
              'name_ar',
              'restaurant_domain'
            ])
            ->one(),
            "stores" => $agent->getAccountsManaged()
            ->select([
              'restaurant_uuid',
              'name',
              'name_ar',
              'restaurant_domain'
            ])
            ->all(),
        ];
    }

}
