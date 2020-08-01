<?php

namespace frontend\controllers;

use Yii;
use common\models\Item;
use frontend\models\ItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Option;
use common\models\CategoryItem;
use common\models\ExtraOption;
use wbraganca\dynamicform\DynamicFormWidget;
use frontend\base\Model;
use yii\helpers\ArrayHelper;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller {

    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
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

    public function actionExportToExcel($restaurantUuid) {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $model = Item::find()->where(['restaurant_uuid' => $restaurant_model->restaurant_uuid])->all();

        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=\"products.xlsx\"");
        header("Cache-Control: max-age=0");


        \moonland\phpexcel\Excel::export([
            'isMultipleSheet' => false,
            'models' => $model,
            'columns' => [
                'item_name',
                'stock_qty',
                'unit_sold',
                'item_price:currency'
            ],
        ]);
    }

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex($restaurantUuid) {
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
    public function actionCreate($restaurantUuid) {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);


        $model = new Item();
        $model->restaurant_uuid = $restaurantUuid;
        $modelOptions = [];

        $formOptions = Yii::$app->request->post('Option', []);
        foreach ($formOptions as $i => $formOption) {
            $modelOption = new Option(['scenario' => Option::SCENARIO_BATCH_UPDATE]);
            $modelOption->setAttributes($formOption);
            $modelOptions[] = $modelOption;
        }

        //handling if the addRow button has been pressed
        if (Yii::$app->request->post('addRow') == 'true') {
            $model->load(Yii::$app->request->post());
            $modelOptions[] = new Option(['scenario' => Option::SCENARIO_BATCH_UPDATE]);
            return $this->render('create', [
                        'model' => $model,
                        'modelOptions' => $modelOptions,
                        'restaurantUuid' => $restaurantUuid
            ]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Model::validateMultiple($modelOptions) && $model->validate()) {
                // if ($model->validate()) {
                $model->save();
                foreach ($modelOptions as $modelOption) {
                    $modelOption->item_uuid = $model->item_uuid;
                    $modelOption->save();
                }
                return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
            }
        }

        return $this->render('create', [
                    'model' => $model,
                    'modelOptions' => $modelOptions,
                    'restaurantUuid' => $restaurant_model->restaurant_uuid
        ]);
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);


        $model = $this->findModel($id, $restaurantUuid);
        $modelOptions = $model->getOptions()->all();



        $formOptions = Yii::$app->request->post('Option', []);
        foreach ($formOptions as $i => $formOption) {
            //loading the models if they are not new
            if (isset($formOption['id']) && isset($formOption['updateType']) && $formOption['updateType'] != Option::UPDATE_TYPE_CREATE) {
                //making sure that it is actually a child of the main model
                $modelOption = Option::findOne(['option_id' => $formOption['option_id'], 'item_uuid' => $model->item_uuid]);
                $modelOption->setScenario(Option::SCENARIO_BATCH_UPDATE);
                $modelOption->setAttributes($formOption);
                $modelOptions[$i] = $modelOption;
                //validate here if the modelOption loaded is valid, and if it can be updated or deleted
            } else {
                $modelOption = new Option(['scenario' => Option::SCENARIO_BATCH_UPDATE]);
                $modelOption->setAttributes($formOption);
                $modelOptions[] = $modelOption;
            }
        }


        //handling if the addRow button has been pressed
        if (Yii::$app->request->post('addRow') == 'true') {

            $modelOptions[] = new Option(['scenario' => Option::SCENARIO_BATCH_UPDATE]);
            return $this->render('update', [
                        'model' => $model,
                        'modelOptions' => $modelOptions,
                        'restaurantUuid' => $restaurantUuid
            ]);
        }




        if ($model->load(Yii::$app->request->post())) {
            if (Model::validateMultiple($modelOptions) && $model->validate()) {
                $model->save();
                foreach ($modelOptions as $modelOption) {
                    //details that has been flagged for deletion will be deleted
                    if ($modelOption->updateType == Option::UPDATE_TYPE_DELETE) {
                        $modelOption->delete();
                    } else {
                        //new or updated records go here
                        $modelOption->item_uuid = $model->item_uuid;
                        $modelOption->save();
                    }
                }
                return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
            }
        }



        return $this->render('update', [
                    'model' => $model,
                    'modelOptions' => $modelOptions,
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
    public function actionDelete($id, $restaurantUuid) {
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
    protected function findModel($id, $restaurantUuid) {
        if (($model = Item::find()->where(['item_uuid' => $id, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
