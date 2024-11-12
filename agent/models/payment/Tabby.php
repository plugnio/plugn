<?php

namespace agent\models\payment;

use common\models\Setting;
use yii\base\Model;

class Tabby extends Model
{
    public $restaurant_uuid;
    public $payment_tabby_public_key;
    public $payment_tabby_secret_key;

    public $payment_tabby_capture_on;
    public $payment_tabby_order_status;
    public $payment_tabby_promo;
    public $payment_tabby_capture_status;

    public $payment_tabby_cc_installments_status;
    public $payment_tabby_installments_status;
    public $payment_tabby_paylater_status;
    public $payment_tabby_promo_limit;
    public $payment_tabby_promo_theme;
    public $payment_tabby_promo_min_price;
    public $payment_tabby_cancel_status_id;
    public $payment_tabby_debug;

    public function init()
    {
        parent::init();

        if($this->restaurant_uuid) {
            $this->payment_tabby_public_key = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_public_key');
            $this->payment_tabby_secret_key = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_secret_key');

            //order_placed
            $this->payment_tabby_capture_on = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_capture_on');
            $this->payment_tabby_order_status = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_order_status');
            $this->payment_tabby_promo = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_promo');
            $this->payment_tabby_capture_status = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_capture_status');
            $this->payment_tabby_cc_installments_status = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_cc_installments_status');

            $this->payment_tabby_promo_theme = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_promo_theme');
            $this->payment_tabby_installments_status = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_installments_status');
            $this->payment_tabby_paylater_status = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_paylater_status');

            $this->payment_tabby_promo_limit = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_promo_limit');//max limit
            $this->payment_tabby_promo_min_price = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_promo_min_price');
            $this->payment_tabby_cancel_status_id = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_cancel_status_id');
            $this->payment_tabby_debug = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_debug');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_tabby_public_key', "payment_tabby_secret_key"], 'required'],
            [['payment_tabby_capture_on', 'payment_tabby_order_status','payment_tabby_promo',
                'payment_tabby_capture_status', 'payment_tabby_cc_installments_status',
                'payment_tabby_installments_status', 'payment_tabby_paylater_status', 'payment_tabby_promo_limit',
                'payment_tabby_promo_theme', 'payment_tabby_promo_min_price','payment_tabby_cancel_status_id',
                'payment_tabby_debug'], "safe"]
        ];
    }

    /**
     * @return bool
     */
    public function save() {

        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_public_key', $this->payment_tabby_public_key);
        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_secret_key', $this->payment_tabby_secret_key);

        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_capture_on', $this->payment_tabby_capture_on);
        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_order_status', $this->payment_tabby_order_status);
        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_promo', $this->payment_tabby_promo);
        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_capture_status', $this->payment_tabby_capture_status);
        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_cc_installments_status', $this->payment_tabby_cc_installments_status);
        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_installments_status', $this->payment_tabby_installments_status);
        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_paylater_status', $this->payment_tabby_paylater_status);
        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_promo_limit', $this->payment_tabby_promo_limit);
        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_promo_theme', $this->payment_tabby_promo_theme);

        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_promo_min_price', $this->payment_tabby_promo_min_price);
        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_cancel_status_id', $this->payment_tabby_cancel_status_id);
        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_debug', $this->payment_tabby_debug);

        return true;
    }
}