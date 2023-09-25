<?php

namespace backend\controllers;

use common\models\CampaignFilter;
use Yii;
use common\models\VendorCampaign;
use backend\models\VendorCampaignSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * VendorCampaignController implements the CRUD actions for VendorCampaign model.
 */
class VendorCampaignController extends Controller
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
     * Lists all VendorCampaign models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorCampaignSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VendorCampaign model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionStatus($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $this->findModel($id);
    }

    /**
     * Creates a new VendorCampaign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VendorCampaign();

        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();

            if( $model->save()) {

                $campaignFilter = Yii::$app->request->post('CampaignFilter');

                foreach ($campaignFilter as $key => $value) {
                    $cf = new CampaignFilter();
                    $cf->campaign_uuid = $model->campaign_uuid;
                    $cf->param = $key;
                    $cf->value = $value;

                    if (!$cf->save()) {
                        $transaction->rollBack();
                        break;
                    }
                }

                $transaction->commit();

                return $this->redirect(['view', 'id' => $model->campaign_uuid]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing VendorCampaign model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->campaign_uuid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * start campaign
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRun($id)
    {
        $model = $this->findModel($id);
        $model->status = VendorCampaign::STATUS_READY;

        if (!$model->save()) {
            foreach ($model->errors as $error)
                Yii::$app->session->addFlash('error', $error);
        }

        return $this->redirect(['view', 'id' => $model->campaign_uuid]);
    }

    /**
     * Deletes an existing VendorCampaign model.
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
     * Finds the VendorCampaign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return VendorCampaign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VendorCampaign::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
