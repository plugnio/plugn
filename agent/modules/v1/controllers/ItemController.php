<?php

namespace agent\modules\v1\controllers;

use common\models\ItemVariant;
use common\models\ItemVariantOption;
use Yii;
use yii\db\Expression;
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
        $type = Yii::$app->request->get('type');
        $category_id = Yii::$app->request->get('category_id');

        $store = Yii::$app->accountManager->getManagedAccount();

        $query = Item::find()
            ->andWhere(['restaurant_uuid'=> $store->restaurant_uuid])
            ->orderBy ([new \yii\db\Expression('item.sort_number ASC')]);
        
        if ($type != 'all') {
            $query->andWhere(['track_quantity'=> 1]);
        }

        if ($keyword && $keyword != 'null') {
            $query->andWhere ([
                    'or',
                    ['like', 'item_name', $keyword],
                    ['like', 'item_name_ar', $keyword],
                    ['like', 'item_description', $keyword],
                    ['like', 'item_description_ar', $keyword]
                ]);
        }

        if($category_id) {
            $query->filterByCategory($category_id);
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
        $model->item_type = Yii::$app->request->getBodyParam ("item_type"); 
        $model->prep_time = Yii::$app->request->getBodyParam ("prep_time");
        $model->prep_time_unit = Yii::$app->request->getBodyParam ("prep_time_unit");
        $model->item_price = Yii::$app->request->getBodyParam ("item_price");
        $model->compare_at_price = Yii::$app->request->getBodyParam ("compare_at_price");
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
                    $optionModel->max_qty = (isset($option['max_qty'])) ? $option['max_qty'] : 0;
                    $optionModel->min_qty = (isset($option['min_qty'])) ? $option['min_qty'] : 0;
                    $optionModel->is_required = $option['is_required'];
                    $optionModel->option_type = $option['option_type'];

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
                            $extraOptionModel->extra_option_price = (isset($extraOption['extra_option_price'])) ? $extraOption['extra_option_price'] : 0;
                            $extraOptionModel->stock_qty = (isset($extraOption['stock_qty']))?$extraOption['stock_qty'] : null;
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

            //add variants

            $variants = Yii::$app->request->getBodyParam ("itemVariants");

            if(!$variants)
                $variants = [];

            foreach($variants as $variant)
            {
                $itemVariant = new ItemVariant();
                $itemVariant->item_uuid = $model->item_uuid;

                $itemVariant->stock_qty = $variant['stock_qty'];
                //$itemVariant->track_quantity = $variant['track_quantity'];
                $itemVariant->sku = $variant['sku'];
                $itemVariant->barcode = $variant['barcode'];
                $itemVariant->price = $variant['price'];
                $itemVariant->compare_at_price = $variant['compare_at_price'];
                $itemVariant->weight = $variant['weight'];

                if(!$itemVariant->save())
                {
                    $transaction->rollBack();

                    return [
                        "operation" => "error",
                        "message" => $itemVariant->errors
                    ];
                }


                //add variant options

                foreach($variant['itemVariantOptions'] as $value) {

                    if(empty($value['option_id'])) {
                        $option_id = Option::findOne([
                            'item_uuid' => $itemVariant->item_uuid,
                            'option_name' => $value['option']['option_name']
                        ])->option_id;
                    }
                    else
                    {
                        $option_id = $value['option_id'];
                    }

                    if(empty($value['extra_option_id'])) {
                        $extra_option_id = ExtraOption::findOne([
                            'option_id' => $option_id,
                            'extra_option_name' => $value['extraOption']['extra_option_name']
                        ])->extra_option_id;
                    }
                    else
                    {
                        $extra_option_id = $value['extra_option_id'];
                    }

                    if(empty($value['item_variant_option_uuid'])) {
                        $itemVariantOption = new ItemVariantOption();
                    } else {
                        $itemVariantOption = ItemVariantOption::findOne($value['item_variant_option_uuid']);
                    }

                    $itemVariantOption->item_variant_uuid  = $itemVariant->item_variant_uuid;
                    $itemVariantOption->item_uuid = $itemVariant->item_uuid;
                    $itemVariantOption->option_id = $option_id;
                    $itemVariantOption->extra_option_id = $extra_option_id;

                    if(!$itemVariantOption->save())
                    {
                        $transaction->rollBack();

                        return [
                            "operation" => "error",
                            "message" => $itemVariantOption->errors
                        ];
                    }
                }
            }

            $transaction->commit();

            return [
                "operation" => "success",
                "message" => Yii::t('agent', "Item created successfully"),
            ];
        }
        catch (\Exception $e)
        {
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
        $model->item_type = Yii::$app->request->getBodyParam ("item_type"); 
        $model->prep_time = Yii::$app->request->getBodyParam ("prep_time");
        $model->prep_time_unit = Yii::$app->request->getBodyParam ("prep_time_unit");
        $model->item_price = Yii::$app->request->getBodyParam ("item_price");
        $model->compare_at_price = Yii::$app->request->getBodyParam ("compare_at_price");
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

            $arrOptionIds = [];

            if ($itemOptions && count($itemOptions) > 0) {

                foreach ($itemOptions as $option) {

                    if(empty($option['option_id'])) {
                        $optionModel = new Option();
                    } else {
                        $optionModel = Option::findOne([
                            'option_id' => $option['option_id'],
                            'item_uuid' => $id
                        ]);
                    }

                    $optionModel->item_uuid = $model->item_uuid;
                    $optionModel->option_name = $option['option_name'];
                    $optionModel->option_name_ar = $option['option_name_ar'];
                    $optionModel->is_required = $option['is_required'];
                    $optionModel->option_type = $option['option_type'];
                    $optionModel->max_qty = (isset($option['max_qty'])) ? $option['max_qty'] : 0;
                    $optionModel->min_qty = (isset($option['min_qty'])) ? $option['min_qty'] : 0;

                    if (!$optionModel->save()) {
                        $transaction->rollBack();
                        return [
                            "operation" => "error",
                            "message" => $optionModel->errors
                        ];
                    }

                    $arrOptionIds[] =  $optionModel->option_id;

                    $arrExtraOptionIds = [];

                    if ($option['extraOptions'] && count($option['extraOptions']) > 0) {

                        foreach ($option['extraOptions'] as $extraOption) {

                            if(empty($extraOption['extra_option_id'])) {
                                $extraOptionModel = new ExtraOption();
                            } else {
                                $extraOptionModel = ExtraOption::findOne([
                                    'option_id' => $optionModel->option_id,
                                    'extra_option_id' => $extraOption['extra_option_id']
                                ]);
                            }

                            $extraOptionModel->option_id = $optionModel->option_id;
                            $extraOptionModel->extra_option_name = $extraOption['extra_option_name'];
                            $extraOptionModel->extra_option_name_ar = $extraOption['extra_option_name_ar'];
                            $extraOptionModel->extra_option_price = $extraOption['extra_option_price'];
                            $extraOptionModel->stock_qty = (isset($extraOption['stock_qty']))?$extraOption['stock_qty'] : null;

                            if (!$extraOptionModel->save()) {
                                $transaction->rollBack();
                                return [
                                    "operation" => "error",
                                    "message" => $extraOptionModel->errors
                                ];
                            }

                            $arrExtraOptionIds[] = $extraOptionModel->extra_option_id;
                        }
                    }

                    ExtraOption::deleteAll([
                        'AND',
                        ['NOT IN', 'extra_option_id', $arrExtraOptionIds],
                        ['option_id' => $optionModel->option_id]
                    ]);
                }
            }

            //remove other option

            Option::deleteAll([
                'AND',
                ['NOT IN', 'option_id', $arrOptionIds],
                ['item_uuid' => $model->item_uuid]
            ]);

            // save images
            $itemImages = Yii::$app->request->getBodyParam ("itemImages");
            $model->saveItemImages($itemImages);

            //save categories
            $itemCategories = Yii::$app->request->getBodyParam ("itemCategories");
            $arrCategoryIds = ArrayHelper::getColumn ($itemCategories, 'category_id');
            $model->saveItemsCategory($arrCategoryIds);

            //add variants

            $variants = Yii::$app->request->getBodyParam ("itemVariants");

            if(!$variants)
                $variants = [];

            $arrItemVariantIds = [];
            $arrItemVariantOptionIds = [];

            foreach($variants as $variant)
            {
                if(empty($variant['item_variant_uuid'])) {
                    $itemVariant = new ItemVariant();
                }
                else
                {
                    $itemVariant = $model->getItemVariants()
                        ->andWhere(['item_variant_uuid' => $variant['item_variant_uuid']])
                        ->one();
                }

                $itemVariant->item_uuid = $model->item_uuid;

                $itemVariant->stock_qty = $variant['stock_qty'];
                //$itemVariant->track_quantity = $variant['track_quantity'];
                $itemVariant->sku = $variant['sku'];
                $itemVariant->barcode = $variant['barcode'];
                $itemVariant->price = $variant['price'];
                $itemVariant->compare_at_price = $variant['compare_at_price'];
                $itemVariant->weight = $variant['weight'];

                if(!$itemVariant->save())
                {
                    $transaction->rollBack();

                    return [
                        "operation" => "error",
                        "message" => $itemVariant->errors
                    ];
                }

                //add variant options

                foreach($variant['itemVariantOptions'] as $value) {

                    if(empty($value['option_id'])) {
                        $option_id = Option::findOne([
                            'item_uuid' => $itemVariant->item_uuid,
                            'option_name' => $value['option']['option_name']
                        ])->option_id;
                    }
                    else
                    {
                        $option_id = $value['option_id'];
                    }

                    if(empty($value['extra_option_id'])) {
                        $extra_option_id = ExtraOption::findOne([
                            'option_id' => $option_id,
                            'extra_option_name' => $value['extraOption']['extra_option_name']
                        ])->extra_option_id;
                    }
                    else
                    {
                        $extra_option_id = $value['extra_option_id'];
                    }

                    if(empty($value['item_variant_option_uuid'])) {
                        $itemVariantOption = new ItemVariantOption();
                    } else {
                        $itemVariantOption = ItemVariantOption::findOne($value['item_variant_option_uuid']);
                    }

                    $itemVariantOption->item_variant_uuid  = $itemVariant->item_variant_uuid;
                    $itemVariantOption->item_uuid = $itemVariant->item_uuid;
                    $itemVariantOption->option_id = $option_id;
                    $itemVariantOption->extra_option_id = $extra_option_id;

                    if(!$itemVariantOption->save())
                    {
                        $transaction->rollBack();

                        return [
                            "operation" => "error",
                            "message" => $itemVariantOption->errors
                        ];
                    }

                    $arrItemVariantOptionIds[] = $itemVariantOption->item_variant_option_uuid;
                }

                $arrItemVariantIds[] = $itemVariant->item_variant_uuid;
            }

            //remove other variants

            ItemVariant::deleteAll([
                'AND',
                ['NOT IN', 'item_variant_uuid', $arrItemVariantIds],
                ['item_uuid' => $id]
            ]);

            //remove other variantOptions

            ItemVariantOption::deleteAll([
                'AND',
                ['NOT IN', 'item_variant_option_uuid', $arrItemVariantOptionIds],
                ['item_uuid' => $id]
            ]);

            $transaction->commit();

            return [
                "operation" => "success",
                "message" => Yii::t('agent',"Item updated successfully")
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
    public function actionUpdateStockQty()
    {
        $id = Yii::$app->request->getBodyParam('item_uuid');

        $stock_qty = Yii::$app->request->getBodyParam('stock_qty');

        $model = $this->findModel($id);

        $model->stock_qty = (int) $stock_qty;

        if (!$model->save(false))
        {
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
     * Update Stock Qty
     * @param type $itemUuid
     * @return boolean
     */
    public function actionChangePosition(){

        $items = Yii::$app->request->getBodyParam('items');

        foreach ($items as $key => $value) {
            $model = $this->findModel($value);

            if(!$model) {
                continue;
            }
            
            $model->sort_number = (int)$key+1;
            $model->save(false);
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Item position changed successfully")
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

    /**
     * download excel of item report
     */
    public function actionItemsReport()
    {
        $store_model = Yii::$app->accountManager->getManagedAccount();

        $start_date = Yii::$app->request->get('start_date');
        $end_date = Yii::$app->request->get('end_date');

            $query = \agent\models\Item::find()
                ->joinWith(['orderItems', 'orderItems.order'])
                ->andWhere ([
                    'IN',
                    'order.order_status', [
                        Order::STATUS_PENDING,
                        Order::STATUS_BEING_PREPARED,
                        Order::STATUS_OUT_FOR_DELIVERY,
                        Order::STATUS_COMPLETE,
                        Order::STATUS_CANCELED
                    ]
                ])
                ->andWhere(['order.restaurant_uuid' => $store_model->restaurant_uuid]);

            if($start_date && $end_date) {
                $query->andWhere (new Expression('DATE(order.order_created_at) >= DATE("'.$start_date.'") AND
                    DATE(order.order_created_at) <= DATE("'.$end_date.'")'));
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
                            $total = $data->getSoldUnitsInSpecifcDate($start_date,$end_date);
                            return ($total) ? $total : 0;
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
                        return Yii::$app->formatter->asCurrency($data->item_price, $data->currency->code, [
                            \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                        ]);
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
