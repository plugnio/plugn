<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use common\models\ItemImage;
use agent\models\Item;
use agent\models\ExtraOption;
use agent\models\Option;
use agent\models\Order;


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
    public function actionList()
    {
        $keyword = Yii::$app->request->get('keyword');

        $store = Yii::$app->accountManager->getManagedAccount();

        $query = Item::find();
        $query->andWhere(['restaurant_uuid'=> $store->restaurant_uuid]);
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
    public function actionDetail($id) {
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
                "message" => Yii::t('translate', "Item created successfully"),
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
                "message" => Yii::t('translate',"Item updated successfully")
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
            "message" => Yii::t('agent', "Item quantity updated successfully")
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
                    "message" => Yii::t('agent',"We've faced a problem deleting the item")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Item deleted successfully")
        ];
    }

    /**
     * @param $id
     * @return array|string[]
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteImage($id, $image)
    {
        $itemImage = ItemImage::findOne(['item_uuid'=>$id, 'product_file_name'=>$image]);
        if ($itemImage && !$itemImage->delete()) {
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
            "message" => "Item image deleted successfully"
        ];
    }

    public function actionItemsReport()
    {
        $store_model = Yii::$app->accountManager->getManagedAccount();

        if($store_model->export_sold_items_data_in_specific_date_range) {
            list($start_date, $end_date) = explode(' - ', $store_model->export_sold_items_data_in_specific_date_range);
        } else {
            $start_date = $end_date = null;
        }

            $query = \agent\models\Item::find()
                ->joinWith(['orderItems', 'orderItems.order'])
                ->where(['order.order_status' => Order::STATUS_PENDING])
                ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
                ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
                ->orWhere(['order_status' => Order::STATUS_CANCELED])
                ->andWhere(['order.restaurant_uuid' => $store_model->restaurant_uuid]);

            if($start_date && $end_date) {
                $query->andWhere (['between', 'order.order_created_at', $start_date, $end_date]);
            }

            $searchResult = $query->all();

            header('Access-Control-Allow-Origin: *');
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment;filename=\"sold-items.xlsx\"");
            header("Cache-Control: max-age=0");

            \moonland\phpexcel\Excel::export([
                'isMultipleSheet' => false,
                'models' => $searchResult,
                'columns' => [
                    'item_name',
                    'sku',
                    'barcode',
                    [
                        'header' => Yii::t('agent','Unit sold'),
                        'label' => 'Sold items',
                        'format' => 'html',
                        'value' => function ($data)  use ($start_date,$end_date) {
                            return $data->getSoldUnitsInSpecifcDate($start_date,$end_date);
                        },
                    ],
                ],
            ]);
    }

    public function actionExportToExcel()
    {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount();

        $model = \agent\models\Item::find()->where(['restaurant_uuid' => $restaurant_model->restaurant_uuid])->all();

        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=\"item-report.xlsx\"");
        header("Cache-Control: max-age=0");

        \moonland\phpexcel\Excel::export([
            'isMultipleSheet' => false,
            'models' => $model,
            'columns' => [
                [
                    'header' => Yii::t('agent','Item name'),
                    "format" => "raw",
                    "value" => function($data) {
                        return $data->item_name;
                    }
                ],
                'sku',
                'barcode',
                'stock_qty',
                'unit_sold',
                [
                    'attribute' => 'item_price',
                    "format" => "raw",
                    "value" => function($data) {
                        return Yii::$app->formatter->asCurrency($data->item_price, $data->currency->code);
                    }
                ]
            ],
        ]);
    }

    /**
     * change status
     * @param $id
     * @param $store_uuid
     * @return array|string[]
     * @throws NotFoundHttpException
     */
    public function actionChangeStatus($id, $store_uuid)
    {
        $model = $this->findModel($id, $store_uuid);
        $model->scenario = Item::SCENARIO_UPDATE_STATUS;

        $model->item_status = ($model->item_status == Item::ITEM_STATUS_PUBLISH) ? Item::ITEM_STATUS_UNPUBLISH : Item::ITEM_STATUS_PUBLISH;
        if (!$model->save ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem while status change of item")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Item status changed successfully")
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

        $model = Item::findOne(['item_uuid'=>$item_uuid,'restaurant_uuid' => $store->restaurant_uuid]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
