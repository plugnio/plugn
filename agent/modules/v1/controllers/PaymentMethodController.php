<?php

namespace agent\modules\v1\controllers;

use agent\models\PaymentMethod;
use agent\models\RestaurantPaymentMethod;
use Yii;
use agent\models\Restaurant;
use yii\web\NotFoundHttpException;

class PaymentMethodController extends BaseController
{
    /**
     * edit payment gateway settings
     * @param $code
     * @return string
     */
    public function actionConfig($code)
    {
        $store = $this->findModel();

        $name = "\agent\models\payment\\" . $code;

        $model = new $name;
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->payment_moyasar_api_secret_key = Yii::$app->request->getBodyParam('payment_moyasar_api_secret_key');
        $model->payment_moyasar_api_key = Yii::$app->request->getBodyParam('payment_moyasar_api_key');
        $model->payment_moyasar_payment_type = Yii::$app->request->getBodyParam('payment_moyasar_payment_type');
        $model->payment_moyasar_network_type = Yii::$app->request->getBodyParam('payment_moyasar_network_type');

        if ($model->save())
        {
            $payment_method = $store->getRestaurantPaymentMethods()
                ->joinWith('paymentMethod')
                ->andWhere(['payment_method_code' => PaymentMethod::CODE_MOYASAR])
                ->exists();

            if (!$payment_method) {

                $moyasarPaymentMethod = PaymentMethod::find()
                    ->andWhere(['payment_method_code' => PaymentMethod::CODE_MOYASAR])
                    ->one();

                $payments_method = new RestaurantPaymentMethod();
                $payments_method->payment_method_id = $moyasarPaymentMethod->payment_method_id;
                $payments_method->restaurant_uuid = $store->restaurant_uuid;

                if (!$payments_method->save()) {
                    return self::message("error", $payments_method->getErrors());
                }
            }

            return self::message('success', "Extension $code updated.");
        }

        return self::message('error', $model->errors);
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($store_uuid =  null)
    {
        $model = Yii::$app->accountManager->getManagedAccount($store_uuid);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }

    /**
     * @param string $type
     * @param $message
     * @return array
     */
    public static function message($type = "success", $message) {
        return [
            "operation" => $type,
            "message" => is_string ($message)? Yii::t('agent', $message): $message
        ];
    }
}