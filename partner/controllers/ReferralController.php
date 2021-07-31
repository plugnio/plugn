<?php

namespace partner\controllers;

use Yii;
use common\models\Restaurant;
use common\models\TapQueue;
use common\models\Order;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
/**
 * StoreController implements the CRUD actions for Restaurant model.
 */
class ReferralController extends Controller {

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
     * Lists all Referrals stores models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = \common\models\Restaurant::find()->where(['referral_code' => Yii::$app->user->identity->referral_code]);

        $dataProvider = new ActiveDataProvider([
            'query' =>  $query,
        ]);


        return $this->render('index', [
            'dataProvider' => $dataProvider
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
        if (($model = Restaurant::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
