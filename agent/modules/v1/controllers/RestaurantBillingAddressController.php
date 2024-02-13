<?php

namespace agent\modules\v1\controllers;

use Yii;
use common\models\RestaurantBillingAddress;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class RestaurantBillingAddressController extends BaseController
{
    /**
     * Get all web links
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionList($store_uuid = null)
    {
        $keyword = Yii::$app->request->get ('keyword');

        $store = Yii::$app->accountManager->getManagedAccount ($store_uuid);

        $query = RestaurantBillingAddress::find ();

        if ($keyword) {
            $query->where (['like', 'recipient_name', $keyword]);
            $query->orWhere (['like', 'address_1', $keyword]);
            $query->orWhere (['like', 'address_2', $keyword]);
        }

        $query->andWhere (['restaurant_uuid' => $store->restaurant_uuid]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Create Restaurant Billing Address
     * @return array
     */
    public function actionCreate()
    {
        $store_uuid = Yii::$app->request->getBodyParam ("store_uuid");

        $store = Yii::$app->accountManager->getManagedAccount ($store_uuid);

        $model = new RestaurantBillingAddress();
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->country_id = Yii::$app->request->getBodyParam ("country_id");
        $model->recipient_name = Yii::$app->request->getBodyParam ("recipient_name");
        $model->address_1 = Yii::$app->request->getBodyParam ("address_1");
        $model->address_2 = Yii::$app->request->getBodyParam ("address_2");
        $model->po_box = Yii::$app->request->getBodyParam ("po_box");
        $model->district = Yii::$app->request->getBodyParam ("district");
        $model->city = Yii::$app->request->getBodyParam ("city");
        $model->state = Yii::$app->request->getBodyParam ("state");
        $model->zip_code = Yii::$app->request->getBodyParam ("zip_code");
        
        if (!$model->save ()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Restaurant Billing Address created successfully"),
            "model" => RestaurantBillingAddress::findOne ($model->rba_uuid)
        ];
    }

    /**
     * Update Restaurant Billing Address
     */
    public function actionUpdate($rba_uuid, $store_uuid = null)
    {
        $model = $this->findModel ($rba_uuid, $store_uuid);

        $model->country_id = Yii::$app->request->getBodyParam ("country_id");
        $model->recipient_name = Yii::$app->request->getBodyParam ("recipient_name");
        $model->address_1 = Yii::$app->request->getBodyParam ("address_1");
        $model->address_2 = Yii::$app->request->getBodyParam ("address_2");
        $model->po_box = Yii::$app->request->getBodyParam ("po_box");
        $model->district = Yii::$app->request->getBodyParam ("district");
        $model->city = Yii::$app->request->getBodyParam ("city");
        $model->state = Yii::$app->request->getBodyParam ("state");
        $model->zip_code = Yii::$app->request->getBodyParam ("zip_code");

        if (!$model->save ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem updating the Restaurant Billing Address")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Restaurant Billing Address updated successfully"),
            "model" => $model
        ];
    }
    
    /**
     * Return detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($store_uuid = null, $rba_uuid)
    {
        return $this->findModel ($rba_uuid, $store_uuid);
    }

    /**
     * Delete Restaurant Billing Address
     */
    public function actionDelete($rba_uuid, $store_uuid = null)
    {
        $model = $this->findModel ($rba_uuid, $store_uuid);

        if (!$model->delete ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem deleting Restaurant Billing Address")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Restaurant Billing Address deleted successfully")
        ];
    }

    /**
     * Finds the Restaurant Billing Address model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $store_uuid = null)
    {
        $store = Yii::$app->accountManager->getManagedAccount ($store_uuid);

        $model = RestaurantBillingAddress::find ()
            ->andWhere ([
                'rba_uuid' => $id,
                'restaurant_uuid' => $store->restaurant_uuid
            ])
            ->one ();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}