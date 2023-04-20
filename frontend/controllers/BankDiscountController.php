<?php

namespace frontend\controllers;

use Yii;
use common\models\BankDiscount;
use frontend\models\BankDiscountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BankDiscountController implements the CRUD actions for BankDiscount model.
 */
class BankDiscountController extends Controller
{
   public $enableCsrfValidation = false;


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
    * Lists all BankDiscount models.
     * @return mixed
     */
    public function actionIndex($storeUuid)
    {
        $restaurant = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $searchModel = new BankDiscountSearch();

        $count = $searchModel->search([], $restaurant->restaurant_uuid)->getCount();

        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            $restaurant->restaurant_uuid
        );

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'count' => $count,
            'restaurant' => $restaurant
        ]);
    }

    /**
     * Creates a new BankDiscount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($storeUuid)
    {
        $restaurant = Yii::$app->accountManager->getManagedAccount($storeUuid);

        if($restaurant){

          $model = new BankDiscount();
          $model->restaurant_uuid = $storeUuid;

          if ($model->load(Yii::$app->request->post())) {

            if( $model->duration && $model->duration != null )
              list($model->valid_from, $model->valid_until) = explode(' - ', $model->duration);

              if($model->save())
                return $this->redirect(['index',  'storeUuid' => $storeUuid]);
          }

          return $this->render('create', [
              'model' => $model,
              'storeUuid' => $storeUuid
          ]);
        }
    }

    /**
     * Updates an existing BankDiscount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $storeUuid)
    {
        $model = $this->findModel($id, $storeUuid);

        if($model->valid_from && $model->valid_until)
          $model->duration =  date('Y-m-d', strtotime( $model->valid_from ))  . ' - '. date('Y-m-d', strtotime( $model->valid_until ));

        if ($model->load(Yii::$app->request->post())) {

          if($model->duration)
              list($model->valid_from, $model->valid_until) = explode(' - ', $model->duration);

          if($model->save())
            return $this->redirect(['index','storeUuid' => $storeUuid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }



      /**
       * Deletes an existing BankDiscount model.
       * If deletion is successful, the browser will be redirected to the 'index' page.
       * @param integer $id
       * @return mixed
       * @throws NotFoundHttpException if the model cannot be found
       */
      public function actionChangeBankDiscountStatus($id, $storeUuid)
      {
          $model = $this->findModel($id, $storeUuid);

          $model->bank_discount_status = $model->bank_discount_status == BankDiscount::BANK_DISCOUNT_STATUS_ACTIVE ?
              BankDiscount::BANK_DISCOUNT_STATUS_EXPIRED  : BankDiscount::BANK_DISCOUNT_STATUS_ACTIVE;

          $model->save();

          return $this->redirect(['index', 'storeUuid' => $storeUuid]);

      }


    /**
     * Deletes an existing BankDiscount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionDelete($id, $storeUuid)
     {
         $this->findModel($id, $storeUuid)->delete();

         return $this->redirect(['index', 'storeUuid' => $storeUuid]);
     }

    /**
     * Finds the BankDiscount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BankDiscount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $storeUuid)
    {
        Yii::$app->accountManager->getManagedAccount($storeUuid);

        $model = BankDiscount::find()->where([
            'bank_discount.bank_discount_id' => $id,
            'bank_discount.restaurant_uuid' => $storeUuid
        ])->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
