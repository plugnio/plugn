<?php

namespace api\modules\v2\controllers;

use common\models\Customer;
use Yii;
use yii\rest\Controller;


class AccountController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

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
    public function actions()
    {
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
     * return user profile
     * @return \yii\web\IdentityInterface|null
     */
    public function actionDetail()
    {
        return Customer::find()
            ->andWhere(['customer_id' => Yii::$app->user->getId()])
            ->one();
    }

    /**
     * soft delete account
     * @return array
     */
    public function actionDelete()
    {
        $model = Yii::$app->user->identity;

        $model->setScenario(Customer::SCENARIO_DELETE);
        $model->deleted = true;

        if (!$model->save ()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            'model' => Yii::t('customer', "Profile deleted successfully"),
            "operation" => "success",
        ];
    }

    /**
     * @return array
     */
    public function actionUpdate()
    {
        $store_id = Yii::$app->request->getHeaders()->get('Store-Id');

        $email = Yii::$app->request->getBodyParam ("email");

        $model = Yii::$app->user->identity;

        if($email != $model->customer_email) {
            $model->customer_new_email = $email;
        }

        $model->customer_name = Yii::$app->request->getBodyParam("first_name") . ' '
            . Yii::$app->request->getBodyParam("last_name");

        $model->customer_phone_number = Yii::$app->request->getBodyParam("phone_number");
        $model->country_code = Yii::$app->request->getBodyParam("country_code");

        if($model->country_code && $model->customer_phone_number) {
            $model->customer_phone_number = "+" . $model->country_code . $model->customer_phone_number;
        }

        if (!$model->save ()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        //if new email

        $message = Yii::t('customer', "Customer profile updated successfully");

        if($model->customer_new_email)
        {
            $model->sendVerificationEmail($store_id);

            $message = Yii::t('agent', "Please click on the link sent to you by email to verify your account");
        }

        return [
            "operation" => "success",
            "model" => $model,
            "message" => $message
        ];
    }
}
