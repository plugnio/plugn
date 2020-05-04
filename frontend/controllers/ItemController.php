<?php

namespace frontend\controllers;

use Yii;
use common\models\Item;
use backend\models\ItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller {

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
    public function actionView($id, $restaurantUuid) {

        $item_model = $this->findModel($id, $restaurantUuid);

        // Item options
        $itemOptionsDataProvider = new \yii\data\ActiveDataProvider([
            'query' => $item_model->getOptions(),
        ]);

        return $this->render('view', [
                    'model' => $this->findModel($id, $restaurantUuid),
                    'itemOptionsDataProvider' => $itemOptionsDataProvider,
                    'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurantUuid) {
        
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);
        $model = new Item();
        
        $model->restaurant_uuid = $restaurant_model->restaurant_uuid;
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            if ($model->save()) {

            $image = \yii\web\UploadedFile::getInstances($model, 'image');

                if ($image)
                    $model->uploadItemImage($image[0]->tempName);

                if ($model->items_category)
                    $model->saveItemsCategory($model->items_category);

                return $this->redirect(['view', 'id' => $model->item_uuid, 'restaurantUuid' => $restaurantUuid]);
            }
        }
        
        return $this->render('create', [
                    'model' => $model,
                    'restaurantUuid' => $restaurant_model->restaurant_uuid
        ]);
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $restaurantUuid) {
        
        $model = $this->findModel($id, $restaurantUuid);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            if ($model->save()) {

                $image = \yii\web\UploadedFile::getInstances($model, 'image');

                if ($image)
                    $model->uploadItemImage($image[0]->tempName);

                if ($model->items_category)
                    $model->saveItemsCategory($model->items_category);

                return $this->redirect(['view', 'id' => $model->item_uuid, 'restaurantUuid' => $restaurantUuid]);
            }
        }

        return $this->render('update', [
                    'model' => $model,
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

        return $this->redirect(['index','restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id , $restaurantUuid) {
        if (($model = Item::find()->where(['item_uuid' => $id, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
