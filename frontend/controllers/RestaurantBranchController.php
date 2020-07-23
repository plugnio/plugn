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
    public function actionIndex($restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $dataProvider = new ActiveDataProvider([
            'query' => RestaurantBranch::find()->where(['restaurant_uuid' => $restaurant_model->restaurant_uuid]),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'restaurantUuid' => $restaurant_model->restaurant_uuid
        ]);
    }

    /**
     * Displays a single RestaurantBranch model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $restaurantUuid) {
        return $this->render('view', [
                    'model' => $this->findModel($id, $restaurantUuid),
        ]);
    }

    /**
     * Creates a new RestaurantBranch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurantUuid) {
        $model = new RestaurantBranch();
        $model->restaurant_uuid = Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid;

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->restaurant_branch_id, 'restaurantUuid' => $model->restaurant_uuid]);
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
    public function actionUpdate($id, $restaurantUuid) {
        $model = $this->findModel($id, $restaurantUuid);

        if ($model->load(Yii::$app->request->post())) {

            $model->restaurant_uuid = Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid;

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->restaurant_branch_id, 'restaurantUuid' => $model->restaurant_uuid]);
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
    public function actionDelete($id, $restaurantUuid) {
        $this->findModel($id, $restaurantUuid)->delete();

        return $this->redirect(['index','restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Finds the RestaurantBranch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RestaurantBranch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $restaurantUuid) {
        if (($model = RestaurantBranch::findOne(['restaurant_branch_id' => $id, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid ])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
