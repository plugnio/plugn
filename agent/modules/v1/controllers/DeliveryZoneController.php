<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\DeliveryZone;
use agent\models\AreaDeliveryZone;
use agent\models\BusinessLocation;


class DeliveryZoneController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
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
            'class' => HttpBearerAuth::className(),
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
     * only owner will have access
     */
//    public function beforeAction($action)
//    {
//        parent::beforeAction ($action);
//
//        if($action->id == 'options') {
//            return true;
//        }
//
//        if(!Yii::$app->accountManager->isOwner()) {
//            throw new \yii\web\BadRequestHttpException(
//                Yii::t('agent', 'You are not allowed to manage business locations. Please contact with store owner')
//            );
//
//            return false;
//        }
//
//        //should have access to store
//
//        Yii::$app->accountManager->getManagedAccount();
//
//        return true;
//    }

    private function ownerCheck()
    {
        if (!Yii::$app->accountManager->isOwner()) {
            throw new \yii\web\BadRequestHttpException(
                Yii::t('agent', 'You are not allowed to manage discounts. Please contact with store owner')
            );
        }

        //should have access to store
        Yii::$app->accountManager->getManagedAccount();
        return true;
    }

    /**
     * Get all delivery zones
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionList($store_uuid = null, $business_location_id)
    {
//        $this->ownerCheck();
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

            $query = DeliveryZone::find()
                ->andWhere([
                    'restaurant_uuid' => $store->restaurant_uuid,
                    'business_location_id' => $business_location_id
                ]);

            return new ActiveDataProvider([
                'query' => $query
            ]);
    }

    /**
     * Create Delivery zone
     * @return array
     */
    public function actionCreate()
    {
//        $this->ownerCheck();
        $store_uuid = Yii::$app->request->getBodyParam("store_uuid");

        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $business_location_id = Yii::$app->request->getBodyParam("business_location_id");

        $business_location_model = BusinessLocation::findOne([
            'business_location_id' => $business_location_id,
            'restaurant_uuid' => $store->restaurant_uuid
        ]);

        if (!$business_location_model)
            throw new NotFoundHttpException('business location not found.');

        $model = new DeliveryZone();
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->business_location_id = $business_location_model->business_location_id;
        $model->country_id = Yii::$app->request->getBodyParam("country_id");
        $model->delivery_time = (int)Yii::$app->request->getBodyParam("delivery_time");
        $model->time_unit = Yii::$app->request->getBodyParam("time_unit");
        $model->delivery_fee = (float)Yii::$app->request->getBodyParam("delivery_fee");
        $model->min_charge = (float)Yii::$app->request->getBodyParam("min_charge");
        $model->delivery_zone_tax = (float)Yii::$app->request->getBodyParam("delivery_zone_tax");


        if ($model->save()) {

          if($model->country->getAreas()->count() == 0){

            $area_delivery_zone_model = new AreaDeliveryZone();
            $area_delivery_zone_model->delivery_zone_id = $model->delivery_zone_id;
            $area_delivery_zone_model->country_id = $model->country_id;
            $area_delivery_zone_model->restaurant_uuid = $store->restaurant_uuid;
            $area_delivery_zone_model->save(false);

          }


        } else {
          return [
              "operation" => "error",
              "message" => $model->errors
          ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Delivery Zone created successfully"),
            "model" => DeliveryZone::findOne($model->delivery_zone_id)
        ];

    }

    /**
     * Update Delivery Zone
     */
    public function actionUpdate($delivery_zone_id, $store_uuid = null)
    {
//        $this->ownerCheck();
        $store_model = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $business_location_id = Yii::$app->request->getBodyParam("business_location_id");

        $business_location_model = BusinessLocation::findOne(['business_location_id' => $business_location_id, 'restaurant_uuid' => $store_model->restaurant_uuid]);

        if (!$business_location_model)
            throw new NotFoundHttpException('The business location record does not exist.');

        $model = $this->findModel($delivery_zone_id, $store_uuid);

        $model->country_id = Yii::$app->request->getBodyParam("country_id");
        $model->business_location_id = $business_location_model->business_location_id;
        $model->delivery_time = (int)Yii::$app->request->getBodyParam("delivery_time");
        $model->time_unit = Yii::$app->request->getBodyParam("time_unit");
        $model->delivery_fee = (float)Yii::$app->request->getBodyParam("delivery_fee");
        $model->min_charge = (float)Yii::$app->request->getBodyParam("min_charge");
        $model->delivery_zone_tax = (float)Yii::$app->request->getBodyParam("delivery_zone_tax");


        if (!$model->save()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem updating the delivery zone")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Delivery zone updated successfully"),
            "model" => $model
        ];
    }

    /**
     * Return Delivery zone detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($store_uuid = null, $delivery_zone_id)
    {
//        $this->ownerCheck();
        return $this->findModel($delivery_zone_id, $store_uuid);
    }

    /**
     * Delete Delivery zone
     */
    public function actionDelete($delivery_zone_id, $store_uuid = null)
    {
        $this->ownerCheck();
        $transaction = Yii::$app->db->beginTransaction();

        AreaDeliveryZone::deleteAll(['delivery_zone_id'=> $delivery_zone_id, 'restaurant_uuid' => $store_uuid]);
        $model = $this->findModel($delivery_zone_id, $store_uuid);

        if (!$model->delete()) {
            $transaction->rollBack();
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem deleting the delivery zone")
                ];
            }
        }

        $transaction->commit();
        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Delivery Zone deleted successfully")
        ];
    }

    /**
     * cancel-override Delivery zone
     */
    public function actionCancelOverride($delivery_zone_id, $store_uuid = null)
    {
        $this->ownerCheck();
        $model = $this->findModel($delivery_zone_id, $store_uuid);
        $model->delivery_zone_tax = null;
        if (!$model->save()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem cancelling VAT Charged")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Delivery Zone VAT Charged cancelled successfully")
        ];
    }

    /**
     * Finds the Delivery zone model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusinessLocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($delivery_zone_id, $store_uuid = null)
    {
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = DeliveryZone::find()->where([
                'delivery_zone_id' => $delivery_zone_id,
                'restaurant_uuid' => $store->restaurant_uuid
            ])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The delivery zone record does not exist.');
        }
    }
}
