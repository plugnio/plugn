<?php

namespace agent\modules\v1\controllers;

use agent\models\Restaurant;
use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\models\Store;

class StoreController extends Controller
{

    public function behaviors()
    {
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
     * Return an overview of the store details
     * @param type $store_uuid
     */
    public function actionDetail($store_uuid)
    {
        if ($store = Yii::$app->accountManager->getManagedAccount($store_uuid)) {
            return Restaurant::findOne($store_uuid);
        }
    }

    /**
     * Return an overview of the store details
     * @param type $store_uuid
     */
    public function actionUpdate($store_uuid)
    {
        if ($store = Yii::$app->accountManager->getManagedAccount($store_uuid)) {
            $store->country_id = Yii::$app->request->getBodyParam('country_id');
            $store->restaurant_email_notification = Yii::$app->request->getBodyParam('email_notification');
            $store->phone_number = Yii::$app->request->getBodyParam('mobile');
            $store->phone_number_country_code = Yii::$app->request->getBodyParam('mobile_country_code');
            $store->name = Yii::$app->request->getBodyParam('name');
            $store->name_ar = Yii::$app->request->getBodyParam('name_ar');
            $store->schedule_interval = Yii::$app->request->getBodyParam('schedule_interval');
            $store->schedule_order = Yii::$app->request->getBodyParam('schedule_order');
            $store->restaurant_email = Yii::$app->request->getBodyParam('store_email');
            $store->tagline = Yii::$app->request->getBodyParam('tagline');
            $store->tagline_ar = Yii::$app->request->getBodyParam('tagline_ar');

            if (!$store->save()) {
                return [
                    'operation' => 'error',
                    'message' => $store->getErrors()
                ];
            }

            return [
                'operation' => 'success',
                'message' => 'Store details updated successfully'
            ];
        }
    }


    /**
     * Displays  Real time orders
     *
     * @return mixed
     */
    public function actionConnectDomain()
    {
        $domain = Yii::$app->request->getBodyParam('domain');

        $store = Yii::$app->accountManager->getManagedAccount();

        if ($store->restaurant_domain == $domain) {
            return [
                'operation' => 'error',
                'message' => 'New domain can not be same as old domain'
            ];
        }

        $old_domain = $store->restaurant_domain;

        $store->setScenario(Restaurant::SCENARIO_CONNECT_DOMAIN);

        $store->restaurant_domain = rtrim($domain, '/');

        if (!$store->save()) {
            return [
                'operation' => 'error',
                'message' => $store->getErrors()
            ];
        }

        \Yii::$app->mailer->compose([
                'html' => 'domain-update-request',
            ], [
                'store_name' => $store->name,
                'new_domain' => $store->restaurant_domain,
                'old_domain' => $old_domain
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo(Yii::$app->params['supportEmail'])
            ->setSubject('[Plugn] Agent updated DN')
            ->send();

        return [
            'operation' => 'success',
            "message" => "Congratulations you have successfully changed your domain name"
        ];
    }
}
