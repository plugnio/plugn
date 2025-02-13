<?php

namespace crm\modules\v1\controllers;

use agent\models\Agent;
use crm\models\Currency;
use crm\models\Restaurant;
use common\models\StaffAssignment;
use common\models\StaffEmailVerifyAttempt;
use common\models\BusinessLocation;
use common\models\Category;
use common\models\RestaurantPaymentMethod;
use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBasicAuth;
use crm\models\Staff;
use crm\models\PasswordResetRequestForm;
use yii\web\NotFoundHttpException;


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
                    'X-Pagination-Total-Count',
                    'X-Error-Email',
                    'X-Error-Password'
                ],
            ],
        ];

        // Basic Auth accepts Base64 encoded username/password and decodes it for you
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'except' => ['options'],
            'auth' => function ($email, $password) {

                $staff = Staff::findByEmail($email);

                if(!$staff) {
                    Yii::$app->response->headers->set (
                        'X-Error-Email', 
                        Yii::t('staff', 'Email not found')
                    );
                    
                    return null;
                }

                if ($staff->validatePassword($password)) {
                    return $staff;
                }

                Yii::$app->response->headers->set (
                    'X-Error-Password', 
                    Yii::t('staff', 'Password not matching')
                );

                return null;
            }
        ];

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        // also avoid for public actions like registration and password reset
        $behaviors['authenticator']['except'] = [
            'options',
            'request-reset-password',
            'update-password',
            'update-email',
            'resend-verification-email',
            'verify-email',
            'is-email-verified',
            'login-auth0',
            'login-by-key',
            'login-by-apple',
            'login-by-google',
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
     * @return array|type
     * @throws NotFoundHttpException
     */
    public function actionLoginByGoogle() {

        $token = Yii::$app->request->getBodyParam("idToken");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=" . $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch));

        if (empty($response->email)) {
            return [
                'operation' => 'error',
                "code" => 1,
                'message' => Yii::t('agent',"Invalid access token")
            ];
        }

        $model = Staff::find()
            ->andWhere(['staff_email' => $response->email])
            ->one();


        if (!$model) {
            return [
                "operation" => "error",
                "code" => 1,
                "message" => Yii::t('agent',"Account not found")
            ];
        }

        Yii::$app->eventManager->track('Log In', [
            "login_method" => "Google"
        ]);

        return $this->_loginResponse($model);
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionLoginByKey() {

        $auth_key = Yii::$app->request->getBodyParam('auth_key');

        $model = Staff::find()
            ->andWhere(['staff_auth_key' => $auth_key])
            //->andWhere(['deleted' => 0])
            ->one();

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');

            /*return [
                "operation" => "error",
                "code" => 1,
                "message" => Yii::t('agent',"Account not found")
            ];*/
        }

        Yii::$app->eventManager->track('Log In', [
            "login_method" => "Admin"
        ]);

        $model->staff_auth_key = "";
        $model->save(false);

        return $this->_loginResponse($model);
    }

    /**
     * login with auth0 token
     * @return array
     */
    public function actionLoginAuth0()
    {
        $accessToken = Yii::$app->request->getBodyParam('accessToken');

        $response = Yii::$app->auth0->getUserInfo($accessToken);

        if(!$response->isOk) {
            return [
                "operation" => "error",
                "message" => "Invalid access token"
            ];
        }

        $userInfo = $response->data;

        if(!$userInfo || !$userInfo['email'])
        {
            return [
                "operation" => "error",
                "message" => "We've faced a problem creating your account, please contact us for assistance.",
            ];
        }

        $staff = Staff::findByEmail($userInfo['email']);

        /**
         * redirect to signup page if no account
         */
        if(!$staff)
        {
            return [
                "operation" => "error",
                "code" => 1,
                "message" => "Account not found"
            ];
        }

        // Email and password are correct, check if his email has been verified
        // If email has been verified, then allow him to log in
        /*if ($agent->contact_email_verification != Candidate::EMAIL_VERIFIED) {

            //$agent->generateOtp();
            //$agent->save(false);

            return [
                "operation" => "error",
                "errorType" => "email-not-verified",
                "message" => Yii::t('candidate', "Please click the verification link sent to you by email to activate your account"),
                "unVerifiedToken" => $this->_loginResponse($agent)
            ];
        }*/

        return $this->_loginResponse($staff);
    }

    /**
     * Perform validation on the staff account (check if he's allowed login to platform)
     * If everything is alright,
     * Returns the BEARER access token required for futher requests to the API
     * @return array
     */
    public function actionLogin() {
        $staff = Yii::$app->user->identity;

        return $this->_loginResponse($staff);
    }

    /**
     * Re-send manual verification email to staff
     * @return array
     *
    public function actionResendVerificationEmail()
    {
        $emailInput = Yii::$app->request->getBodyParam("email");

        $staff = Staff::findOne([
            'staff_email' => $emailInput,
        ]);

        $errors = false;
        $errorCode = null; //error code

        if ($staff) {

            if ($staff->staff_email_verification == Staff::EMAIL_VERIFIED) {
                return [
                    'operation' => 'error',
                    'errorCode' => 1,
                    'message' => Yii::t('staff', 'You have verified your email')
                ];
            }

            //Check if this user sent an email in past few minutes (to limit email spam)
            $emailLimitDatetime = null;
            $currentDatetime = new \DateTime();

            if ($staff->staff_limit_email) {
                $emailLimitDatetime = new \DateTime($staff->staff_limit_email);
                date_add($emailLimitDatetime, date_interval_create_from_date_string('1 minutes'));
            }

            if ($staff->staff_limit_email && $currentDatetime < $emailLimitDatetime) {
                $difference = $currentDatetime->diff($emailLimitDatetime);
                $minuteDifference = (int) $difference->i;
                $secondDifference = (int) $difference->s;

                $errorCode = 2;

                $errors = Yii::t('staff', "Email was sent previously, you may request another one in {numMinutes, number} minutes and {numSeconds, number} seconds", [
                    'numMinutes' => $minuteDifference,
                    'numSeconds' => $secondDifference,
                ]);
            } else if ($staff->staff_email_verification == Staff::EMAIL_NOT_VERIFIED) {
                $staff->sendVerificationEmail();
            }
        } else {
            $errorCode = 3;
            $errors['email'] = [Yii::t('staff', 'Account not found')];
        }

        // If errors exist show them

        if ($errors) {
            return [
                'errorCode' => $errorCode,
                'operation' => 'error',
                'message' => $errors
            ];
        }

        // Otherwise return success
        return [
            'operation' => 'success',
            'message' => Yii::t('staff', 'Please click on the link sent to you by email to verify your account'),
        ];
    }*/

    /**
     * Process email verification
     * @return array
     *
    public function actionVerifyEmail() {

        $code = Yii::$app->request->getBodyParam("code");
        $email = Yii::$app->request->getBodyParam("email");

        //check limit reached

        $totalInvalidAttempts = StaffEmailVerifyAttempt::find()
            ->andWhere([
                'staff_email' => $email,
                'ip_address' => Yii::$app->getRequest()->getUserIP()
            ])
            ->andWhere(new \yii\db\Expression("created_at >= DATE_SUB(NOW(),INTERVAL 1 HOUR)"))//last 1 hour
            ->count();

        if ($totalInvalidAttempts > 4) {
            return [
                'operation' => 'error',
                'message' => Yii::t('staff', 'You reached your limit to verify email. Please try again after an hour.')
            ];
        }

        $response = Staff::verifyEmail($email, $code);

        if ($response['success'] == false) {
            return [
                'operation' => 'error',
                'message' => $response['message']
            ];
        }

        if ($response['success'] == true) {
            //remove old email verification attempts

            StaffEmailVerifyAttempt::deleteAll([
                'staff_email' => $email,
                'ip_address' => Yii::$app->getRequest()->getUserIP()
            ]);

            //remove otp

            //$staff->otp = null;
            //$staff->save(false);

            return $this->_loginResponse($response['data']);
            
        } else {
            //add entry for invalid attempt

            $model = new StaffEmailVerifyAttempt;
            $model->code = $code;
            $model->staff_email = $email;
            $model->ip_address = Yii::$app->getRequest()->getUserIP();
            $model->save();

            return [
                'operation' => 'error',
                'message' => Yii::t('staff', 'Invalid email verification code.')
            ];
        }
    }*/

    /**
     * Sends password reset email to user
     * @return array
     */
    public function actionRequestResetPassword() {

        $emailInput = Yii::$app->request->getBodyParam("email");
        $errors = false;
        $model = new PasswordResetRequestForm();
        $model->email = $emailInput;

        if (!$model->validate()) {
            return [
                'operation' => 'error',
                'message' => $model->getErrors()
            ];
        }

        $staff = Staff::findOne([
           'staff_email' => $model->email,
        ]);

        //Check if this user sent an email in past few minutes (to limit email spam)
        
        $currentDatetime = new \DateTime('now');
        $emailLimitDatetime = null;
        
        if ($staff->staff_limit_email) {
            $emailLimitDatetime = new \DateTime($staff->staff_limit_email);
            date_add($emailLimitDatetime, date_interval_create_from_date_string('1 minutes'));
        }
        
        if ($staff->staff_limit_email && $currentDatetime < $emailLimitDatetime) {
            $difference = $currentDatetime->diff($emailLimitDatetime);
            $minuteDifference = (int) $difference->i;
            $secondDifference = (int) $difference->s;

            $errors = Yii::t('staff', "Email was sent previously, you may request another one in {numMinutes, number} minutes and {numSeconds, number} seconds", [
                'numMinutes' => $minuteDifference,
                'numSeconds' => $secondDifference,
            ]);
        } else if (!$model->sendEmail()) {
            $errors = Yii::t('staff', 'Sorry, we are unable to reset a password for email provided.');
        }

        if($errors) {
            return [
                'operation' => 'error',
                'message' => $errors
            ];
        }

        // Otherwise return success
        return [
            'operation' => 'success',
            'message' => Yii::t ('staff', 'Please check the link sent to you on your email to set new password.')
        ];
    }

    /**
     * Updates password based on passed token
     * @return array
     */
    public function actionUpdatePassword() {

        $token = Yii::$app->request->getBodyParam("token");
        $newPassword = Yii::$app->request->getBodyParam("newPassword");
        //$cPassword = Yii::$app->request->getBodyParam("cPassword");

        $staff = Staff::findByPasswordResetToken($token);

        if (!$staff) {
            return [
                'operation' => 'error',
                'message' => Yii::t ('staff', 'Invalid password reset token.')
            ];
        }
        if (!$newPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t ('staff', 'Password field required')
            ];
        }

        /*if (!$cPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t ('staff', 'Confirm Password field required')
            ];
        }

        if ($cPassword != $newPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t ('staff', 'Password & Confirm Password does not match')
            ];
        }*/

        $staff->setPassword($newPassword);
        $staff->removePasswordResetToken();

        /**
         * as password reset token will be sent to email and user will update password
         * from that link so if user have token he have valid email
         */

        //$staff->staff_email_verification = Staff::EMAIL_VERIFIED;

        $staff->save(false);

        return $this->_loginResponse($staff);
    }

    /**
     * Return staff data after successful login
     * @param type $staff
     * @return type
     */
    private function _loginResponse($staff)
    {
        // Return Staff access token if everything valid

        $accessToken = $staff->accessToken->token_value;

        return [
            "operation" => "success",
            "token" => $accessToken,
            "id" => $staff->staff_id,
            "username" => $staff->staff_id,
            "staff_name" => $staff->staff_name,
            "staff_email" => $staff->staff_email,
        ];
    }
}

