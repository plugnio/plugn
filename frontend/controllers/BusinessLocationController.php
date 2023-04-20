<?php

namespace frontend\controllers;

use Yii;
use common\models\Restaurant;
use common\models\BusinessLocation;
use frontend\models\BusinessLocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BusinessLocationController implements the CRUD actions for BusinessLocation model.
 */
class BusinessLocationController extends Controller
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
     * Lists all BusinessLocation models.
     * @return mixed
     */
    public function actionIndex($storeUuid)
    {
        $store = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $businessLocations = BusinessLocation::find()
            ->where(['business_location.restaurant_uuid' => $store->restaurant_uuid])
            ->all();

        return $this->render('index', [
            'businessLocations' => $businessLocations,
            'store' => $store
        ]);
    }

    /**
     * Creates a new BusinessLocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($storeUuid)
    {
        $store = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $model = new BusinessLocation();
        $model->restaurant_uuid = $store->restaurant_uuid;


        if ($model->load(Yii::$app->request->post()) ) {

          if($model->latitude && $model->longitude ) {
            $response = Yii::$app->googleMapComponent->getReverseGeocodeing($model->latitude,$model->longitude);

            if($response->isOk){

              $building = '';
              $block = '';
              $street = '';
              $area = '';
              $country = '';

              foreach ($response->data['results'] as $key => $address) {

                if(in_array('neighborhood', $address['types'])){
                   foreach ($address['address_components'] as $key => $adress_component) {
                     if(in_array('neighborhood', $adress_component['types'])){
                       $block = $adress_component['long_name'];
                     }
                   }
               }

                else if(in_array('route', $address['types'])){
                    foreach ($address['address_components'] as $key => $adress_component) {
                      if(in_array('route', $adress_component['types'])){
                        $street = $adress_component['long_name'];
                      }
                    }
                }

                else if(in_array('premise', $address['types'])){
                    foreach ($address['address_components'] as $key => $adress_component) {
                      if(in_array('premise', $adress_component['types'])){
                        $building = $adress_component['long_name'];
                      }
                    }
                }


                else if(in_array('sublocality', $address['types'])){
                    foreach ($address['address_components'] as $key => $adress_component) {
                      if(in_array('sublocality', $adress_component['types'])){
                        $area = $adress_component['long_name'];
                      }
                    }
                }


                else if(in_array('political', $address['types'])){
                    foreach ($address['address_components'] as $key => $adress_component) {
                      if(in_array('political', $adress_component['types'])){
                        $country = $adress_component['long_name'];
                      }
                    }
                }




              }

              $address = '';

              if($building)
                $address = $building . ', ';

              if($block)
                $address .= $block . ', ';

              if($street)
                $address .= $street . ', ';

              if($area)
                $address .= $area . ', ';

                $address .=   $country;


              $model->address = $address;

            } else {
              Yii::error('[ ReverseGeocodeing ]' . json_encode($response) . ' lat: '. $lat . ', lng: '. $lng , __METHOD__);
            }
          }

          if($model->save()) {
              return $this->redirect(['index', 'storeUuid' => $storeUuid]);
          } else {
              Yii::$app->session->setFlash('error', $model->errors);
          }
        }

        return $this->render('create', [
            'model' => $model,
            'store' => $store
        ]);
    }

    /**
     * @param $id
     * @param $storeUuid
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionEnablePickup($id, $storeUuid)
    {
        $model = $this->findModel($id, $storeUuid);
        $model->support_pick_up = 1;

        $model->setScenario(BusinessLocation::SCENARIO_UPDATE_PICK_UP);

        if(!$model->save())
        {
            Yii::$app->session->setFlash('error', $model->errors);
        }

        return $this->redirect(['index',  'storeUuid' => $storeUuid]);

    }

    /**
     * @param $id
     * @param $storeUuid
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDisablePickup($id, $storeUuid)
    {
        $model = $this->findModel($id, $storeUuid);

        $model->setScenario(BusinessLocation::SCENARIO_UPDATE_PICK_UP);

        $model->support_pick_up = 0;

        if(!$model->save())
        {
            Yii::$app->session->setFlash('error', $model->errors);
        }

        return $this->redirect(['index',  'storeUuid' => $storeUuid]);
    }

    /**
     * @param $id
     * @param $storeUuid
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRemoveTax($id, $storeUuid)
    {
        $model = $this->findModel($id, $storeUuid);

        $model->setScenario(BusinessLocation::SCENARIO_UPDATE_TAX);

        $model->business_location_tax = 0;

        if(!$model->save())
        {
            Yii::$app->session->setFlash('error', $model->errors);
        }

        return $this->redirect(['index',  'storeUuid' => $storeUuid]);
    }

    /**
     * @param $id
     * @param $storeUuid
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionConfigureTax($id, $storeUuid)
    {
        $model = $this->findModel($id, $storeUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index',  'storeUuid' => $storeUuid]);
        }

        if($model->errors)
        {
            Yii::$app->session->setFlash('error', $model->errors);
        }

        return $this->render('_set-tax', [
            'model' => $model,
            'storeUuid' => $storeUuid
        ]);
    }

    /**
     * Updates an existing BusinessLocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $storeUuid)
    {
        $model = $this->findModel($id, $storeUuid);


        if ($model->load(Yii::$app->request->post()) ) {

          if($model->latitude && $model->longitude ) {
            $response = Yii::$app->googleMapComponent->getReverseGeocodeing($model->latitude,$model->longitude);

            if($response->isOk){

              $building = '';
              $block = '';
              $street = '';
              $area = '';
              $country = '';

              foreach ($response->data['results'] as $key => $address) {

                if(in_array('neighborhood', $address['types'])){
                   foreach ($address['address_components'] as $key => $adress_component) {
                     if(in_array('neighborhood', $adress_component['types'])){
                       $block = $adress_component['long_name'];
                     }
                   }
               }

                else if(in_array('route', $address['types'])){
                    foreach ($address['address_components'] as $key => $adress_component) {
                      if(in_array('route', $adress_component['types'])){
                        $street = $adress_component['long_name'];
                      }
                    }
                }

                else if(in_array('premise', $address['types'])){
                    foreach ($address['address_components'] as $key => $adress_component) {
                      if(in_array('premise', $adress_component['types'])){
                        $building = $adress_component['long_name'];
                      }
                    }
                }


                else if(in_array('sublocality', $address['types'])){
                    foreach ($address['address_components'] as $key => $adress_component) {
                      if(in_array('sublocality', $adress_component['types'])){
                        $area = $adress_component['long_name'];
                      }
                    }
                }


                else if(in_array('political', $address['types'])){
                    foreach ($address['address_components'] as $key => $adress_component) {
                      if(in_array('political', $adress_component['types'])){
                        $country = $adress_component['long_name'];
                      }
                    }
                }

              }

              $address = '';

              if($building)
                $address = $building . ', ';

              if($block)
                $address .= $block . ', ';

              if($street)
                $address .= $street . ', ';

              if($area)
                $address .= $area . ', ';

                $address .=   $country;


              $model->address = $address;

            } else {
              Yii::error('[ ReverseGeocodeing ]' . json_encode($response) . ' lat: '. $lat . ', lng: '. $lng , __METHOD__);
            }
          }

          if($model->save()) {
              return $this->redirect(['index', 'storeUuid' => $storeUuid]);
          } else {
              Yii::$app->session->setFlash('error', $model->errors);
          }
        }

        return $this->render('update', [
            'model' => $model,
            'store' => Restaurant::findOne($storeUuid)
        ]);
    }

    /**
     * Deletes an existing BusinessLocation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $storeUuid)
    {
        $model = $this->findModel($id, $storeUuid);

        if(!$model->delete())
        {
            Yii::$app->session->setFlash('error', $model->errors);
        }

        return $this->redirect(['index', 'storeUuid' => $storeUuid]);
    }

    /**
     * Finds the BusinessLocation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusinessLocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $storeUuid)
    {
        $store = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $model = BusinessLocation::find()->where([
            'business_location.business_location_id' => $id,
            'business_location.restaurant_uuid' => $store->restaurant_uuid
        ])->one();

        if (($model) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
