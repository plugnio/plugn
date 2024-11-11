<?php

namespace agent\modules\v1\controllers;

use agent\models\PaymentMethod;
use agent\models\RestaurantPaymentMethod;
use common\models\RestaurantUpload;
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

        if($code == "Moyasar") {
            return $this->_configMoyasar($model, $store, $code);
        } else if($code == "Stripe") {
            return $this->_configStripe($model, $store, $code);
        } else if($code == "UPayment") {
            return $this->_configUPayment($model, $store, $code);
        }else if($code == "Tabby") {
            return $this->_configTabby($model, $store, $code);
        }
    }

    private function _configTabby($model, $store, $code) {

        $model->payment_tabby_public_key = Yii::$app->request->getBodyParam('payment_tabby_public_key');
        $model->payment_tabby_secret_key = Yii::$app->request->getBodyParam('payment_tabby_secret_key');

        if ($model->save())
        {
            $payment_method = $store->getRestaurantPaymentMethods()
                ->joinWith('paymentMethod')
                ->andWhere(['payment_method_code' => PaymentMethod::CODE_TABBY])
                ->exists();

            if (!$payment_method) {

                $upayPaymentMethod = PaymentMethod::find()
                    ->andWhere(['payment_method_code' => PaymentMethod::CODE_TABBY])
                    ->one();

                $payments_method = new RestaurantPaymentMethod();
                $payments_method->payment_method_id = $upayPaymentMethod->payment_method_id;
                $payments_method->restaurant_uuid = $store->restaurant_uuid;

                if (!$payments_method->save()) {
                    return self::message("error", $payments_method->getErrors());
                }
            }

            return self::message('success', "Extension $code updated.");
        }

        return self::message('error', $model->errors);
    }

    private function _configUPayment($model, $store, $code) {

        $model->payment_upayment_api_key = Yii::$app->request->getBodyParam('payment_upayment_api_key');

        if ($model->save())
        {
            $payment_method = $store->getRestaurantPaymentMethods()
                ->joinWith('paymentMethod')
                ->andWhere(['payment_method_code' => PaymentMethod::CODE_UPAYMENT])
                ->exists();

            if (!$payment_method) {

                $upayPaymentMethod = PaymentMethod::find()
                    ->andWhere(['payment_method_code' => PaymentMethod::CODE_UPAYMENT])
                    ->one();

                $payments_method = new RestaurantPaymentMethod();
                $payments_method->payment_method_id = $upayPaymentMethod->payment_method_id;
                $payments_method->restaurant_uuid = $store->restaurant_uuid;

                if (!$payments_method->save()) {
                    return self::message("error", $payments_method->getErrors());
                }
            }

            return self::message('success', "Extension $code updated.");
        }

        return self::message('error', $model->errors);
    }

    private function _configStripe($model, $store, $code) {

        $model->payment_stripe_secret_key = Yii::$app->request->getBodyParam('payment_stripe_secret_key');
        $model->payment_stripe_publishable_key = Yii::$app->request->getBodyParam('payment_stripe_publishable_key');

        if ($model->save())
        {
            $payment_method = $store->getRestaurantPaymentMethods()
                ->joinWith('paymentMethod')
                ->andWhere(['payment_method_code' => PaymentMethod::CODE_STRIPE])
                ->exists();

            if (!$payment_method) {

                $moyasarPaymentMethod = PaymentMethod::find()
                    ->andWhere(['payment_method_code' => PaymentMethod::CODE_STRIPE])
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


    private function _configMoyasar($model, $store, $code) {

        $model->payment_moyasar_api_secret_key = Yii::$app->request->getBodyParam('payment_moyasar_api_secret_key');
        $model->payment_moyasar_api_key = Yii::$app->request->getBodyParam('payment_moyasar_api_key');
        $model->payment_moyasar_payment_type = Yii::$app->request->getBodyParam('payment_moyasar_payment_type');
        $model->payment_moyasar_network_type = Yii::$app->request->getBodyParam('payment_moyasar_network_type');
        $model->payment_moyasar_apple_domain_association = Yii::$app->request->getBodyParam('payment_moyasar_apple_domain_association');

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

            //apple domain association

            $upload = $store->getRestaurantUploads()
                ->andWhere(['filename' => "apple-developer-merchantid-domain-association"])
                ->one();

            if(!$upload)
                $upload = new RestaurantUpload;

            $upload->restaurant_uuid = $store->restaurant_uuid;
            $upload->content = $model->payment_moyasar_apple_domain_association;
            $upload->path =  ".well-known";
            $upload->filename = "apple-developer-merchantid-domain-association";
            $upload->created_by = Yii::$app->user->getId();

            if (!$upload->save()) {
                return self::message("error", $upload->getErrors());
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