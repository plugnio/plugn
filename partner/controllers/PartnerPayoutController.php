<?php

namespace partner\controllers;

use Yii;
use common\models\PartnerPayout;
use common\models\Partner;
use common\models\SubscriptionPayment;
use common\models\Payment;
use partner\models\PartnerPayoutSearch;
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
        $query = PartnerPayout::find()->where(['partner_uuid' =>  Yii::$app->user->identity->partner_uuid]);
        $partner_model = Partner::find()->where(['partner_uuid' =>  Yii::$app->user->identity->partner_uuid ])->one();

        $totalEarningsFromOrders = $partner_model->getTotalEarningsFromOrders() ? $partner_model->getTotalEarningsFromOrders() : 0;
        $totalEarningsFromSubscriptions = $partner_model->getTotalEarningsFromSubscriptions() ? $partner_model->getTotalEarningsFromSubscriptions() : 0;

        $totalEarnings = $totalEarningsFromOrders + $totalEarningsFromSubscriptions;



        $dataProvider = new  \yii\data\ActiveDataProvider([
            'query' =>  $query,
        ]);


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'partner' => $partner_model,
            'totalEarnings' => $totalEarnings,
        ]);
    }


    /**
     * @return mixed
     */
    public function actionPending()
    {
        $query = PartnerPayout::find()->where(['partner_uuid' =>  Yii::$app->user->identity->partner_uuid]);
        $partner_model = Partner::find()->where(['partner_uuid' =>  Yii::$app->user->identity->partner_uuid ])->one();


        $subscriptionPayments = $partner_model->getSubscriptionPayments()
          ->andWhere(['subscription_payment.payout_status' => SubscriptionPayment::PAYOUT_STATUS_UNPAID])->with('restaurant')->all();

        $payments = $partner_model->getPayments()->andWhere(['payment.payout_status' => Payment::PAYOUT_STATUS_UNPAID])->all();

        $payments = array_merge($subscriptionPayments,$payments);

        $totalEanings = 0;

        foreach ($payments as $key => $payment) {
          $totalEanings += $payment->partner_fee;
        }

        return $this->render('pending', [
            'payments' => $payments,
            'partner' => $partner_model,
            'totalEanings' => $totalEanings,
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
