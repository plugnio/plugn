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
use yii\db\Expression;

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


        $payments = new \yii\data\ActiveDataProvider([
            'query' => $model->getPayments(),
            'pagination' => false
        ]);

        $subscriptionPayments = new \yii\data\ActiveDataProvider([
            'query' => $model->getSubscriptionPayments(),
            'pagination' => false
        ]);


        return $this->render('view', [
            'model' => $model,
            'payments' => $payments,
            'subscriptionPayments' => $subscriptionPayments,
            'pendingAmount' => $model->totalEarnings + $model->pendingPayouts
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
     * Create payout
     * @param string $partner_uuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCreatePayout($partner_uuid)
    {
          $model = $this->findModel($partner_uuid);
          $payments = $model->getPayments()
                          ->joinWith(['partner'])
                          ->andWhere(['payment.payout_status' => Payment::PAYOUT_STATUS_PENDING])
                          ->andWhere(['payment.partner_payout_uuid' => null])
                          ->andWhere (new Expression('DATE(payment.payment_created_at) >= DATE(partner.partner_created_at) '))
                          ->andWhere (['>' , 'payment.partner_fee', 0])
                          ->all();


          $subscriptionPayments = $model->getSubscriptionPayments()
                          ->joinWith(['restaurant','restaurant.partner'])
                          ->andWhere(['subscription_payment.payout_status' => Payment::PAYOUT_STATUS_PENDING])
                          ->andWhere(['subscription_payment.partner_payout_uuid' => null])
                          ->andWhere (new Expression('DATE(subscription_payment.payment_created_at) >= DATE(partner.partner_created_at) '))
                          ->andWhere (['>' , 'subscription_payment.partner_fee', 0])
                          ->all();



          if(sizeof($payments) > 0 || sizeof($subscriptionPayments) > 0){


            $partner_payout_model = new PartnerPayout;
            $partner_payout_model->partner_uuid = $model->partner_uuid;
            $partner_payout_model->transfer_benef_iban = $model->partner_iban;
            $partner_payout_model->transfer_benef_name = $model->benef_name;
            $partner_payout_model->bank_id = $model->bank_id;

            $partner_payout_model->payout_status = PartnerPayout::PAYOUT_STATUS_PENDING;

            if(!$partner_payout_model->save())
              Yii::$app->session->setFlash('error', print_r($partner_payout_model->errors, true));

            $payout_amount = 0;



            foreach ($payments as $payment) {

              $payout_amount += $payment->partner_fee;
              $payment->partner_payout_uuid = $partner_payout_model->partner_payout_uuid;
              if(!$payment->save())
                Yii::$app->session->setFlash('error', print_r($payment->errors, true));

            }



            foreach ($subscriptionPayments as $payment) {
              $payout_amount += $payment->partner_fee;

              $payment->partner_payout_uuid = $partner_payout_model->partner_payout_uuid;
              if(!$payment->save())
                Yii::$app->session->setFlash('error', print_r($payment->errors, true));
            }


            $partner_payout_model->amount = $payout_amount;
            if(!$partner_payout_model->save()){
              Yii::$app->session->setFlash('error', print_r($partner_payout_model->errors, true));
              Yii::error('[Error while Creating payout]' . json_encode($partner_payout_model->errors), __METHOD__);
            }

          }

         return $this->redirect(['index', 'partner_uuid' => $model->partner_uuid]);

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
