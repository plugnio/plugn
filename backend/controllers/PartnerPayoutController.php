<?php

namespace backend\controllers;

use backend\models\Admin;
use Yii;
use backend\models\TranferExcel;
use common\models\PartnerPayout;
use common\models\PartnerPayoutSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PartnerPayoutController implements the CRUD actions for PartnerPayout model.
 */
class PartnerPayoutController extends Controller
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
                  [
                      'allow' => Yii::$app->user->identity && Yii::$app->user->identity->admin_role != Admin::ROLE_CUSTOMER_SERVICE_AGENT,
                      'actions' => ['create', 'update', 'delete', 'import-expert'],
                      'roles' => ['@'],
                  ],
                  [//allow authenticated users only
                      'allow' => true,
                      'roles' => ['@'],
                  ],
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
        $searchModel = new PartnerPayoutSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * method to generate text file for all unpaid partners
     * @return array
     */
    public function actionDownloadTransferFile()
    {
        $s1 = 'S1,11622216,,MXD,M,,'.date('d/m/Y').','.date('dmY').'-01'.PHP_EOL; // header line
        $s2 = '';

        $partners = PartnerPayout::getPayablePartnerListFormat();

        if(!$partners) {
            return [
                "operation" => "error",
                "message" => 'No Payable Partners!'
            ];
        }

        foreach ($partners['partner_list'] as $detail) {
            $s2 .=  implode(',',$detail).",".PHP_EOL;
        }

        $s3 = 'S3,'.count($partners['partner_list']).','.$partners['total_amount']; // Footer
        $sAll = $s1.$s2.$s3;

        $fileName = 'BAWS-PAY-'.date('dmY').'-01.txt';

        $path = sys_get_temp_dir() .DIRECTORY_SEPARATOR. $fileName;

        $handle = fopen($path, "w");
        fwrite($handle, $sAll);
        fclose($handle);

        Yii::$app->response->headers->add('filename', $fileName);

        return Yii::$app->response->sendFile($path);
    }

    /**
     * @return array|string|\yii\web\Response
     */
    public function actionImportExcel()
    {
          $model = new TranferExcel();

          if ($model->load(Yii::$app->request->post())) {

              $tempFile = UploadedFile::getInstances($model, 'excel') ;
              $tempFile = $tempFile[0];
              $tempFile->saveAs('uploads/' . $tempFile->baseName . '.' . $tempFile->extension);

              $tmpFile = Yii::getAlias ('@transferFiles') . '/' . $tempFile->baseName . '.' . $tempFile->extension;

              if($transferFilePath = $model->uploadTransferFile($tmpFile)){

                  $excelData  = \common\components\PhpExcel::import($tmpFile,  [
                    'setFirstRecordAsKeys' => false
                  ]);

                  //remove first blank row

                  \yii\helpers\ArrayHelper::remove($excelData, '1');

                  //second row will be key

                  $keys = \yii\helpers\ArrayHelper::remove($excelData, '2');

                  //create array with key to read data

                  $data = [];

                  foreach ($excelData as $values)
                  {
                    $data[] = array_combine($keys, $values);
                  }
                  //no need file anymore

                  @unlink($tmpFile);

                  //remove empty rows

                  $total = 0;

                  $partnersTransfers = [];

                  foreach ($data as $key => $value)
                  {



                    if(empty($value['Status'])) {
                        return [
                            'operation' => 'error',
                            'message' => 'Invalid excel',
                            'errorCode' => 1
                        ];
                    }

                    if($value['Status'] == 'FAIL') {

                        $transferPartner = PartnerPayout::find()->andWhere(['partner_payout_uuid' => $value['Credit Narrative']])->one();

                        if($transferPartner && $transferPartner->partner) {

                            $transferPartner->payout_status = PartnerPayout::PAYOUT_STATUS_UNPAID;
                            $transferPartner->transfer_benef_iban = null;
                            $transferPartner->transfer_benef_name = null;
                            $transferPartner->bank_id = null;
                            $transferPartner->transfer_file = $transferFilePath ;

                            if ($transferPartner->save(false)) {
                                $transferPartner->partner->bank_id = null;
                                $transferPartner->partner->benef_name = null;
                                $transferPartner->partner->partner_iban = null;
                                $transferPartner->partner->save(false);
                                //TODO
                                // if ($transferPartner->partner->save(false)) {
                                //     $transferPartner->unpaidNotification();
                                // }
                            }
                        }

                    }

                    if($value['Status'] == 'SUCCESS')  {
                        $transferPartner = PartnerPayout::find()->andWhere(['partner_payout_uuid' => $value['Credit Narrative']])->one();


                        if(!$transferPartner || !$transferPartner->partner) {
                          Yii::$app->session->setFlash('error', 'Invalid excel');

                        } else if ($transferPartner && $transferPartner->partner){

                          $transferPartner->payout_status = PartnerPayout::PAYOUT_STATUS_PAID;
                          $transferPartner->transfer_file = $transferFilePath ;
                          if(!$transferPartner->save(false)){
                            return $transferPartner->errors;
                          }
                        }

                        // $partnersTransfers[] = [
                        //     'transfer_confirmation_id' => $value['Status Description'],
                        //     'transfer_id' => $value['Debit Narrative'],
                        //     'tc_id' => $value['Credit Narrative'],
                        //     'partner_uuid' => $transferPartner->partner->partner_uuid,
                        //     'partner_name' => $transferPartner->partner->benef_name,
                        //     'total_amount' => $transferPartner->amount
                        // ];
                        //
                        // $total += $transferPartner->amount;
                    }

                  }

                  return $this->redirect(['index']);

                  // return [
                  //   'total' => $total,
                  //   'partners' => $partnersTransfers
                  // ];
            }
          }

        return $this->render('upload-transfer-file',[
          'model' => $model
        ]);
    }

    /**
     * Displays a single PartnerPayout model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

       $model = $this->findModel($id);

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
        ]);
    }

    /**
     * Creates a new PartnerPayout model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PartnerPayout();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->partner_payout_uuid]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PartnerPayout model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->partner_payout_uuid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PartnerPayout model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
