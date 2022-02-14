<?php

namespace frontend\controllers;

use Yii;
use common\models\RestaurantBranch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RestaurantBranchController implements the CRUD actions for RestaurantBranch model.
 */
class RestaurantBranchController extends Controller {

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

    /**
     * Lists all RestaurantBranch models.
     * @return mixed
     */
    public function actionIndex($storeUuid) {

        $restaurant = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $dataProvider = new ActiveDataProvider([
            'query' => RestaurantBranch::find()->where(['restaurant_uuid' => $restaurant->restaurant_uuid]),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'storeUuid' => $restaurant->restaurant_uuid
        ]);
    }

    /**
     * Displays a single RestaurantBranch model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $storeUuid) {
        return $this->render('view', [
                    'model' => $this->findModel($id, $storeUuid),
        ]);
    }

    /**
     * Creates a new RestaurantBranch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($storeUuid) {
        $model = new RestaurantBranch();
        $model->restaurant_uuid = Yii::$app->accountManager->getManagedAccount($storeUuid)->restaurant_uuid;

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->restaurant_branch_id, 'storeUuid' => $model->restaurant_uuid]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing RestaurantBranch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $storeUuid) {
        $model = $this->findModel($id, $storeUuid);

        if ($model->load(Yii::$app->request->post())) {

            $model->restaurant_uuid = Yii::$app->accountManager->getManagedAccount($storeUuid)->restaurant_uuid;

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->restaurant_branch_id, 'storeUuid' => $model->restaurant_uuid]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RestaurantBranch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $storeUuid) {
        $this->findModel($id, $storeUuid)->delete();

        return $this->redirect(['index','storeUuid' => $storeUuid]);
    }

    /**
     * Finds the RestaurantBranch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RestaurantBranch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $storeUuid) {

        $store = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $model = RestaurantBranch::findOne([
            'restaurant_branch_id' => $id,
            'restaurant_uuid' => $store->restaurant_uuid
        ]);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
