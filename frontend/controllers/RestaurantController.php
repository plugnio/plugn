<?php

namespace frontend\controllers;

use Yii;
use common\models\Restaurant;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RestaurantController implements the CRUD actions for Restaurant model.
 */
class RestaurantController extends Controller {

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
        ];
    }

    /**
     * Lists all Restaurant models.
     * @return mixed
     */
    public function actionIndex() {
        $restaurant_id = Yii::$app->user->identity->restaurant_uuid;

        return $this->render('view', [
                    'model' => $this->findModel($restaurant_id),
        ]);
    }

    /**
     * Updates an existing Restaurant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {

        $model = $this->findModel($id);
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            if ($model->restaurant_delivery_area)
                $model->saveRestaurantDeliveryArea($model->restaurant_delivery_area);

            if ($model->restaurant_payments_method)
                $model->saveRestaurantDeliveryArea($model->restaurant_delivery_area);


            if ($model->save()) {

                $thumbnail_image = \yii\web\UploadedFile::getInstances($model, 'thumbnail_image');
                $logo = \yii\web\UploadedFile::getInstances($model, 'logo');

                if ($thumbnail_image)
                    $model->uploadThumbnailImage($thumbnail_image[0]->tempName);

                if ($logo)
                    $model->uploadLogo($logo[0]->tempName);

                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
            }
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Change restaurant status to become open
     * @param integer $id => restaurant_uuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPromoteToOpen($id) {
        $model = $this->findModel($id);
        $model->promoteToOpenRestaurant();

        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Change restaurant status to become busy
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPromoteToBusy($id) {
        $model = $this->findModel($id);
        $model->promoteToBusyRestaurant();

        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Change restaurant status to become close
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPromoteToClose($id) {
        $model = $this->findModel($id);
        $model->promoteToCloseRestaurant();

        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Restaurant::findOne($id)) !== null && $id == Yii::$app->user->identity->restaurant_uuid) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
