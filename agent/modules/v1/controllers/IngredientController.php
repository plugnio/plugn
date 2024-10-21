<?php

namespace agent\modules\v1\controllers;

use Yii;
use common\models\RestaurantIngredient;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class IngredientController extends BaseController
{
    /**
     * @return ActiveDataProvider
     */
    public function actionList()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $keyword = Yii::$app->request->get("query");

        $query = RestaurantIngredient::find()
            ->andWhere(['restaurant_uuid' => $store->restaurant_uuid])
            ->orderBy('created_at DESC');

        if ($keyword && $keyword != 'null') {
            $query->andWhere(['like', 'name', $keyword]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     * @throws NotFoundHttpException
     */
    public function actionDetail($id)
    {
        return $this->findModel($id);
    }

    /**
     * @return array|string[]
     */
    public function actionCreate()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = new RestaurantIngredient();
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->name = Yii::$app->request->getBodyParam("name");
        $model->stock_quantity = Yii::$app->request->getBodyParam("stock_quantity");
        $model->image_url = Yii::$app->request->getBodyParam("image_url");

        if (!$model->save()) {
            return [
                "message" => $model->errors,
                "operation" => "error"
            ];
        }

        return [
            "message" => 'Added successfully!',
            "operation" => "success"
        ];
    }

    /**
     * @param $id
     * @return array|string[]
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->name = Yii::$app->request->getBodyParam("name");
        $model->stock_quantity = Yii::$app->request->getBodyParam("stock_quantity");
        $model->image_url = Yii::$app->request->getBodyParam("image_url");

        if (!$model->save()) {
            return [
                "message" => $model->errors,
                "operation" => "error"
            ];
        }

        return [
            "message" => 'Updated successfully!',
            "operation" => "success"
        ];
    }

    /**
     * @param $id
     * @return array|string[]
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!$model->delete()) {
            return [
                "message" => $model->errors,
                "operation" => "error"
            ];
        }

        return [
            "message" => 'Deleted successfully!',
            "operation" => "success"
        ];
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        if (($model = RestaurantIngredient::find()->where(['ingredient_uuid' => $id, 'restaurant_uuid' => $store->restaurant_uuid])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}