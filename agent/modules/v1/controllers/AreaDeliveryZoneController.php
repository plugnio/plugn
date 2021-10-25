<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\DeliveryZone;
use agent\models\Area;
use agent\models\AreaDeliveryZone;


class AreaDeliveryZoneController extends Controller
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
//        if(!Yii::$app->accountManager->isOwner() && !in_array ($action->id, ['list'])) {
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

    public function actionList()
    {
        $this->ownerCheck();
        $page = Yii::$app->request->get ('page');
        $keyword = Yii::$app->request->get ('keyword');
        $city_id = Yii::$app->request->get ('city_id');
        $delivery_zone_id = Yii::$app->request->get ('delivery_zone_id');

        //validate

        Yii::$app->accountManager->getManagedAccount ();

        $query = AreaDeliveryZone::find ()
            ->andWhere (['delivery_zone_id' => $delivery_zone_id]);

        if ($city_id) {
            $query->andWhere(['city_id' => $city_id]);
        }

        if ($keyword) {
            $query->andWhere ([
                'OR',
                ['like', 'area_name', $keyword],
                ['like', 'area_name_ar', $keyword]
            ]);
        }

        if (!$page) {
            return new ActiveDataProvider([
                'query' => $query,
                'pagination' => false
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * save delivery zone areas
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionSaveDetails()
    {
        $this->ownerCheck();
        $store_uuid = Yii::$app->request->getBodyParam ("store_uuid");
        $areas = Yii::$app->request->getBodyParam ("areas");
        $delivery_zone_id = Yii::$app->request->getBodyParam ("delivery_zone_id");

        $store = Yii::$app->accountManager->getManagedAccount($store_uuid );


        $delivery_zone = $store->getDeliveryZones()
            ->andWhere (['delivery_zone_id' => $delivery_zone_id])
            ->one();


        if (!$delivery_zone)
            throw new NotFoundHttpException('The requested record does not exist.');

        //delete extra areas saved before
        $area_id = array_column($areas, 'area_id');
        AreaDeliveryZone::deleteAll(['delivery_zone_id' => $delivery_zone_id]);

        //list already added areas

        $addedAreas = $store->getAreaDeliveryZones ()
            ->andWhere (['delivery_zone_id' => $delivery_zone_id])
            ->all ();

        $addedAreaIds = ArrayHelper::getColumn ($addedAreas, 'area_id');

        //add new areas

        $arrDeliveryAreas = [];

        foreach ($areas as $area) {
            //skip if already added
            if (!in_array ($area, $addedAreaIds)) {
                $arrDeliveryAreas[] = [
                    'restaurant_uuid' => $store->restaurant_uuid,
                    'delivery_zone_id' => $delivery_zone_id,
                    'country_id' => $delivery_zone->country_id,
                    'city_id' => $area['city_id'],
                    'area_id' => $area['area_id']
                ];
            }
        }

        if (sizeof ($arrDeliveryAreas) > 0) {
            Yii::$app->db->createCommand ()->batchInsert ('area_delivery_zone',
                ['restaurant_uuid', 'delivery_zone_id', 'country_id', 'city_id', 'area_id'],
                $arrDeliveryAreas
            )->execute ();
        }

        return [
            "operation" => "success",
            "message" => Yii::t ('agent', "Delivery areas updated successfully")
        ];
    }

    /**
     * Create AreaDelivery Zone
     * @return array
     */
    public function actionCreate()
    {
        $this->ownerCheck();
        $store_uuid = Yii::$app->request->getBodyParam ("store_uuid");
        $area_id = Yii::$app->request->getBodyParam ("area_id");
        $store_model = Yii::$app->accountManager->getManagedAccount ($store_uuid);

        $delivery_zone_id = Yii::$app->request->getBodyParam ("delivery_zone_id");

        $delivery_zone_model = DeliveryZone::findOne (['delivery_zone_id' => $delivery_zone_id, 'restaurant_uuid' => $store_model->restaurant_uuid]);

        if (!$delivery_zone_model)
            throw new NotFoundHttpException('The requested record does not exist.');

        $area_model = Area::findOne (['area_id' => $area_id]);

        if (!$area_model)
            throw new NotFoundHttpException('The requested record does not exist.');


        $model = new AreaDeliveryZone();
        $model->restaurant_uuid = $store_model->restaurant_uuid;
        $model->delivery_zone_id = $delivery_zone_model->delivery_zone_id;
        $model->area_id = $area_model->area_id;

        if (!$model->save ()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t ('agent', "Area Delivery Zone created successfully"),
            "model" => AreaDeliveryZone::findOne ($model->area_delivery_zone)
        ];
    }

    /**
     * Update  AreaDelivery Zone
     */
    public function actionUpdate($area_delivery_zone_id, $store_uuid)
    {
        $this->ownerCheck();
        $store_uuid = Yii::$app->request->getBodyParam ("store_uuid");
        $area_id = Yii::$app->request->getBodyParam ("area_id");
        $store_model = Yii::$app->accountManager->getManagedAccount ($store_uuid);


        $delivery_zone_id = Yii::$app->request->getBodyParam ("delivery_zone_id");

        $delivery_zone_model = DeliveryZone::findOne (['delivery_zone_id' => $delivery_zone_id, 'restaurant_uuid' => $store_model->restaurant_uuid]);

        if (!$delivery_zone_model)
            throw new NotFoundHttpException('The requested record does not exist.');

        $area_model = Area::findOne (['area_id' => $area_id]);

        if (!$area_model)
            throw new NotFoundHttpException('The requested record does not exist.');


        $model = $this->findModel ($area_delivery_zone_id, $store_uuid);

        $model->delivery_zone_id = $delivery_zone_model->delivery_zone_id;
        $model->area_id = $area_model->area_id;

        if (!$model->save ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t ('agent', "We've faced a problem updating the delivery zone")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t ('agent', "Area Delivery Zone updated successfully"),
            "model" => $model
        ];
    }

    /**
     * Delete Delivery zone
     */
    public function actionDelete($area_delivery_zone_id, $store_uuid)
    {
        $this->ownerCheck();
        $model = $this->findModel ($area_delivery_zone_id, $store_uuid);
        if (!$model->delete ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t ('agent', "We've faced a problem deleting the area delivery zone")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t ('agent', "Area Delivery Zone deleted successfully")
        ];
    }

    /**
     * Finds the Delivery zone model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusinessLocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($area_delivery_zone_id, $store_uuid)
    {
        $store_model = Yii::$app->accountManager->getManagedAccount ($store_uuid);

        $model = AreaDeliveryZone::find ()->where ([
            'area_delivery_zone' => $area_delivery_zone_id,
            'restaurant_uuid' => $store_model->restaurant_uuid
        ])->one ();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
