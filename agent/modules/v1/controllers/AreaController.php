<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\Area;


class AreaController extends BaseController {

    /**
     * @return ActiveDataProvider
     */
    public function actionList() {

        $keyword = Yii::$app->request->get('keyword');
        $city_id = Yii::$app->request->get('city_id');
        $store_id = Yii::$app->request->get('store_id');

        //valdiate access
        if ($store_id) {
            Yii::$app->accountManager->getManagedAccount($store_id);
        }

        $query =  Area::find();

        if ($city_id) {
            $query->andWhere(['city_id' => $city_id]);
        }

        if ($store_id) {
            $query->joinWith('restaurant');
            $query->andWhere(['restaurant.restaurant_uuid' => $store_id]);
        }

        if ($keyword) {
            $query->andWhere([
                'OR',
                ['like', 'area_name', $keyword],
                ['like', 'area_name_ar', $keyword]
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @return ActiveDataProvider
     */
    public function actionDeliveryAreas() {

        $keyword = Yii::$app->request->get('keyword');
        $city_id = Yii::$app->request->get('city_id');
        $store_id = Yii::$app->request->get('store_id');

        //valdiate access

        $store = Yii::$app->accountManager->getManagedAccount($store_id);

        $query = $store->getAreaDeliveryZones();

        if ($city_id) {
            $query->andWhere(['city_id' => $city_id]);
        }

        if ($store_id) {
            //$query->joinWith('restaurant');
            $query->andWhere(['restaurant_uuid' => $store_id]);
        }

        if ($keyword) {
            $query
                ->joinWith('area')
            ->andWhere([
                'OR',
                ['like', 'area_name', $keyword],
                ['like', 'area_name_ar', $keyword]
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Return Area detail
     * @param integer $area_id
     * @return Area
     */
    public function actionDetail($id) {
        return $this->findModel($id);
    }

    /**
     * Finds the Area  model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Area the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($area_id)
    {
        if (($model = Area::findOne ($area_id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
