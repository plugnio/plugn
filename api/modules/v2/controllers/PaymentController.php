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

      $headers = Yii::$app->request->headers;

      $myFatoorahSignature = $headers->get('MyFatoorah-Signature');
      $webhookToken = 'rIp9GnDBQ3kZzZ+hJRNZAEtttGfnIs7AKHDvHuera2+V2sZv/n/55USbF2GvBf2E4vBefzfQX/QgeyYBSAi1rA==';


      $hash = hash_hmac('sha256', $webhookToken, $myFatoorahSignature);
      \Yii::error('$hash=>' . $hash, __METHOD__); // Log error faced by user
      \Yii::error('$myFatoorahSignature=>' . $myFatoorahSignature, __METHOD__); // Log error faced by user

      if($hash !== 'aa1506dc7ebc07a4c2b8af89e8b7c61fcb08fa0c4ae4e6dfd7671d63236ebb05'){
        return [
            'message' => 'Failed to authorize the request.'
        ];

           \Yii::error('Failed to authorize the request.', __METHOD__); // Log error faced by user

      }

      \Yii::error('authorized', __METHOD__); // Log error faced by user


      $eventType = Yii::$app->request->getBodyParam("EventType");
      $data = Yii::$app->request->getBodyParam("Data");

      if( $eventType && $data){

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
