<?php

namespace agent\modules\v1\controllers;

use agent\models\AreaDeliveryZone;
use agent\models\DeliveryZone;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\BusinessLocation;


class BusinessLocationController extends BaseController
{

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
     * @param $store_uuid
     * @return ActiveDataProvider
     */
    public function actionList($store_uuid = null)
    {
//        $this->ownerCheck();
        $keyword = Yii::$app->request->get ('keyword');
        $support_pick_up = Yii::$app->request->get ('support_pick_up');
        $list_all = Yii::$app->request->get ('list_all');

        $store = Yii::$app->accountManager->getManagedAccount ($store_uuid);

        $query = BusinessLocation::find()->joinWith('country');

        if ($keyword) {
            $query->andWhere('or', [
                ['like', 'business_location_name', $keyword],
                ['like', 'business_location_name_ar', $keyword],
                ['like', 'country.country_name', $keyword],
                ['like', 'country.country_name_ar', $keyword]
            ]);
        }

        if($support_pick_up) {
            $query->andWhere(['support_pick_up' => $support_pick_up]);
        }

        $query->andWhere (['restaurant_uuid' => $store->restaurant_uuid])
            ->orderBy('business_location_id DESC');

        $params = $list_all?  [
            'query' => $query,
            'pagination' => false
        ]: [
            'query' => $query,
        ];

        return new ActiveDataProvider($params);
    }

    /**
     * Create Business Location
     * @return array
     */
    public function actionCreate()
    {
//        $this->ownerCheck();
        $store = Yii::$app->accountManager->getManagedAccount ();

        $model = new BusinessLocation();
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->country_id = Yii::$app->request->getBodyParam ("country_id");
        $model->business_location_name = Yii::$app->request->getBodyParam ("business_location_name");
        $model->business_location_name_ar = Yii::$app->request->getBodyParam ("business_location_name_ar");
        $model->support_pick_up = (int)Yii::$app->request->getBodyParam ("support_pick_up");
        $model->business_location_tax = (double)Yii::$app->request->getBodyParam ("business_location_tax");
        $model->mashkor_branch_id = Yii::$app->request->getBodyParam ("mashkor_branch_id");
        $model->armada_api_key = Yii::$app->request->getBodyParam ("armada_api_key");
        $model->address = Yii::$app->request->getBodyParam ("address");
        $model->latitude = Yii::$app->request->getBodyParam ("latitude");
        $model->longitude = Yii::$app->request->getBodyParam ("longitude");
        $model->max_num_orders = Yii::$app->request->getBodyParam ("max_num_orders");

        if (!$model->save ()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => "Business Location created successfully",
            "model" => BusinessLocation::findOne ($model->business_location_id)
        ];
    }

    /**
     * Update Business Location
     */
    public function actionUpdate($business_location_id, $store_uuid = null)
    {
//        $this->ownerCheck();
        $model = $this->findModel ($business_location_id, $store_uuid);

        $model->country_id = Yii::$app->request->getBodyParam ("country_id");
        $model->business_location_name = Yii::$app->request->getBodyParam ("business_location_name");
        $model->business_location_name_ar = Yii::$app->request->getBodyParam ("business_location_name_ar");
        $model->support_pick_up = (int)Yii::$app->request->getBodyParam ("support_pick_up");
        $model->business_location_tax = (double)Yii::$app->request->getBodyParam ("business_location_tax");
        $model->mashkor_branch_id = Yii::$app->request->getBodyParam ("mashkor_branch_id");
        $model->armada_api_key = Yii::$app->request->getBodyParam ("armada_api_key");
        $model->address = Yii::$app->request->getBodyParam ("address");
        $model->latitude = Yii::$app->request->getBodyParam ("latitude");
        $model->longitude = Yii::$app->request->getBodyParam ("longitude");
        $model->max_num_orders = Yii::$app->request->getBodyParam ("max_num_orders");

        if (!$model->save ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem updating the business location")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Business Location details updated successfully"),
            "model" => $model
        ];
    }

    /**
     * Return Business Location detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($business_location_id, $store_uuid = null)
    {
//        $this->ownerCheck();
        return $this->findModel ($business_location_id, $store_uuid);
    }

    /**
     * Delete Business Location
     */
    public function actionDelete($business_location_id, $store_uuid = null)
    {
        $this->ownerCheck();

        Yii::$app->accountManager->getManagedAccount ($store_uuid);
        
        $transaction = Yii::$app->db->beginTransaction();

        $model = $this->findModel ($business_location_id, $store_uuid);

        $model->setScenario(BusinessLocation::SCENARIO_DELETE);

        $model->is_deleted = 1;

        if (!$model->save ()) {

            $transaction->rollBack();

            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem deleting the business location")
                ];
            }
        }

        //delete delivery zone areas

        foreach ($model->deliveryZones as $deliveryZone) {
            AreaDeliveryZone::deleteAll([
                'is_deleted' => 0,
            ],[
                'delivery_zone_id'=> $deliveryZone->delivery_zone_id
            ]);
        }

        //delete delivery zones

        DeliveryZone::updateAll([
            'is_deleted' => 0,
        ],[
            'business_location_id' => $model->business_location_id
        ]);

        $transaction->commit();

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Business location deleted successfully")
        ];
    }

    /**
     * Finds the Business Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusinessLocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($business_location_id, $store_uuid = null)
    {
        $store = Yii::$app->accountManager->getManagedAccount ($store_uuid);

        $model = BusinessLocation::find()
            ->where([
                'business_location_id' => $business_location_id,
                'restaurant_uuid' => $store->restaurant_uuid
            ])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
