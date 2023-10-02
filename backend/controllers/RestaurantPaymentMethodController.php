<?php

namespace backend\controllers;

use Yii;
use common\models\RestaurantPaymentMethod;
use backend\models\RestaurantPaymentMethodSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RestaurantPaymentMethodController implements the CRUD actions for RestaurantPaymentMethod model.
 */
class RestaurantPaymentMethodController extends Controller
{
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
     * Lists all RestaurantPaymentMethod models.
     * @return mixed
     */
    public function actionIndex($uuid = null)
    {
        $searchModel = new RestaurantPaymentMethodSearch();
        if ($uuid) {
            $searchModel->restaurant_uuid = $uuid;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RestaurantPaymentMethod model.
     * @param string $restaurant_uuid
     * @param integer $payment_method_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($restaurant_uuid, $payment_method_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($restaurant_uuid, $payment_method_id),
        ]);
    }

    /**
     * Creates a new RestaurantPaymentMethod model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RestaurantPaymentMethod();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'restaurant_uuid' => $model->restaurant_uuid, 'payment_method_id' => $model->payment_method_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RestaurantPaymentMethod model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $restaurant_uuid
     * @param integer $payment_method_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($restaurant_uuid, $payment_method_id)
    {
        $model = $this->findModel($restaurant_uuid, $payment_method_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'restaurant_uuid' => $model->restaurant_uuid, 'payment_method_id' => $model->payment_method_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RestaurantPaymentMethod model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $restaurant_uuid
     * @param integer $payment_method_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($restaurant_uuid, $payment_method_id)
    {
        $this->findModel($restaurant_uuid, $payment_method_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RestaurantPaymentMethod model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $restaurant_uuid
     * @param integer $payment_method_id
     * @return RestaurantPaymentMethod the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($restaurant_uuid, $payment_method_id)
    {
        if (($model = RestaurantPaymentMethod::findOne(['restaurant_uuid' => $restaurant_uuid, 'payment_method_id' => $payment_method_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
