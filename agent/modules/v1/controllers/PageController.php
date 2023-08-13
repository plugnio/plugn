<?php

namespace agent\modules\v1\controllers;

use agent\models\PageItem;
use agent\models\Restaurant;
use common\models\RestaurantPage;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use agent\models\Page;

class PageController extends BaseController
{
    /**
     * Get all store's categories
     * @param type $id
     * @param type $store_uuid
     * @return ActiveDataProvider
     */
    public function actionList($store_uuid = null)
    {
        $keyword = Yii::$app->request->get('keyword');

        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $query = $store->getStorePages();

        if ($keyword) {
            $query->andWhere([
                'OR',
                ['like', 'title', $keyword],
                ['like', 'title_ar', $keyword],
            ]);
        }

        $query->orderBy([new \yii\db\Expression('sort_number ASC')]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Create page
     * @return array
     */
    public function actionCreate()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = new RestaurantPage();
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->title = Yii::$app->request->getBodyParam("title");
        $model->title_ar = Yii::$app->request->getBodyParam("title_ar");
        $model->description = Yii::$app->request->getBodyParam("description");
        $model->description_ar = Yii::$app->request->getBodyParam("description_ar");
        $model->sort_number = Yii::$app->request->getBodyParam("sort_number");

        if (!$model->save()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Page created successfully")
        ];
    }

    /**
     * Update page
     */
    public function actionUpdate($page_uuid)
    {
        $model = $this->findModel($page_uuid);

        $model->title = Yii::$app->request->getBodyParam("title");
        $model->title_ar = Yii::$app->request->getBodyParam("title_ar");
        $model->description = Yii::$app->request->getBodyParam("description");
        $model->description_ar = Yii::$app->request->getBodyParam("description_ar");
        $model->sort_number = Yii::$app->request->getBodyParam("sort_number");

        if (!$model->save()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem updating the page")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Page updated successfully"),
            "model" => $model
        ];
    }
 
    /**
     * Delete Page
     */
    public function actionDelete($page_uuid)
    {
        $model = $this->findModel($page_uuid);

        if (!$model->delete()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem deleting the page")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Page deleted successfully")
        ];
    }
 
    /**
     * Update Stock Qty
     * @param type $itemUuid
     * @return boolean
     */
    public function actionChangePosition()
    {
        $items = Yii::$app->request->getBodyParam('items');

        $store = Yii::$app->accountManager->getManagedAccount();

        foreach ($items as $key => $page_uuid) {

            $model = Page::find()->where([
                'page_uuid' => $page_uuid,
                'restaurant_uuid' => $store->restaurant_uuid
            ])->one();

            if (!$model) {
                continue;
            }

            $model->sort_number = (int)$key + 1;
            $model->save(false);
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Item position changed successfully")
        ];
    }

    /**
     * Return Page detail
     * @param type $store_uuid
     * @param type $page_uuid
     * @return type
     */
    public function actionDetail($page_uuid)
    {
        return $this->findModel($page_uuid);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $page_uuid
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($page_uuid)
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = $store->getStorePages()
            ->andWhere([
                'page_uuid' => $page_uuid,
            ])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
