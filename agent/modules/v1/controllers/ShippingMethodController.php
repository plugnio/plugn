<?php

namespace agent\modules\v1\controllers;

use common\models\ShippingMethod;
use common\models\RestaurantShippingMethod;
use Yii;
use agent\models\Restaurant;
use yii\web\NotFoundHttpException;


class ShippingMethodController extends BaseController
{
    public function actionDisable($code)
    {
        $store = $this->findModel();

        $shipping_method = $store->getRestaurantShippingMethods()
            ->joinWith('shippingMethod')
            ->andWhere(['code' => $code])
            ->one();

        if($shipping_method)
            $shipping_method->delete();

        return self::message('success', "Extension $code updated.");
    }

    /**
     * edit shipping gateway settings
     * @param $code
     * @return string
     */
    public function actionConfig($code)
    {
        $store = $this->findModel();

        if ($code == "Aramex")
        {
            $name = "\agent\models\shipping\\" . $code;
            $model = new $name;
            $model->restaurant_uuid = $store->restaurant_uuid;

            return $this->_configAramex($model, $store);
        }
        else if ($code == "Fedex")
        {
            $name = "\agent\models\shipping\\" . $code;
            $model = new $name;
            $model->restaurant_uuid = $store->restaurant_uuid;

            return $this->_configFedex($model, $store);
        }
        else if ($code == "Armada")
        {
            return $this->_configArmada($store);
        }
        else if ($code == "Mashkor")
        {
            return $this->_configMashkor($store);
        }
        else if ($code == "FlatRate")
        {
            return $this->_configFlatRate($store);
        }
    }

    private function _configAramex($model, $store)
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

        if (!$model->save())
        {
            return self::message('error', $model->errors);
        }

        return $this->_enableMethod($store, ShippingMethod::CODE_ARAMEX);
    }

    private function _configFedex($model, $store)
    {
        $model->shipping_fedex_key = Yii::$app->request->getBodyParam('key');
        $model->shipping_fedex_password = Yii::$app->request->getBodyParam('password');
        $model->shipping_fedex_account = Yii::$app->request->getBodyParam('account');
        $model->shipping_fedex_meter = Yii::$app->request->getBodyParam('meter');
        $model->shipping_fedex_dropoff_type = Yii::$app->request->getBodyParam('dropoff_type');
        $model->shipping_fedex_fedpack_type = Yii::$app->request->getBodyParam('fedpack_type');
        $model->shipping_fedex_country_code= Yii::$app->request->getBodyParam('country_code');
        $model->shipping_fedex_postcode = Yii::$app->request->getBodyParam('postcode');

        if (!$model->save())
        {
            return self::message('error', $model->errors);
        }

        return $this->_enableMethod($store, ShippingMethod::CODE_FEDEX);

        /**"DROP_BOX";
        REGULAR_PICKUP
        REQUEST_COURIER
        DROP_BOX
        BUSINESS_SERVICE_CENTER
        STATION


        $fedex_fedpack_type = "FEDEX_BOX";
        /*
        YOUR_PACKAGING
        FEDEX_BOX
        FEDEX_PAK
                FEDEX_TUBE
                FEDEX_10KG_BOX
                FEDEX_25KG_BOX
                FEDEX_ENVELOPE
                FEDEX_EXTRA_LARGE_BOX
                FEDEX_LARGE_BOX
                FEDEX_MEDIUM_BOX
                FEDEX_SMALL_BOX*/


    }

    private function _configArmada($store)
    {
        $store->armada_api_key = Yii::$app->request->getBodyParam('api_key');

        $store->setScenario(\common\models\Restaurant::SCENARIO_UPDATE_DELIVERY);

        if (!$store->save()) {
            return self::message("error", $store->getErrors());
        }

        return $this->_enableMethod($store, ShippingMethod::CODE_ARMADA);
    }

    private function _configMashkor($store)
    {
        $store->mashkor_branch_id = Yii::$app->request->getBodyParam('branch_id');

        $store->setScenario(\common\models\Restaurant::SCENARIO_UPDATE_DELIVERY);

        if (!$store->save()) {
            return self::message("error", $store->getErrors());
        }

        return $this->_enableMethod($store, ShippingMethod::CODE_MASHKOR);
    }

    private function _configFlatRate($store)
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

    private function _enableMethod($store, $code) {

        $shipping_method = $store->getRestaurantShippingMethods()
            ->joinWith('shippingMethod')
            ->andWhere(['code' => $code])
            ->exists();

        if (!$shipping_method) {

            $aramexShippingMethod = ShippingMethod::find()
                ->andWhere(['code' => $code])
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