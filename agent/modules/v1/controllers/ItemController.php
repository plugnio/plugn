<?php

namespace agent\modules\v1\controllers;

use agent\models\ExtraOption;
use agent\models\Option;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\Item;

class ItemController extends Controller
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
     * Get all store's products
     * @return type
     */
    public function actionList($store_uuid)
    {
        $keyword = Yii::$app->request->get('keyword');

        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $query = Item::find();
        $query->andWhere(['restaurant_uuid'=>$store_uuid]);
        $query->orderBy('item_created_at DESC');

        if ($keyword && $keyword != 'null') {
            $query->filterWhere ([
                    'or',
                    ['like', 'item_name', $keyword],
                    ['like', 'item_name_ar', $keyword],
                    ['like', 'item_description', $keyword],
                    ['like', 'item_description_ar', $keyword]
                ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Return item detail
     * @param string $id
     * @return Item
     */
    public function actionDetail($id)
    {
        return $this->findModel($id);
    }

    /**
     * add new item
     * @return array|string[]
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = new Item();

        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->item_name = Yii::$app->request->getBodyParam ("item_name");
        $model->item_name_ar = Yii::$app->request->getBodyParam ("item_name_ar");
        $model->item_description = Yii::$app->request->getBodyParam ("item_description");
        $model->item_description_ar = Yii::$app->request->getBodyParam ("item_description_ar");
        $model->sort_number = Yii::$app->request->getBodyParam ("sort_number");
        $model->prep_time = Yii::$app->request->getBodyParam ("prep_time");
        $model->prep_time_unit = Yii::$app->request->getBodyParam ("prep_time_unit");
        $model->item_price = Yii::$app->request->getBodyParam ("item_price");
        $model->sku = Yii::$app->request->getBodyParam ("sku");
        $model->barcode = Yii::$app->request->getBodyParam ("barcode");
        $model->track_quantity = (int)Yii::$app->request->getBodyParam ("track_quantity");
        $model->stock_qty = Yii::$app->request->getBodyParam ("stock_qty");
        $model->items_category = Yii::$app->request->getBodyParam ("itemCategories");
        $model->item_images = Yii::$app->request->getBodyParam ("itemImages");
        $itemOptions = Yii::$app->request->getBodyParam('options');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->save()) {
                $transaction->rollBack();
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            }

            if ($itemOptions && count($itemOptions) > 0) {
                foreach ($itemOptions as $option) {
                    $optionModel = new Option();
                    $optionModel->item_uuid = $model->item_uuid;
                    $optionModel->option_name = $option['option_name'];
                    $optionModel->option_name_ar = $option['option_name_ar'];
                    $optionModel->max_qty = $option['max_qty'];
                    $optionModel->min_qty = $option['min_qty'];
                    if (!$optionModel->save()) {
                        $transaction->rollBack();
                        return [
                            "operation" => "error",
                            "message" => $optionModel->errors
                        ];
                    }

                    if ($option['extraOptions'] && count($option['extraOptions']) > 0) {
                        foreach ($option['extraOptions'] as $extraOption) {
                            $extraOptionModel = new ExtraOption();
                            $extraOptionModel->option_id = $optionModel->option_id;
                            $extraOptionModel->extra_option_name = $extraOption['extra_option_name'];
                            $extraOptionModel->extra_option_name_ar = $extraOption['extra_option_name_ar'];
                            $extraOptionModel->extra_option_price = $extraOption['extra_option_price'];
                            $extraOptionModel->stock_qty = $extraOption['stock_qty'];
                            if (!$extraOptionModel->save()) {
                                $transaction->rollBack();
                                return [
                                    "operation" => "error",
                                    "message" => $extraOptionModel->errors
                                ];
                            }
                        }
                    }
                }
            }

            // save images
            $itemImages = Yii::$app->request->getBodyParam ("itemImages");
            $model->saveItemImages($itemImages);

            //save categories
            $itemCategories = Yii::$app->request->getBodyParam ("itemCategories");
            $arrCategoryIds = ArrayHelper::getColumn ($itemCategories, 'category_id');
            $model->saveItemsCategory($arrCategoryIds);

            $transaction->commit();

            return [
                "operation" => "success",
                "message" => "Item created successfully",
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                "operation" => "error",
                "message" => $e->getMessage()
            ];
        }
    }

    /**
     * Update item
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel ($id);

        $model->item_name = Yii::$app->request->getBodyParam ("item_name");
        $model->item_name_ar = Yii::$app->request->getBodyParam ("item_name_ar");
        $model->item_description = Yii::$app->request->getBodyParam ("item_description");
        $model->item_description_ar = Yii::$app->request->getBodyParam ("item_description_ar");
        $model->sort_number = Yii::$app->request->getBodyParam ("sort_number");
        $model->prep_time = Yii::$app->request->getBodyParam ("prep_time");
        $model->prep_time_unit = Yii::$app->request->getBodyParam ("prep_time_unit");
        $model->item_price = Yii::$app->request->getBodyParam ("item_price");
        $model->sku = Yii::$app->request->getBodyParam ("sku");
        $model->barcode = Yii::$app->request->getBodyParam ("barcode");
        $model->track_quantity = (int)Yii::$app->request->getBodyParam ("track_quantity");
        $model->stock_qty = Yii::$app->request->getBodyParam ("stock_qty");
        $model->items_category = Yii::$app->request->getBodyParam ("itemCategories");
        $model->item_images = Yii::$app->request->getBodyParam ("itemImages");

        $itemOptions = Yii::$app->request->getBodyParam('options');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->save()) {
                $transaction->rollBack();
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            }
            Yii::$app->db->createCommand("delete from `option` where item_uuid = '$id'")->execute();
            Yii::$app->db->createCommand("delete from `extra_option` where option_id in (SELECT option_id FROM `option` where item_uuid = '$id')")->execute();

            if ($itemOptions && count($itemOptions) > 0) {
                foreach ($itemOptions as $option) {
                    $optionModel = new Option();
                    $optionModel->item_uuid = $model->item_uuid;
                    $optionModel->option_name = $option['option_name'];
                    $optionModel->option_name_ar = $option['option_name_ar'];
                    $optionModel->max_qty = $option['max_qty'];
                    $optionModel->min_qty = $option['min_qty'];
                    if (!$optionModel->save()) {
                        $transaction->rollBack();
                        return [
                            "operation" => "error",
                            "message" => $optionModel->errors
                        ];
                    }

                    if ($option['extraOptions'] && count($option['extraOptions']) > 0) {
                        foreach ($option['extraOptions'] as $extraOption) {
                            $extraOptionModel = new ExtraOption();
                            $extraOptionModel->option_id = $optionModel->option_id;
                            $extraOptionModel->extra_option_name = $extraOption['extra_option_name'];
                            $extraOptionModel->extra_option_name_ar = $extraOption['extra_option_name_ar'];
                            $extraOptionModel->extra_option_price = $extraOption['extra_option_price'];
                            $extraOptionModel->stock_qty = $extraOption['stock_qty'];
                            if (!$extraOptionModel->save()) {
                                $transaction->rollBack();
                                return [
                                    "operation" => "error",
                                    "message" => $extraOptionModel->errors
                                ];
                            }
                        }
                    }
                }
            }

            // save images
            $itemImages = Yii::$app->request->getBodyParam ("itemImages");
            $model->saveItemImages($itemImages);

            //save categories
            $itemCategories = Yii::$app->request->getBodyParam ("itemCategories");
            $arrCategoryIds = ArrayHelper::getColumn ($itemCategories, 'category_id');
            $model->saveItemsCategory($arrCategoryIds);

            $transaction->commit();

            return [
                "operation" => "success",
                "message" => "Item updated successfully",
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                "operation" => "error",
                "message" => $e->getMessage()
            ];
        }
    }


    /**
     * Update Stock Qty
     * @param type $itemUuid
     * @return boolean
     */
    public function actionUpdateStockQty(){

        $id = Yii::$app->request->getBodyParam('item_uuid');
        $model = $this->findModel($id);
        $model->stock_qty = Yii::$app->request->getBodyParam('stock_qty');
        if (!$model->save(false)){
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }
        return [
            "operation" => "success",
            "message" => "Item quantity updated successfully",
        ];
    }


    /**
     * Delete Business Location
     */
    public function actionDelete($id)
    {
        $model = $this->findModel ($id);

        if (!$model->delete ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => "We've faced a problem deleting the item"
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => "Item deleted successfully"
        ];
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $item_uuid
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($item_uuid)
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = Item::findOne(['item_uuid'=>$item_uuid,'restaurant_uuid'=>$store->restaurant_uuid]);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
