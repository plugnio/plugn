<?php

namespace partners\controllers;

use Yii;
use common\models\PartnerPayout;
use common\models\Partner;
use partners\models\PartnerPayoutSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PartnerPayoutController implements the CRUD actions for PartnerPayout model.
 */
class PartnerPayoutController extends Controller
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
        ];
    }

    /**
     * Lists all PartnerPayout models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = PartnerPayout::find()->where(['partner_uuid' =>  Yii::$app->user->identity->partner_uuid , 'payout_status' => PartnerPayout::PAYOUT_STATUS_PAID]);
        $partner_model = Partner::find()->where(['partner_uuid' =>  Yii::$app->user->identity->partner_uuid ])->one();

        $dataProvider = new  \yii\data\ActiveDataProvider([
            'query' =>  $query,
        ]);


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'partner' => $partner_model,
        ]);
    }


    /**
     * Finds the PartnerPayout model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PartnerPayout the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PartnerPayout::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
