<?php

namespace agent\modules\v1\controllers;

use agent\models\ShippingMethod;
use agent\models\RestaurantShippingMethod;
use Yii;
use agent\models\Restaurant;
use yii\web\NotFoundHttpException;


class ShippingMethodController extends BaseController
{
    /**
     * edit shipping gateway settings
     * @param $code
     * @return string
     */
    public function actionConfig($code)
    {
        $store = $this->findModel();

        $name = "\agent\models\shipping\\" . $code;

        $model = new $name;
        $model->restaurant_uuid = $store->restaurant_uuid;

        if ($code == "Aramex") {
            return $this->_configAramex($model, $store, $code);
        } else if ($code == "Fedex") {
            return $this->_configFedex($model, $store, $code);
        } else if ($code == "FlatRate") {
            return $this->_configFlatRate($model, $store, $code);
        }
    }

    private function _configAramex($model, $store, $code)
    {
        $model->shipping_aramex_sandbox = Yii::$app->request->getBodyParam('sandbox');
        
        $model->shipping_aramex_account_number = Yii::$app->request->getBodyParam('account_number');
        $model->shipping_aramex_account_entity = Yii::$app->request->getBodyParam('account_entity');
        $model->shipping_aramex_account_pin = Yii::$app->request->getBodyParam('account_pin');
        $model->shipping_aramex_username = Yii::$app->request->getBodyParam('username');
        $model->shipping_aramex_password = Yii::$app->request->getBodyParam('password');
        
        $model->shipping_aramex_city = Yii::$app->request->getBodyParam('city');
        $model->shipping_aramex_country_code = Yii::$app->request->getBodyParam('country_code');
        $model->shipping_aramex_state = Yii::$app->request->getBodyParam('state');
        $model->shipping_aramex_post_code = Yii::$app->request->getBodyParam('post_code');

        if ($model->save())
        {
            $shipping_method = $store->getRestaurantShippingMethods()
                ->joinWith('shippingMethod')
                ->andWhere(['shipping_method_code' => ShippingMethod::CODE_ARAMEX])
                ->exists();

            if (!$shipping_method) {

                $aramexShippingMethod = ShippingMethod::find()
                    ->andWhere(['shipping_method_code' => ShippingMethod::CODE_STRIPE])
                    ->one();

                $shippings_method = new RestaurantShippingMethod();
                $shippings_method->shipping_method_id = $aramexShippingMethod->shipping_method_id;
                $shippings_method->restaurant_uuid = $store->restaurant_uuid;

                if (!$shippings_method->save()) {
                    return self::message("error", $shippings_method->getErrors());
                }
            }

            return self::message('success', "Extension $code updated.");
        }

        return self::message('error', $model->errors);
    }

    private function _configFedex($model, $store, $code)
    {

    }

    private function _configFlatRate($model, $store, $code)
    {

    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($store_uuid = null)
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
    public static function message($type = "success", $message)
    {
        return [
            "operation" => $type,
            "message" => is_string($message) ? Yii::t('agent', $message) : $message
        ];
    }
}