<?php

namespace backend\controllers;

use Yii;
use common\models\Payment;
use common\models\PartnerPayout;
use common\models\Partner;
use common\models\SubscriptionPayment;
use backend\models\PartnerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PartnerController implements the CRUD actions for Partner model.
 */
class PartnerController extends Controller
{
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
     * Lists all Partner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PartnerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Partner model.
     * @param string $partner_uuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($partner_uuid)
    {
      $model = $this->findModel($partner_uuid);


        $payouts = new \yii\data\ActiveDataProvider([
            'query' => $model->getPartnerPayouts(),
            'pagination' => false
        ]);


        $totalEarningsFromOrders = $model->getTotalEarningsFromOrders() ? $model->getTotalEarningsFromOrders() : 0;
        $totalEarningsFromSubscriptions = $model->getTotalEarningsFromSubscriptions() ? $model->getTotalEarningsFromSubscriptions() : 0;

        $totalEarnings = $totalEarningsFromOrders + $totalEarningsFromSubscriptions;


        return $this->render('view', [
            'model' => $model,
            'payouts' => $payouts,
            'totalEarnings' => $totalEarnings,
        ]);
    }

    /**
     * Creates a new Partner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Partner();

        if ($model->load(Yii::$app->request->post())) {


          // $password = Yii::$app->security->generateRandomString(5);
          $password = '123456';

          $model->setPassword($password);

          if($model->save()){

            //Send Email to Partner
            // Partner::passwordMail($model, $password);


            return $this->redirect(['view', 'partner_uuid' => $model->partner_uuid]);
          }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }






    /**
     * Mark all payouts for that partner as paid.
     * @param string $partner_uuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCreatePayout($partner_uuid)
    {
          $model = $this->findModel($partner_uuid);
          $payments = $model->getPayments()
                          ->andWhere(['payment.payout_status' => Payment::PAYOUT_STATUS_UNPAID])
                          ->all();

          $subscriptionPayments = $model->getSubscriptionPayments()
                          ->andWhere(['subscription_payment.payout_status' => SubscriptionPayment::PAYOUT_STATUS_UNPAID])
                          ->all();

          if(sizeof($payments) > 0 || sizeof($subscriptionPayments) > 0){



            $partner_payout_model = new PartnerPayout;
            $partner_payout_model->partner_uuid = $model->partner_uuid;
            // $partner_payout_model->amount = $payment->partner_fee;
            $partner_payout_model->payout_status = PartnerPayout::PAYOUT_STATUS_PENDING;
            $partner_payout_model->save();

            $payout_amount = 0;




            foreach ($payments as $payment) {

              $payout_amount += $payment->partner_fee;
              $payment->partner_payout_uuid = $partner_payout_model->partner_payout_uuid;
              $payment->save();
            }



            foreach ($subscriptionPayments as $payment) {
              $payout_amount += $payment->partner_fee;

              $payment->partner_payout_uuid = $partner_payout_model->partner_payout_uuid;
              $payment->save();
            }


            $partner_payout_model->amount = $payout_amount;
            if(!$partner_payout_model->save()){
              Yii::error('[Error while Creating payout]' . json_encode($partner_payout_model->errors), __METHOD__);
            }

          } 

         return $this->redirect(['view', 'partner_uuid' => $model->partner_uuid]);

    }


    /**
     * Mark  payout as paid
     * @param string $partner_uuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMarkAsPaid($partner_payout_uuid)
    {
          $model = PartnerPayout::findOne($partner_payout_uuid);

          $model->payout_status = Payment::PAYOUT_STATUS_PAID;
          if($model->save()){
            return $this->redirect(['view', 'partner_uuid' => $model->partner_uuid]);
          }

         return $this->redirect(['view', 'partner_uuid' => $model->partner_uuid]);
    }



    /**
     * Updates an existing Partner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $partner_uuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($partner_uuid)
    {
        $model = $this->findModel($partner_uuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'partner_uuid' => $model->partner_uuid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Partner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $partner_uuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($partner_uuid)
    {
        $this->findModel($partner_uuid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Partner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $partner_uuid
     * @return Partner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($partner_uuid)
    {
        if (($model = Partner::find()->where(['partner_uuid' => $partner_uuid])->one() ) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
