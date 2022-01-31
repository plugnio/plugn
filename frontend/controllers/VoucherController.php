<?php

namespace frontend\controllers;

use Yii;
use common\models\Voucher;
use frontend\models\VoucherSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Segment;

/**
 * VoucherController implements the CRUD actions for Voucher model.
 */
class VoucherController extends Controller
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
     * Lists all Voucher models.
     * @return mixed
     */
    public function actionIndex($storeUuid)
    {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $searchModel = new VoucherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $restaurant_model->restaurant_uuid);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'restaurant_model' => $restaurant_model
        ]);
    }

    /**
     * Creates a new Voucher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($storeUuid)
    {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        if($restaurant_model) {

          $model = new Voucher();
          $model->restaurant_uuid = $storeUuid;

          if ($model->load(Yii::$app->request->post())) {

            if( $model->duration && $model->duration != null )
              list($model->valid_from, $model->valid_until) = explode(' - ', $model->duration);


              if($model->save()){

              if(YII_ENV == 'prod') {

                 $discount_amount = $model->discount_amount;

                  if($model->currency->code == 'KWD'){
                    $discount_amount = $discount_amount * 3.28;
                  }
                  if($model->currency->code == 'SAR'){
                    $discount_amount = $discount_amount * 0.27;
                  }
                  if($model->currency->code == 'BHD'){
                    $discount_amount = $discount_amount *  2.65;
                  }



                  \Segment::init('2b6WC3d2RevgNFJr9DGumGH5lDRhFOv5');
                  \Segment::track([
                      'userId' => $storeUuid,
                      'event' => 'Voucher Created',
                      'properties' => [
                          'type' => $model->discountType,
                          'discountAmount' => $model->discount_amount ? ((float) $model->discount_amount * 3.28) : 0
                      ]
                  ]);
                }

                return $this->redirect(['index',  'storeUuid' => $storeUuid]);

              }
          }

          return $this->render('create', [
              'model' => $model,
              'storeUuid' => $storeUuid
          ]);
        }

    }

    /**
     * Updates an existing Voucher model.
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
     * Deletes an existing Voucher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionChangeVoucherStatus($id, $storeUuid)
    {
        $model = $this->findModel($id, $storeUuid);

        $model->voucher_status = $model->voucher_status == Voucher::VOUCHER_STATUS_ACTIVE ? Voucher::VOUCHER_STATUS_EXPIRED  : Voucher::VOUCHER_STATUS_ACTIVE;
        $model->save();

        return $this->redirect(['index', 'storeUuid' => $storeUuid]);

    }

    /**
     * Deletes an existing Voucher model.
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
     * Finds the Voucher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Voucher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $storeUuid)
    {
        $store = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $model = Voucher::find()
            ->where([
                'voucher_id' => $id,
                'restaurant_uuid' => $store->restaurant_uuid
            ])
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
