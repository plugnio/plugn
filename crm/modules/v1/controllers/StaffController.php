<?php

namespace staff\modules\v1\controllers;


use Yii;
use yii\rest\Controller;


class StaffController extends Controller
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
    public function actionUpdateStaffProfile($store_uuid = null)
    {
        $model = Yii::$app->user->identity;

        $email = Yii::$app->request->getBodyParam ("staff_email");

        if($email != $model->staff_email) {
            $model->staff_new_email = $email;
        }

        $model->staff_name = Yii::$app->request->getBodyParam ("staff_name");

        if (!$model->save ()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        $model->sendVerificationEmail();

        return [
            'model' => $model,
            "operation" => "success",
            "message" => Yii::t('staff', "Staff profile updated successfully")
        ];
    }

    /**
     * change password
     */
    public function actionChangePassword()
    {
        $staff = Yii::$app->user->identity;

        $oldPassword = Yii::$app->request->getBodyParam ("oldPassword");
        $newPassword = Yii::$app->request->getBodyParam ("newPassword");
        $confirmPassword = Yii::$app->request->getBodyParam ("confirmPassword");

        if (!$oldPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t('staff', 'Old Password field required')
            ];
        }

        if (!$confirmPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t('staff','Confirm Password field required')
            ];
        }

        if (!$newPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t('staff','Password field required')
            ];
        }

        if ($confirmPassword != $newPassword) {
            return [
                'operation' => 'error',
                'message' => Yii::t('staff','Password not matching')
            ];
        }

        if (!$staff->validatePassword ($oldPassword)) {
            return [
                'operation' => 'error',
                'message' => Yii::t('staff', 'Old Password not valid')
            ];
        }

        $staff->setPassword ($newPassword);
        $staff->save (false);

        return [
            "operation" => "success",
            "message" => Yii::t('staff', "Account Password Updated Successfully")
        ];
    }


}
