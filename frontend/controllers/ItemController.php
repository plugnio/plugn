<?php

namespace frontend\controllers;

use Yii;
use common\models\Item;
use backend\models\ItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Option;
use common\models\ExtraOption;
use wbraganca\dynamicform\DynamicFormWidget;
use frontend\base\Model;
use yii\helpers\ArrayHelper;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [//allow authenticated users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex($restaurantUuid)
    {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $restaurant_model->restaurant_uuid);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'restaurant_model' => $restaurant_model
        ]);
    }

    /**
     * Displays a single Item model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionView($id, $restaurantUuid) {
    //
    //     $item_model = $this->findModel($id, $restaurantUuid);
    //
    //     // Item options
    //     $itemOptionsDataProvider = new \yii\data\ActiveDataProvider([
    //         'query' => $item_model->getOptions(),
    //     ]);
    //
    //     return $this->render('view', [
    //                 'model' => $this->findModel($id, $restaurantUuid),
    //                 'itemOptionsDataProvider' => $itemOptionsDataProvider,
    //                 'restaurantUuid' => $restaurantUuid
    //     ]);
    // }


    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate($restaurantUuid)
    {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $modelItem = new Item;
        $modelItem->restaurant_uuid = $restaurant_model->restaurant_uuid;
        $modelsOption = [new Option];
        $modelsExtraOption = [[new ExtraOption]];

        if ($modelItem->load(Yii::$app->request->post())) {
            $itemImages = \yii\web\UploadedFile::getInstances($modelItem, 'item_images');

            $modelsOption = Model::createMultiple(Option::classname());
            Model::loadMultiple($modelsOption, Yii::$app->request->post());

            // validate item and options models
            $valid = $modelItem->validate();
            $valid = Model::validateMultiple($modelsOption) && $valid;

            if (isset($_POST['ExtraOption'][0][0])) {
                foreach ($_POST['ExtraOption'] as $indexOption => $extraOptions) {
                    foreach ($extraOptions as $indexExtraOption => $extraOption) {
                        $data['ExtraOption'] = $extraOption;
                        $modelExtraOption = new ExtraOption;
                        $modelExtraOption->load($data);
                        $modelsExtraOption[$indexOption][$indexExtraOption] = $modelExtraOption;
                        $valid = $modelExtraOption->validate();
                    }
                }
            }

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelItem->save(false)) {

                        if ($modelItem->items_category) {
                            $modelItem->saveItemsCategory($modelItem->items_category);
                        }

                        if (!empty($itemImages))
                            $modelItem->uploadItemImage($itemImages);

                        Yii::info("[" . $modelItem->restaurant->name . ": " . $modelItem->item_name . " has been added  " . '] ' . $modelItem->restaurant->restaurant_domain . '/product/' . $modelItem->item_uuid, __METHOD__);

                        foreach ($modelsOption as $indexOption => $modelOption) {
                            if ($flag === false) {
                                break;
                            }

                            $modelOption->item_uuid = $modelItem->item_uuid;

                            if (!($flag = $modelOption->save(false))) {
                                break;
                            }

                            if (isset($modelsExtraOption[$indexOption]) && is_array($modelsExtraOption[$indexOption])) {
                                foreach ($modelsExtraOption[$indexOption] as $indexExtraOption => $modelExtraOption) {
                                    $modelExtraOption->option_id = $modelOption->option_id;
                                    if (!($flag = $modelExtraOption->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
                    'modelItem' => $modelItem,
                    'modelsOption' => (empty($modelsOption)) ? [new Option] : $modelsOption,
                    'modelsExtraOption' => (empty($modelsExtraOption)) ? [[new ExtraOption]] : $modelsExtraOption,
                    'restaurantUuid' => $restaurant_model->restaurant_uuid
        ]);
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $restaurantUuid)
    {
        $modelItem = $this->findModel($id, $restaurantUuid);
        $modelsOption = $modelItem->getOptions()->all();
        $modelsExtraOption = [];
        $oldExtraOptions = [];


        if (!empty($modelsOption)) {
            foreach ($modelsOption as $indexOption => $modelOption) {
                $extraOptions = $modelOption->getExtraOptions()->all();
                $modelsExtraOption[$indexOption] = $extraOptions;
                $oldExtraOptions = ArrayHelper::merge(ArrayHelper::index($extraOptions, 'extra_option_id'), $oldExtraOptions);
            }
        }



        if ($modelItem->load(Yii::$app->request->post())) {
            $itemImages = \yii\web\UploadedFile::getInstances($modelItem, 'item_images');


            // reset
            $modelsExtraOption = [];

            $oldOptionIDs = ArrayHelper::map($modelsOption, 'option_id', 'option_id');
            $modelsOption = Model::createMultiple(Option::classname(), $modelsOption);
            Model::loadMultiple($modelsOption, Yii::$app->request->post());
            $deletedOptionIDs = array_diff($oldOptionIDs, array_filter(ArrayHelper::map($modelsOption, 'option_id', 'option_id')));


            // validate Item and options models
            $valid = $modelItem->validate();
            $valid = Model::validateMultiple($modelsOption) && $valid;


            $extraOptionsIDs = [];
            if (isset($_POST['ExtraOption'][0][0])) {
                foreach ($_POST['ExtraOption'] as $indexOption => $extraOptions) {
                    $extraOptionsIDs = ArrayHelper::merge($extraOptionsIDs, array_filter(ArrayHelper::getColumn($extraOptions, 'extra_option_id')));
                    foreach ($extraOptions as $indexExtraOption => $extraOption) {
                        $data['ExtraOption'] = $extraOption;
                        $modelExtraOption = (isset($extraOption['extra_option_id']) && isset($oldExtraOptions[$extraOption['extra_option_id']])) ? $oldExtraOptions[$extraOption['extra_option_id']] : new ExtraOption;
                        $modelExtraOption->load($data);
                        $modelsExtraOption[$indexOption][$indexExtraOption] = $modelExtraOption;
                        $valid = $modelExtraOption->validate();
                    }
                }
            }



            $oldExtraOptionsIDs = ArrayHelper::getColumn($oldExtraOptions, 'extra_option_id');
            $deletedExtraOptionsIDs = array_diff($oldExtraOptionsIDs, $extraOptionsIDs);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelItem->save(false)) {

                      if ($modelItem->items_category) {
                          $modelItem->saveItemsCategory($modelItem->items_category);
                      }

                      if (!empty($itemImages))
                              $modelItem->uploadItemImage($itemImages);


                        Yii::info("[" . $modelItem->restaurant->name . ": " . $modelItem->item_name . " has been updated  " . '] ' . $modelItem->restaurant->restaurant_domain . '/product/' . $modelItem->item_uuid, __METHOD__);

                        if (!empty($deletedExtraOptionsIDs)) {
                            ExtraOption::deleteAll(['extra_option_id' => array_values($deletedExtraOptionsIDs)]);
                        }

                        if (!empty($deletedOptionIDs)) {
                            Option::deleteAll(['option_id' => $deletedOptionIDs]);
                        }

                        foreach ($modelsOption as $indexOption => $modelOption) {
                            if ($flag === false) {
                                break;
                            }

                            $modelOption->item_uuid = $modelItem->item_uuid;

                            if (!($flag = $modelOption->save(false))) {
                                break;
                            }

                            if (isset($modelsExtraOption[$indexOption]) && is_array($modelsExtraOption[$indexOption])) {
                                foreach ($modelsExtraOption[$indexOption] as $indexExtraOption => $modelExtraOption) {
                                    $modelExtraOption->option_id = $modelOption->option_id;
                                    if (!($flag = $modelExtraOption->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }


        return $this->render('update', [
                    'modelItem' => $modelItem,
                    'modelsOption' => (empty($modelsOption)) ? [new Option] : $modelsOption,
                    'modelsExtraOption' => (empty($modelsExtraOption)) ? [[new ExtraOption]] : $modelsExtraOption,
                    'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $restaurantUuid)
    {
        $this->findModel($id, $restaurantUuid)->delete();

        return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $restaurantUuid)
    {
        if (($model = Item::find()->where(['item_uuid' => $id, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
