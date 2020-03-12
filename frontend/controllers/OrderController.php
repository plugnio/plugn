<?php

namespace frontend\controllers;

use Yii;
use common\models\Order;
use frontend\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Customer;
use common\models\Restaurant;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller {

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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionChangeOrderStatus($id, $status) {
        $order_model = $this->findModel($id);
        $order_model->setScenario('update-order-by-admin');

        $order_model->order_status = $status;
        $order_model->save(false);

        return $this->redirect(['view', 'id' => $order_model->order_id]);
    }

      return $this->redirect(['view', 'id' => $order_model->order_uuid]);
   }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {

        $order_model = $this->findModel($id);

        // Item
        $orderItems = new \yii\data\ActiveDataProvider([
            'query' => $order_model->getOrderItems(),
            'sort' => false
        ]);

        // Item extra optn
        $itemsExtraOpitons = new \yii\data\ActiveDataProvider([
            'query' => $order_model->getOrderItemExtraOptions()
        ]);

        return $this->render('view', [
                    'model' => $order_model,
                    'orderItems' => $orderItems,
                    'itemsExtraOpitons' => $itemsExtraOpitons,
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $model->setScenario('update-order-by-admin');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->order_uuid]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
