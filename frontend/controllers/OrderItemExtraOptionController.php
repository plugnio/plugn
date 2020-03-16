<?php

namespace frontend\controllers;

use Yii;
use common\models\OrderItemExtraOption;
use frontend\models\OrderItemExtraOptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\OrderItem;
use common\models\ExtraOption;
use yii\helpers\ArrayHelper;

/**
 * OrderItemExtraOptionController implements the CRUD actions for OrderItemExtraOption model.
 */
class OrderItemExtraOptionController extends Controller {

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
     * Creates a new OrderItemExtraOption model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        if (OrderItem::find()->where(['order_item_id' => $id])->exists()) {
            $model = new OrderItemExtraOption();
            $model->order_item_id = $id;

            //Get item's extra options to retrieve it on create-form page
            //todo retrieve item's extra optn only
            $extraOptions = ExtraOption::find()->asArray()->all();
            $extraOptionsQuery = ArrayHelper::map($extraOptions, 'extra_option_id', 'extra_option_name');

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->order_item_extra_option_id]);
            }

            return $this->render('create', [
                        'model' => $model,
                        'extraOptionsQuery' => $extraOptionsQuery
            ]);
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Displays a single OrderItemExtraOption model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing OrderItemExtraOption model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->order_item_extra_option_id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrderItemExtraOption model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {

        $model = $this->findModel($id);
        $order_item_id = $model->order_item_id;

        $this->findModel($id)->delete();

        return $this->redirect(['order-item/view', 'id' => $order_item_id]);
    }

    /**
     * Finds the OrderItemExtraOption model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderItemExtraOption the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = OrderItemExtraOption::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
