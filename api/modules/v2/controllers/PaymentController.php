<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\RestaurantPaymentMethod;
use common\models\PaymentMethod;
use common\models\Payment;
use common\models\Refund;
use common\models\Restaurant;
use yii\web\ForbiddenHttpException;

class PaymentController extends Controller {

    public function behaviors() {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count'
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }


    /**
     *  Return Payment details
     */
    public function actionMyFatoorahWebhook() {

      $data = Yii::$app->request->getBodyParam("Data");
      $eventType = Yii::$app->request->getBodyParam("EventType");
      \Yii::error('enter actionMyFatoorahWebhook => ' . $eventType, __METHOD__); // Log error faced by user


      $headers = Yii::$app->request->headers;

      $headerSignature = $headers->get('MyFatoorah-Signature');


      if($eventType == 1)  //1 For Transaction Status Changed
        $payCurrency = $data['PayCurrency'];
      else if  ($eventType == 2) //2 For Refund Status Changed
        $payCurrency = Yii::$app->request->getBodyParam("CountryIsoCode");;


      if($payCurrency == 'KWD')
        $secretKey = 'WmlCnGR8+MXAlNZ3lyMdW/mD06jXa2kWa44g21lPawoRTMoZpKmn39ihdcQKYKw3uax7QYfhuEK+qPDkIvzfmA=='; // from portal
      else if ($payCurrency == 'SAR')
        $secretKey = 'sFfT2vIPVu7+GWlGFWqyH47wuVfNrhnqNpg2FCScRDrhoDiEmyvCPKBJcWcPf4takQR21o/PBK/oXfabiq0dUg==';// from portal




      $isValidSignature = true;

       //Check If Enabled Secret Key and If The header has request
      if ($headerSignature != null)  {
        $isValidSignature = false;

        $data = Yii::$app->request->getBodyParam("Data");


        if( $eventType && $data){

          if (!$isValidSignature) {
                 $isValidSignature = Yii::$app->myFatoorahPayment->checkMyFatoorahSignature($data, $secretKey, $headerSignature);
                 if (!$isValidSignature) {
                   \Yii::error('enter actionMyFatoorahWebhook => ' . $eventType, __METHOD__); // Log error faced by user
                    throw new ForbiddenHttpException('Invalid Signature');
                 }
          }

          switch ($eventType) {
            case 1: //1 For Transaction Status Changed
                Payment::updatePaymentStatusFromMyFatoorahWebhook($data['InvoiceId'], $data);
            break;

            case 2: //2 For Refund Status Changed
                Refund::updateRefundStatus($data['RefundReference'], $data);
            break;

            }

          }

        return [
          'message' => 'success'
        ];
    }



    }


    /**
     * return a list of payments method that restaurant's owner added on agent dashboard
     */
    public function actionListAllRestaurantsPaymentMethod($id) {

        $model = Restaurant::findOne($id);

        return new ActiveDataProvider([
            'query' => $model->getPaymentMethods()->asArray(),
            'pagination' => false
        ]);
    }

}
