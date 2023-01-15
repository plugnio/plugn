<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\RestaurantPaymentMethod;
use common\models\PaymentMethod;
use api\models\Payment;
use common\models\Refund;
use api\models\Restaurant;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class PaymentController extends Controller
{

    public function behaviors()
    {
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
    public function actions()
    {
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
    public function actionMyFatoorahWebhook()
    {
        $data = Yii::$app->request->getBodyParam("Data");

        $eventType = Yii::$app->request->getBodyParam("EventType");

        $headers = Yii::$app->request->headers;

        $headerSignature = $headers->get('MyFatoorah-Signature');

        $countryIsoCode = Yii::$app->request->getBodyParam("CountryIsoCode");;

        if ($countryIsoCode == 'KWT')
            $secretKey = \Yii::$app->params['myfatoorah.kuwaitSecretKey']; // from portal
        else if ($countryIsoCode == 'SA')
            $secretKey = \Yii::$app->params['myfatoorah.saudiSecretKey'];// from portal

        $isValidSignature = true;

        //Check If Enabled Secret Key and If The header has request
        if ($headerSignature != null) {
            $isValidSignature = false;

            $data = Yii::$app->request->getBodyParam("Data");


            if ($eventType && $data) {

                if (!$isValidSignature) {
                    $isValidSignature = Yii::$app->myFatoorahPayment->checkMyFatoorahSignature($data, $secretKey, $headerSignature);
                    if (!$isValidSignature) throw new ForbiddenHttpException('Invalid Signature');

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
    public function actionListAllRestaurantsPaymentMethod($id)
    {
        $model = $this->findModel($id);

        $currency = \Yii::$app->currency->getCode();

        if(!$currency) {
            $currency = $model->currency->code;
        }

        $query = $model->getPaymentMethods()
            ->joinWith('paymentMethodCurrencies');

        //for premium stores

        if(!$model->platform_fee || $model->platform_fee == 0)
        {
            $query->andWhere([
                'OR',
                ['payment_method_currency.currency' => $currency],
                [
                    'IN',
                    'payment_method_code',
                    [
                        PaymentMethod::CODE_MOYASAR
                    ]
                ]
            ]);
        }
        else
        {
            $query->andWhere(['payment_method_currency.currency' => $currency]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Restaurant::find()
            ->where(['restaurant_uuid' => $id])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
