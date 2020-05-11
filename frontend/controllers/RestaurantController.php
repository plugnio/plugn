<?php

namespace frontend\controllers;

use Yii;
use common\models\Restaurant;
use common\models\AgentAssignment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RestaurantController implements the CRUD actions for Restaurant model.
 */
class RestaurantController extends Controller {

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
     * Lists all Restaurant models.
     * @return mixed
     */
    public function actionIndex($restaurantUuid) {

        $model = $this->findModel($restaurantUuid);

        return $this->render('view', [
                    'model' => $model
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

            if(!$model->phone_number)
                $model->phone_number_display = Restaurant::PHONE_NUMBER_DISPLAY_DONT_SHOW_PHONE_NUMBER;
            
            if ($model->save()) {

                $thumbnail_image = \yii\web\UploadedFile::getInstances($model, 'restaurant_thumbnail_image');
                $logo = \yii\web\UploadedFile::getInstances($model, 'restaurant_logo');

                if ($model->restaurant_payments_method)
                    $model->saveRestaurantPaymentMethod($model->restaurant_payments_method);

                if ($thumbnail_image)
                    $model->uploadThumbnailImage($thumbnail_image[0]->tempName);

                if ($logo)
                    $model->uploadLogo($logo[0]->tempName);


                return $this->redirect(['index', 'restaurantUuid' => $id]);
            }
        }

        return $this->render('update', [
                    'model' => $model,
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

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($id);

        if ($restaurant_model !== null) {
            if (AgentAssignment::isOwner($id))
                return $restaurant_model;
            else
                throw new \yii\web\BadRequestHttpException('Sorry, you are not allowed to access this page.');
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
