<?php

namespace backend\controllers;

use Yii;
use common\models\Restaurant;
use common\models\TapQueue;
use backend\models\RestaurantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

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
    public function actionIndex() {
        $searchModel = new RestaurantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Restaurant model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {

        $model = $this->findModel($id);

        // Store theme color
        $storeThemeColors = new \yii\data\ActiveDataProvider([
            'query' => $model->getRestaurantTheme(),
            'pagination' => false
        ]);


        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'storeThemeColors' => $storeThemeColors
        ]);
    }

    /**
     * Creates a new Tap account
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateTapAccount($restaurant_uuid) {
        $model = $this->findModel($restaurant_uuid);
        $model->setScenario(Restaurant::SCENARIO_CREATE_TAP_ACCOUNT);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            $restaurant_authorized_signature_file = UploadedFile::getInstances($model, 'restaurant_authorized_signature_file');
            $restaurant_commercial_license_file = UploadedFile::getInstances($model, 'restaurant_commercial_license_file');

            $owner_identification_file_front_side = UploadedFile::getInstances($model, 'owner_identification_file_front_side');
            $owner_identification_file_back_side = UploadedFile::getInstances($model, 'owner_identification_file_back_side');

            if (sizeof($restaurant_commercial_license_file) > 0)
                $model->restaurant_commercial_license_file = $restaurant_commercial_license_file[0]; //Commercial License

            if (sizeof($restaurant_authorized_signature_file) > 0)
                $model->restaurant_authorized_signature_file = $restaurant_authorized_signature_file[0]; //Authorized signature

            if (sizeof($owner_identification_file_front_side) > 0)
                $model->owner_identification_file_front_side = $owner_identification_file_front_side[0]; //Owner's civil id front side

            if (sizeof($owner_identification_file_back_side) > 0)
                $model->owner_identification_file_back_side = $owner_identification_file_back_side[0]; //Owner's civil id back side



            $model->createAnAccountOnTap();


            if ($model->validate() && $model->save()) {
                return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
            } else {
                Yii::$app->session->setFlash('error', print_r($model->errors, true));
            }
        }

        return $this->render('create_tap_account', [
                    'model' => $model,
        ]);
    }

    /**
     * Creates a new Restaurant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Restaurant();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->restaurant_payments_method)
                $model->saveRestaurantPaymentMethod($model->restaurant_payments_method);


            $thumbnail_image = UploadedFile::getInstances($model, 'restaurant_thumbnail_image');

            $logo = UploadedFile::getInstances($model, 'restaurant_logo');

            if ($thumbnail_image)
                $model->uploadThumbnailImage($thumbnail_image[0]->tempName);

            if ($logo)
                $model->uploadLogo($logo[0]->tempName);


            return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
        }

        return $this->render('create', [
                    'model' => $model,
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

            if ($model->restaurant_payments_method)
                $model->saveRestaurantPaymentMethod($model->restaurant_payments_method);


            if ($model->save()) {

                $thumbnail_image = UploadedFile::getInstances($model, 'restaurant_thumbnail_image');

                $logo = UploadedFile::getInstances($model, 'restaurant_logo');

                if ($thumbnail_image)
                    $model->uploadThumbnailImage($thumbnail_image[0]->tempName);

                if ($logo)
                    $model->uploadLogo($logo[0]->tempName);

                return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
            }else {
                Yii::$app->session->setFlash('error', print_r($model->errors, true));
            }
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Send store's data to Tap via email
     */
    public function actionSendEmailToTap($id) {
        $model = $this->findModel($id);
        $model->sendStoreDataToTap();

        return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
    }

    /**
     * Change restaurant status to become open
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPromoteToOpen($id) {
        $model = $this->findModel($id);
        $model->promoteToOpenRestaurant();

        return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
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

        return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
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

        return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
    }

    /**
     * Deletes an existing Restaurant model.
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
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Restaurant::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
