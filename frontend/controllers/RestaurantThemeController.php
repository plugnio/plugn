<?php

namespace frontend\controllers;

use Yii;
use common\models\RestaurantTheme;
use common\models\AgentAssignment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RestaurantThemeController implements the CRUD actions for RestaurantTheme model.
 */
class RestaurantThemeController extends Controller {

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
     * Displays a single RestaurantTheme model.
     * @param string $restaurantUuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionIndex($restaurantUuid) {

      $model = $this->findModel($restaurantUuid);

      if ($model->load(Yii::$app->request->post()) && $model->save()) {
          return $this->redirect(['index', 'restaurantUuid' => $model->restaurant_uuid]);
      }

      return $this->render('index', [
                  'model' => $model,
      ]);
    }

  
    /**
     * Finds the RestaurantTheme model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RestaurantTheme the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($restaurantUuid) {
        if (Yii::$app->accountManager->getManagedAccount($restaurantUuid)) {

            if (AgentAssignment::isOwner($restaurantUuid)) {
                if (($model = RestaurantTheme::findOne(Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid)) !== null) {
                    return $model;
                }
            } else {
                throw new \yii\web\BadRequestHttpException('Sorry, you are not allowed to access this page.');
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
