<?php

namespace backend\controllers;

use backend\models\Admin;
use common\models\Currency;
use Yii;
use common\models\Plan;
use common\models\PlanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\PlanPrice;
use backend\models\PlanPriceSearch;

/**
 * PlanController implements the CRUD actions for Plan model.
 */
class PlanController extends Controller
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
                      'actions' => ['create', 'update', 'delete'],
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
     * Lists all Plan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Plan model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new PlanPriceSearch;
        $searchModel->plan_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            "searchModel" => $searchModel,
            "dataProvider" => $dataProvider
        ]);
    }

    /**
     * Creates a new Plan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Plan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->plan_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Plan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->plan_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * update price
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionUpdatePrice($id)
    {
        $model = $this->findModel($id);

        $currencies = \agent\models\Currency::find()
            ->all();

        $kwdCurrency = Currency::findOne(['code' => 'KWD']);

        $amountInUSD = $model->price / $kwdCurrency->rate;

        $data = [];

        foreach ($currencies as $currency)
        {
            $amount = $currency->code == "KWD"? $model->price:
                round($amountInUSD * $currency->rate, $currency->decimal_place);

            $data[] = [
                $model->plan_id,
                $currency->code,
                $amount,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
            ];
        }

        PlanPrice::deleteAll();

        Yii::$app->db->createCommand()->batchInsert('plan_price',
            ['plan_id', 'currency', 'price', "created_at", "updated_at"], $data)->execute();

        return $this->redirect(['view', 'id' => $model->plan_id]);
    }

    /**
     * Deletes an existing Plan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Plan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
