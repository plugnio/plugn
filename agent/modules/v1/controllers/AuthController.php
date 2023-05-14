<?php

namespace agent\modules\v1\controllers;

use agent\models\AgentToken;
use agent\models\Currency;
use agent\models\PaymentMethod;
use agent\models\Restaurant;
use common\models\AgentAssignment;
use common\models\AgentEmailVerifyAttempt;
use common\models\BusinessLocation;
use common\models\Category;
use common\models\RestaurantPaymentMethod;
use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBasicAuth;
use agent\models\Agent;
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
            'login-auth0'
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
                "message" => Yii::t('candidate',"Please click the verification link sent to you by email to activate your account"),
                "unVerifiedToken" => $this->_loginResponse($agent)
            ];
        }

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

        return $this->_loginResponse($agent);
    }

    /**
     * signup
     * @return array|string[]
     */
    public function actionSignupStepOne()
    {
        $agent = new Agent();
        $agent->setScenario(Agent::SCENARIO_CREATE_NEW_AGENT);
        $agent->agent_name = Yii::$app->request->getBodyParam('name');
        $agent->agent_email = Yii::$app->request->getBodyParam('email');
        $agent->setPassword(Yii::$app->request->getBodyParam('password'));
        $agent->tempPassword = Yii::$app->request->getBodyParam ('password');

        if (!$agent->validate()) {
            return [
                "operation" => "error",
                "message" => $agent->errors
            ];
        } else {

            if (YII_ENV == 'prod') {
                $param = [
                    'email' => Yii::$app->request->getBodyParam('email'),
                    'password' => Yii::$app->request->getBodyParam('password')
                ];
                Yii::$app->auth0->createUser($param);
            }

            return [
                "operation" => "success"
            ];
        }
    }

    /**
     * register user with store
     * @return mixed
     */
    public function actionSignup() {

        $currencyCode = Yii::$app->request->getBodyParam('currency');
        $accessToken = Yii::$app->request->getBodyParam('accessToken');

        $currency = Currency::findOne(['code' => $currencyCode]);

        $agent = new Agent();
        $agent->setScenario(Agent::SCENARIO_CREATE_NEW_AGENT);
        $agent->agent_name = Yii::$app->request->getBodyParam ('name');
        $agent->agent_email = Yii::$app->request->getBodyParam ('email');
        $agent->setPassword(Yii::$app->request->getBodyParam ('password'));
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

            if (!$store->save()) {
                return [
                    "operation" => "error",
                    "message" => $store->errors
                ];
            }

            //Create a catrgory for a store by default named "Products". so they can get started adding products without having to add category first

            $category = new Category();
            $category->restaurant_uuid = $store->restaurant_uuid;
            $category->title = 'Products';
            $category->title_ar = 'منتجات';

            if (!$category->save()) {
                $transaction->rollBack();
                return [
                    "operation" => "error",
                    "message" => $category->errors
                ];
            }

            //Create a business Location for a store by default named "Main Branch".
            $business_location = new BusinessLocation();
            $business_location->restaurant_uuid = $store->restaurant_uuid;
            $business_location->country_id = $store->country_id;
            $business_location->support_pick_up = 1;
            $business_location->business_location_name = 'Main Branch';
            $business_location->business_location_name_ar = 'الفرع الرئيسي';

            if (!$business_location->save()) {
                $transaction->rollBack();

                return [
                    "operation" => "error",
                    "message" => $business_location->errors
                ];
            }

            //Enable cash by default

            $paymentMethod = PaymentMethod::find()
                ->andWhere(['payment_method_code' => PaymentMethod::CODE_CASH])
                ->one();

            if(!$paymentMethod) {
                $paymentMethod = new PaymentMethod();
                $paymentMethod->payment_method_code =  PaymentMethod::CODE_CASH;
                $paymentMethod->payment_method_name = "Cash on delivery";
                $paymentMethod->payment_method_name_ar = "الدفع عند الاستلام";
                $paymentMethod->vat = 0;
                $paymentMethod->save(false);
            }

            $payments_method = new RestaurantPaymentMethod();
            $payments_method->payment_method_id = $paymentMethod->payment_method_id; //Cash
            $payments_method->restaurant_uuid = $store->restaurant_uuid;
            
            if (!$payments_method->save()) {
                $transaction->rollBack();
                return [
                    "operation" => "error",
                    "message" => $payments_method->errors
                ];
            }

            $assignment_agent = new AgentAssignment();
            $assignment_agent->agent_id = $agent->agent_id;
            $assignment_agent->assignment_agent_email = $agent->agent_email;
            $assignment_agent->role = AgentAssignment::AGENT_ROLE_OWNER;
            $assignment_agent->restaurant_uuid = $store->restaurant_uuid;
            $assignment_agent->business_location_id = $business_location->business_location_id;
            
            if (!$assignment_agent->save()) {
                $transaction->rollBack();
                return [
                    "operation" => "error",
                    "message" => $assignment_agent->errors
                ];
            }

            \Yii::info("[New Store Signup] " . $store->name . " has just joined Plugn", __METHOD__);

            if (YII_ENV == 'prod') {

                $full_name = explode(' ', $agent->agent_name);
                $firstname = $full_name[0];
                $lastname = array_key_exists(1, $full_name) ? $full_name[1] : null;

                Yii::$app->eventManager->track('Store Created', [
                        'first_name' => trim($firstname),
                        'last_name' => trim($lastname),
                        'store_name' => $store->name,
                        'phone_number' => $store->owner_phone_country_code . $store->owner_number,
                        'email' => $agent->agent_email,
                        'store_url' => $store->restaurant_domain
                    ],
                    null,
                    $agent->agent_id
                );

                $param = [
                    'email' => Yii::$app->request->getBodyParam('email'),
                    'password' => Yii::$app->request->getBodyParam('password')
                ];
                
                Yii::$app->auth0->createUser($param);

                //https://hooks.zapier.com/hooks/catch/3784096/oeap6qy

                Yii::$app->zapier->webhook("https://hooks.zapier.com/hooks/catch/3784096/366cqik", [
                    'first_name' => trim($firstname),
                    'last_name' => trim($lastname),
                    'store_name' => $store->name,
                    'phone_number' => $store->owner_phone_country_code . $store->owner_number,
                    'email' => $agent->agent_email,
                    'store_url' => $store->restaurant_domain
                ]);
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

        $agent = Agent::find()->andWhere([
            'OR',
            ['agent_email' => $emailInput],
            ['agent_new_email' => $emailInput],
        ])->one();

        $errors = false;
        $errorCode = null; //error code

        if ($agent) {

            if ($agent->agent_email_verification == Agent::EMAIL_VERIFIED) {
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
     * Check if candidate email already verified
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
     * Return agent data after successful login
     * @param type $agent
     * @return type
     */
    private function _loginResponse($agent)
    {
        // Return Agent access token if everything valid

        $accessToken = $agent->accessToken->token_value;

        $assignment = $agent->getAgentAssignments()->one();

        if(!$assignment) {
          return [
              "operation" => "error",
              'message' => Yii::t ('agent', "You're not assigned to any store")
          ];
        }

        $selectedStore = $assignment->getRestaurant()
            ->select([
                'restaurant_uuid',
                'name',
                'name_ar',
                'restaurant_domain'
            ])
            ->one();

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
            "role" => (int) $assignment->role,
            "selectedStore" => $selectedStore,
            "stores" => $stores
        ];
    }
}

