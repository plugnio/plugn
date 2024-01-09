<?php

namespace agent\modules\v1\controllers;

use agent\models\CategoryItem;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use agent\models\Category;

class CategoryController extends BaseController
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

        $query = Category::find()
            ->andWhere(['category.restaurant_uuid' => $store->restaurant_uuid]);

        if ($keyword) {
            $query->joinWith(['items']);
            $query->andWhere([
                'OR',
                ['like', 'title', $keyword],
                ['like', 'title_ar', $keyword],
                ['like', 'subtitle', $keyword],
                ['like', 'subtitle_ar', $keyword],
                ['like', 'item.item_name', $keyword],
                ['like', 'item.item_name_ar', $keyword],
            ]);
        }

        $query->orderBy([new \yii\db\Expression('sort_number ASC')]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionItemList($store_uuid = null)
    {
        $category_id = Yii::$app->request->get('category_id');

        Yii::$app->accountManager->getManagedAccount($store_uuid);

        $query = CategoryItem::find()
            ->joinWith('item')
            ->andWhere(['category_id' => $category_id])
            ->orderBy([new \yii\db\Expression('item.sort_number ASC')]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Create category
     * @return array
     */
    public function actionCreate()
    {

        $store_uuid = Yii::$app->request->getBodyParam("store_uuid");

        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = new Category();
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->title = Yii::$app->request->getBodyParam("title");
        $model->title_ar = Yii::$app->request->getBodyParam("title_ar");
        $model->subtitle = Yii::$app->request->getBodyParam("subtitle");
        $model->subtitle_ar = Yii::$app->request->getBodyParam("subtitle_ar");
        $model->category_meta_description = Yii::$app->request->getBodyParam("category_meta_description");
        $model->category_meta_description_ar = Yii::$app->request->getBodyParam("category_meta_description_ar");
        $model->sort_number = Yii::$app->request->getBodyParam("sort_number");

        //validate before uploading image

        if (!$model->validate()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        $category_image = Yii::$app->request->getBodyParam('category_image');

        if ($category_image) {
            $imageUrl = Yii::$app->temporaryBucketResourceManager->getUrl($category_image);
            $model->updateImage($imageUrl);
        }

        if (!$model->save()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Category created successfully"),
            "model" => Category::findOne($model->category_id)
        ];
    }

    /**
     * Update category
     */
    public function actionUpdate($category_id)
    {
        $model = $this->findModel($category_id);

        $model->title = Yii::$app->request->getBodyParam("title");
        $model->title_ar = Yii::$app->request->getBodyParam("title_ar");
        $model->subtitle = Yii::$app->request->getBodyParam("subtitle");
        $model->subtitle_ar = Yii::$app->request->getBodyParam("subtitle_ar");
        $model->category_meta_description = Yii::$app->request->getBodyParam("category_meta_description");
        $model->category_meta_description_ar = Yii::$app->request->getBodyParam("category_meta_description_ar");
        $model->sort_number = Yii::$app->request->getBodyParam("sort_number");

        //validate before uploading image

        if (!$model->validate()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        $category_image = Yii::$app->request->getBodyParam('category_image');

        if ($model->category_image != $category_image) {
            $model->updateImage($category_image);
        }

        if (!$model->save()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem updating the category")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Category updated successfully"),
            "model" => $model
        ];
    }

    /**
     * Allows agent to upload category image
     */
    public function actionUploadCategoryImage()
    {
        $category_image = urldecode(Yii::$app->request->getBodyParam('category_image'));
        $store_uuid = Yii::$app->request->getBodyParam('store_uuid');
        $category_id = Yii::$app->request->getBodyParam('category_id');

        $model = $this->findModel($category_id);

        if (!isset($model->category_id)) {
            return [
                "operation" => "error",
                "message" => Yii::t('agent', 'Invalid Category ID')
            ];
        }

        //Delete old category image

        if ($model->category_image) {
            $model->deleteCategoryImage();
        }

        $model->category_image = basename($category_image);

        $result = $model->moveCategoryImageFromS3toCloudinary();

        if ($result) {
            return [
                'operation' => 'success',
                'url' => Url::to("@categoty-image/" . 'restaurants/' . $model->restaurant_uuid . "/category/" . $model->category_image),
                'logo' => $model->category_image,
                'message' => Yii::t('agent', 'Category Image Uploaded Successfully')
            ];
        } else {
            return [
                'operation' => 'error',
                'message' => $model->errors
            ];
        }
    }

    /**
     * Delete Category
     */
    public function actionDelete($category_id)
    {
        $model = $this->findModel($category_id);

        if (!$model->delete()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem deleting the category")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Category deleted successfully")
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

        foreach ($items as $key => $category_id) {

            $model = Category::find()->where([
                'category_id' => $category_id,
                'restaurant_uuid' => $store->restaurant_uuid
            ])->one();

            if(!$model) {
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
     * Return Category detail
     * @param type $store_uuid
     * @param type $category_id
     * @return type
     */
    public function actionDetail($category_id)
    {
        return $this->findModel($category_id);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $category_id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($category_id)
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = Category::find()->where([
            'category_id' => $category_id,
           // 'restaurant_uuid' => $store->restaurant_uuid
        ])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
