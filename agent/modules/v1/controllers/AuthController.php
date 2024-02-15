<?php

namespace agent\modules\v1\controllers;

use agent\models\AgentToken;
use agent\models\Currency;
use agent\models\Restaurant;
use common\models\RestaurantByCampaign;
use common\models\AgentEmailVerifyAttempt;
use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBasicAuth;
use agent\models\Agent;
use agent\models\PasswordResetRequestForm;


/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class AuthController extends BaseController {

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

                $agent = Agent::findByEmail($email);

                if(!$agent) {
                    Yii::$app->response->headers->set (
                        'X-Error-Email', 
                        Yii::t('agent', 'Email not found')
                    );
                    
                    return null;
                }

                if(empty($password)) {
                    Yii::$app->response->headers->set (
                        'X-Error-Password',
                        Yii::t('agent', 'Password not provided')
                    );

                    return null;
                }

                if ($agent->validatePassword($password)) {
                    return $agent;
                }

                Yii::$app->response->headers->set (
                    'X-Error-Password', 
                    Yii::t('agent', 'Password not matching')
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
            'signup',
            'signup-step-one',
            'update-email',
            'resend-verification-email',
            'verify-email',
            'is-email-verified',
            'login-auth0',
            'locate',
            'login-by-apple',
            'login-by-google'
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

        // Email and password are correct, check if his email has been verified
        // If agent email has been verified, then allow him to log in
        if($agent->agent_email_verification != \common\models\Agent::EMAIL_VERIFIED) {

            return [
                "operation" => "error",
                "errorType" => "email-not-verified",
                "message" => Yii::t('agent',"Please click the verification link sent to you by email to activate your account"),
                "unVerifiedToken" => $this->_loginResponse($agent)
            ];
        }

        Yii::$app->eventManager->track('Log In', [
            "login_method" => "Email",
        ]);

        return $this->_loginResponse($agent);
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
                "message" => Yii::t('agent',"Invalid access token")
            ];
        }

        $userInfo = $response->data;

        if(!$userInfo || !$userInfo['email'])
        {
            return [
                "operation" => "error",
                "message" => Yii::t('agent',"We've faced a problem creating your account, please contact us for assistance.")
            ];
        }

        $agent = Agent::find()
            ->andWhere(['agent_email' => $userInfo['email']])
            ->one();

        /**
         * redirect to signup page if no account
         */
        if(!$agent)
        {
            return [
                "operation" => "error",
                "code" => 1,
                "message" => Yii::t('agent',"Account not found")
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
                "message" => Yii::t('agent', "Please click the verification link sent to you by email to activate your account"),
                "unVerifiedToken" => $this->_loginResponse($agent)
            ];
        }*/

        Yii::$app->eventManager->track('Log In', [
            "login_method" => "Auth0"
        ]);

        return $this->_loginResponse($agent);
    }

    /**
     * Sign up with google login
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

        $model = Agent::find()
            ->andWhere(['agent_email' => $response->email])
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
     *
     * Sign up with apple login
     */
    public function actionLoginByApple() {

        try {

            $jwt = Yii::$app->request->getBodyParam("identityToken");

            //will throw error on invalid token

            $payload = Yii::$app->jwt->decode($jwt);

        } catch(\ErrorException $e) {

            return [
                'operation' => 'error',
                'message' => $e->getMessage()
            ];

        }

        if(empty($payload->email)) {
            return [
                'operation' => 'error',
                'message' => Yii::t('agent',"Invalid access token")
            ];
        }

        $email = $payload->email;

        //$familyName = Yii::$app->request->getBodyParam("familyName");
        //$givenName = Yii::$app->request->getBodyParam("givenName");

        $model = Agent::find()
            ->andWhere(['agent_email' => $email])
            ->one();


        if (!$model) {
            return [
                "operation" => "error",
                "code" => 1,
                "message" => Yii::t('agent',"Account not found")
            ];
        }

        Yii::$app->eventManager->track('Log In', [
            "login_method" => "Apple"
        ]);

        return $this->_loginResponse($model);
    }

    /**
     * signup agent
     * @return array|string[]
     */
    public function actionSignupStepOne()
    {
        $accessToken = Yii::$app->request->getBodyParam('accessToken');
        $token = Yii::$app->request->getBodyParam('token');

        //TODO: make token as required field once we update android app

        if(YII_ENV == 'prod') {
            $response = Yii::$app->reCaptcha->verify($token);

            if (!$response->data || !$response->data['success']) {
                return [
                    "operation" => "error",
                    "code" => 0,
                    "message" => Yii::t('agent', "Invalid captcha validation")
                ];
            }
        }

        $agent = new Agent();
        $agent->setScenario(Agent::SCENARIO_CREATE_NEW_AGENT);
        $agent->utm_uuid = Yii::$app->request->getBodyParam('utm_uuid');
        $agent->agent_name = Yii::$app->request->getBodyParam('name');
        $agent->agent_email = Yii::$app->request->getBodyParam('email');
        $agent->agent_number = Yii::$app->request->getBodyParam ('owner_number');
        $agent->agent_phone_country_code = Yii::$app->request->getBodyParam ('owner_phone_country_code');

        $agent->tempPassword = Yii::$app->request->getBodyParam ('password');

        if($accessToken) {

            $response = Yii::$app->auth0->getUserInfo ($accessToken);

            if ($response->isOk && $response->data['email']) {
                $agent->agent_email = $response->data['email'];
                $agent->agent_email_verification = Agent::EMAIL_VERIFIED;
            }
        }

        if (!$agent->save()) {
            return [
                "operation" => "error",
                "message" => $agent->errors
            ];
        }

        if (YII_ENV == 'prod') {

            $param = [
                'email' => Yii::$app->request->getBodyParam('email'),
                'password' => Yii::$app->request->getBodyParam('password')
            ];

            Yii::$app->auth0->createUser($param);
        }

        $full_name = explode(' ', $agent->agent_name);
        $firstname = $full_name[0];
        $lastname = array_key_exists(1, $full_name) ? $full_name[1] : null;

        Yii::$app->eventManager->setUser($agent->agent_id, [
            'name' => trim($agent->agent_name),
            'email' => $agent->agent_email,
        ]);

        Yii::$app->eventManager->track('Agent Signup', [
            'first_name' => trim($firstname),
            'last_name' => trim($lastname),
            'email' => $agent->agent_email,
            "campaign" => $agent->campaign ? $agent->campaign->utm_campaign : null,
            "utm_medium" => $agent->campaign ? $agent->campaign->utm_medium : null,
            "profile_status" => "Active",
            "user_id" => $agent->agent_id
        ]);

        if($agent->agent_email_verification == Agent::EMAIL_NOT_VERIFIED)
        {
            $agent->sendVerificationEmail();

            return [
                "operation" => "success",
                "agent_id" => $agent->agent_id,
                "message" => Yii::t('agent', "Please click on the link sent to you by email to verify your account"),
                "unVerifiedToken" => $this->_loginResponse($agent)
            ];
        }

        return $this->_loginResponse ($agent);
    }

    /**
     * register user with store
     * @return mixed
     */
    public function actionSignup() {

        $currencyCode = Yii::$app->request->getBodyParam('currency');
        $accessToken = Yii::$app->request->getBodyParam('accessToken');
        $utm_id = Yii::$app->request->getBodyParam('utm_uuid');
        $token = Yii::$app->request->getBodyParam('token');

        //TODO: make token as required field once we update android app

        if(YII_ENV == 'prod') {
            $response = Yii::$app->reCaptcha->verify($token);

            if (!$response->data || !$response->data['success']) {
                return [
                    "operation" => "error",
                    "code" => 0,
                    "message" => Yii::t('agent', "Invalid captcha validation")
                ];
            }
        }

        $currency = Currency::findOne(['code' => $currencyCode]);
 
        $agent = new Agent();
        $agent->setScenario(Agent::SCENARIO_CREATE_NEW_AGENT);
        $agent->utm_uuid = Yii::$app->request->getBodyParam('utm_uuid');
        $agent->agent_name = Yii::$app->request->getBodyParam ('name');
        $agent->agent_email = Yii::$app->request->getBodyParam ('email');
        //$agent->setPassword(Yii::$app->request->getBodyParam ('password'));
        $agent->tempPassword = Yii::$app->request->getBodyParam ('password');

        if($accessToken) {

            $response = Yii::$app->auth0->getUserInfo ($accessToken);

            if ($response->isOk && $response->data['email']) {
                $agent->agent_email = $response->data['email'];
                $agent->agent_email_verification = Agent::EMAIL_VERIFIED;
            }
        }

        $store = new Restaurant();
        $store->version = Yii::$app->params['storeVersion'];
        $store->setScenario(Restaurant::SCENARIO_CREATE_STORE_BY_AGENT);
        $store->owner_number = Yii::$app->request->getBodyParam ('owner_number');
        $store->owner_phone_country_code= Yii::$app->request->getBodyParam ('owner_phone_country_code');
        $store->meta_description = Yii::$app->request->getBodyParam("meta_description");
        $store->meta_description_ar = Yii::$app->request->getBodyParam("meta_description_ar");

        $store->name = Yii::$app->request->getBodyParam ('restaurant_name');
        $store->business_type = Yii::$app->request->getBodyParam ('account_type');
        $store->restaurant_domain = Yii::$app->request->getBodyParam ('restaurant_domain');
        $store->country_id = Yii::$app->request->getBodyParam ('country_id');
        $store->currency_id = Yii::$app->request->getBodyParam('currency');
        $store->accept_order_247 = Yii::$app->request->getBodyParam('accept_order_247');
        
        $store->annual_revenue= Yii::$app->request->getBodyParam ('annual_revenue');

        $store->restaurant_email = $agent->agent_email;
        $store->owner_first_name = $agent->agent_name;
        $store->name_ar = $store->name;

        $transaction = Yii::$app->db->beginTransaction();

            if (!$agent->save()) {
                $transaction->rollBack();
                return [
                    "operation" => "error",
                    "message" => $agent->errors
                ];
            }

            $full_name = explode(' ', $agent->agent_name);
            $firstname = $full_name[0];
            $lastname = array_key_exists(1, $full_name) ? $full_name[1] : null;

            Yii::$app->eventManager->track('Agent Signup', [
                'first_name' => trim($firstname),
                'last_name' => trim($lastname),
                'store_name' => $store->name,
                'phone_number' => $store->owner_number,
                'email' => $agent->agent_email,
                'store_url' => $store->restaurant_domain,
                "country" => $store->country ? $store->country->country_name : null,
                "campaign" => $agent->campaign ? $agent->campaign->utm_campaign : null,
                "utm_medium" => $agent->campaign ? $agent->campaign->utm_medium : null,
                "profile_status" => "Active",
                "user_id" => $agent->agent_id
            ]);

            if (!$store->save()) {
                return [
                    "operation" => "error",
                    "message" => $store->errors
                ];
            }

            $response = $store->setupStore($agent);

            if($response['operation'] != 'success') {
                $transaction->rollBack();

                return $response;
            }

            if($utm_id) {
                $rbc = new RestaurantByCampaign();
                $rbc->restaurant_uuid = $store->restaurant_uuid;
                $rbc->utm_uuid = $utm_id;

                if (!$rbc->save()) {
                    $transaction->rollBack();
                    return [
                        "operation" => "error",
                        "message" => $rbc->errors
                    ];
                }
            }

            $transaction->commit();

            if($agent->agent_email_verification == Agent::EMAIL_NOT_VERIFIED)
            {
                $agent->sendVerificationEmail();

                return [
                    "operation" => "success",
                    "agent_id" => $agent->agent_id,
                    "message" => Yii::t('agent', "Please click on the link sent to you by email to verify your account"),
                    "unVerifiedToken" => $this->_loginResponse($agent)
                ];
            }

        /*} catch (\Exception $e) {
            $transaction->rollBack();
            return [
                "operation" => 'error',
                "message" => $e->getMessage()
            ];
        }*/

        return $this->_loginResponse ($agent);

    }
    
    /**
     * Update email address
     * @return type
     */
    public function actionUpdateEmail() {

        $unVerifiedToken = Yii::$app->request->getBodyParam("unVerifiedToken");
        $new_email = Yii::$app->request->getBodyParam("newEmail");
        $token = Yii::$app->request->getBodyParam('token');

        //TODO: make token as required field once we update android app

        if(YII_ENV == 'prod') {
            $response = Yii::$app->reCaptcha->verify($token);

            if (!$response->data || !$response->data['success']) {
                return [
                    "operation" => "error",
                    "code" => 0,
                    "message" => Yii::t('agent', "Invalid captcha validation")
                ];
            }
        }
        
        $agent = Agent::findIdentityByUnVerifiedTokenToken($unVerifiedToken);

        if (!$agent) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!$new_email) {
            return [
                "operation" => "error",
                "message" => Yii::t('agent', "Agent new email address required")
            ];
        }

        if ($new_email == $agent->agent_email || $new_email == $agent->agent_new_email) {
            return [
                "operation" => "error",
                "message" => Yii::t('agent', "Agent new email address is same as old email")
            ];
        }

        /**
         * Opt will expiry after 60 minutes, so user have to login back to update
         * email
         *
        if (!$agent->findByOtp($agent->otp, 60)) {
        return [
        "operation" => "error-session-expired",
        "message" => Yii::t('employer', "Session expired, please log back in")
        ];
        }*/

        $agent->scenario = AGENT::SCENARIO_UPDATE_EMAIL;

        if ($agent->agent_email_verification == Agent::EMAIL_VERIFIED) {
            $agent->agent_new_email = $new_email;
        } else  {
            $agent->agent_email = $new_email;
            $agent->agent_new_email = null;
        }

        if ($agent->save()) {

            //extend otp to fix: https://www.pivotaltracker.com/story/show/169037267

            //$agent->generateOtp();

            //to verify new email address 

            $agent->sendVerificationEmail();

            return [
                "operation" => "success",
                "message" => Yii::t('agent', "Agent Account Info Updated Successfully, please check email to verify new email address"),
                "unVerifiedToken" => $this->_loginResponse($agent)
            ];
        } else {
            return [
                "operation" => "error",
                "message" => $agent->errors
            ];
        }
    }
    
    /**
     * Re-send manual verification email to agent
     * @return array
     */
    public function actionResendVerificationEmail()
    {
        $emailInput = Yii::$app->request->getBodyParam("email");
        $token = Yii::$app->request->getBodyParam('token');

        //TODO: make token as required field once we update android app

        if(YII_ENV == 'prod') {
            $response = Yii::$app->reCaptcha->verify($token);

            if (!$response->data || !$response->data['success']) {
                return [
                    "operation" => "error",
                    "code" => 0,
                    "message" => Yii::t('agent', "Invalid captcha validation")
                ];
            }
        }

        $agent = Agent::find()
            ->andWhere(['deleted' => 0])
            ->andWhere([
                'OR',
                ['agent_email' => $emailInput],
                ['agent_new_email' => $emailInput],
            ])->one();

        $errors = false;
        $errorCode = null; //error code

        if ($agent) {

            if (empty($agent->agent_new_email) && $agent->agent_email_verification == Agent::EMAIL_VERIFIED) {
                return [
                    'operation' => 'error',
                    'errorCode' => 1,
                    'message' => Yii::t('agent', 'You have verified your email')
                ];
            }

            //Check if this user sent an email in past few minutes (to limit email spam)
            $emailLimitDatetime = new \DateTime($agent->agent_limit_email);
            date_add($emailLimitDatetime, date_interval_create_from_date_string('1 minutes'));
            $currentDatetime = new \DateTime();

            if ($agent->agent_limit_email && $currentDatetime < $emailLimitDatetime) {

                $difference = $currentDatetime->diff($emailLimitDatetime);
                $minuteDifference = (int) $difference->i;
                $secondDifference = (int) $difference->s;

                $errorCode = 2;

                $errors = Yii::t('agent', "Email was sent previously, you may request another one in {numMinutes, number} minutes and {numSeconds, number} seconds", [
                    'numMinutes' => $minuteDifference,
                    'numSeconds' => $secondDifference,
                ]);
            } else if ($agent->agent_email_verification == Agent::EMAIL_NOT_VERIFIED) {
                $agent->sendVerificationEmail();
            }


        } else {
            $errorCode = 3;
            $errors['email'] = [Yii::t('agent', 'Account not found')];
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
            'message' => Yii::t('agent', 'Please click on the link sent to you by email to verify your account'),
        ];
    }

    /**
     * Check if agent email already verified
     */
    public function actionIsEmailVerified() {

        $token = Yii::$app->request->getBodyParam("token");

        $model = AgentToken::find()
            ->andWhere(['token_value' => $token])
            ->one();

        if (!$model || !$model->agent) {
            return [
                'status' => 0
            ];
        }

        return [
            'status' => $model->agent->agent_new_email ? 0 : $model->agent->agent_email_verification
        ];
    }

    /**
     * Process email verification
     * @return array
     */
    public function actionVerifyEmail() {

        $code = Yii::$app->request->getBodyParam("code");
        $email = Yii::$app->request->getBodyParam("email");

        //check limit reached

        $totalInvalidAttempts = AgentEmailVerifyAttempt::find()
            ->andWhere([
                'agent_email' => $email,
                'ip_address' => Yii::$app->getRequest()->getUserIP()
            ])
            ->andWhere(new \yii\db\Expression("created_at >= DATE_SUB(NOW(),INTERVAL 1 HOUR)"))//last 1 hour
            ->count();

        if ($totalInvalidAttempts > 4) {
            return [
                'operation' => 'error',
                'message' => Yii::t('agent', 'You reached your limit to verify email. Please try again after an hour.')
            ];
        }

        $response = Agent::verifyEmail($email, $code);

        if ($response['success'] == false) {
            return [
                'operation' => 'error',
                'message' => $response['message']
            ];
        }

        if ($response['success'] == true) {
            //remove old email verification attempts

            AgentEmailVerifyAttempt::deleteAll([
                'agent_email' => $email,
                'ip_address' => Yii::$app->getRequest()->getUserIP()
            ]);

            //remove otp

            //$agent->otp = null;
            //$agent->save(false);

            return $this->_loginResponse($response['data']);
            
        } else {
            //add entry for invalid attempt

            $model = new AgentEmailVerifyAttempt;
            $model->code = $code;
            $model->agent_email = $email;
            $model->ip_address = Yii::$app->getRequest()->getUserIP();
            $model->save();

            return [
                'operation' => 'error',
                'message' => Yii::t('agent', 'Invalid email verification code.')
            ];
        }
    }

    /**
     * Sends password reset email to user
     * @return array
     */
    public function actionRequestResetPassword() {

        $emailInput = Yii::$app->request->getBodyParam("email");
        $token = Yii::$app->request->getBodyParam('token');

        //TODO: make token as required field once we update android app

        if(YII_ENV == 'prod') {
            $response = Yii::$app->reCaptcha->verify($token);

            if (!$response->data || !$response->data['success']) {
                return [
                    "operation" => "error",
                    "code" => 0,
                    "message" => Yii::t('agent', "Invalid captcha validation")
                ];
            }
        }

        $errors = false;
        $model = new PasswordResetRequestForm();
        $model->email = $emailInput;

        if (!$model->validate()) {
            return [
                'operation' => 'error',
                'message' => $model->getErrors()
            ];
        }

        $agent = Agent::findOne([
           'agent_email' => $model->email,
        ]);

        //Check if this user sent an email in past few minutes (to limit email spam)
        $emailLimitDatetime = new \DateTime($agent->agent_limit_email);
        date_add($emailLimitDatetime, date_interval_create_from_date_string('1 minutes'));
        $currentDatetime = new \DateTime('now');

        if ($agent->agent_limit_email && $currentDatetime < $emailLimitDatetime) {
            $difference = $currentDatetime->diff($emailLimitDatetime);
            $minuteDifference = (int) $difference->i;
            $secondDifference = (int) $difference->s;

            $errors = Yii::t('agent', "Email was sent previously, you may request another one in {numMinutes, number} minutes and {numSeconds, number} seconds", [
                'numMinutes' => $minuteDifference,
                'numSeconds' => $secondDifference,
            ]);
        } else if (!$model->sendEmail()) {
            $errors = Yii::t('agent', 'Sorry, we are unable to reset a password for email provided.');
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
            'message' => Yii::t ('agent', 'Please check the link sent to you on your email to set new password.')
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

        $agent = Agent::findByPasswordResetToken($token);

        if (!$agent) {
            return [
                'operation' => 'error',
                'message' => Yii::t ('agent', 'Invalid password reset token.')
            ];
        }
        if (!$newPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t ('agent', 'Password field required')
            ];
        }

        /*if (!$cPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t ('agent', 'Confirm Password field required')
            ];
        }

        if ($cPassword != $newPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t ('agent', 'Password & Confirm Password does not match')
            ];
        }*/

        $agent->setPassword($newPassword);
        $agent->removePasswordResetToken();

        /**
         * as password reset token will be sent to email and user will update password
         * from that link so if user have token he have valid email
         */

        $agent->agent_email_verification = Agent::EMAIL_VERIFIED;

        $agent->save(false);

        return $this->_loginResponse($agent);
    }

    /**
     * return user location detail by user ip address
     * @return type
     */
    public function actionLocate() {
        return Yii::$app->ipstack->locate();
    }

    /**
     * Return agent data after successful login
     * @param type $agent
     * @return type
     */
    private function _loginResponse($agent)
    {
        // Return Agent access token if everything valid

        $accessToken = $agent->accessToken->token_value;

        $assignment = $agent->getAgentAssignments()->one();

        /*if(!$assignment) {
          return [
              "operation" => "error",
              'message' => Yii::t ('agent', "You're not assigned to any store")
          ];
        }*/

        $selectedStore = null;

        if($assignment)
        {
            $selectedStore = $assignment->getRestaurant()
                ->select([
                    'restaurant_uuid',
                    'name',
                    'name_ar',
                    'restaurant_domain'
                ])
                ->one();
        }

        $stores = $agent->getAccountsManaged()
            ->select([
                'restaurant_uuid',
                'name',
                'name_ar',
                'restaurant_domain'
            ])
            ->all();

        return [
            "operation" => "success",
            "token" => $accessToken,
            "id" => $agent->agent_id,
            "username" => $agent->agent_id,
            "agent_name" => $agent->agent_name,
            "agent_email" => $agent->agent_email,
            "agent_new_email" => $agent->agent_new_email,
            "language_pref" => $agent->agent_language_pref,
            "role" =>  $assignment? (int) $assignment->role: null,
            "created_at" => strtotime($agent->agent_created_at),
            "selectedStore" => $selectedStore,
            "stores" => $stores
        ];
    }
}

