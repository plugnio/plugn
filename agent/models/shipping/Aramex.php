<?php

namespace agent\models\shipping;

use common\models\Setting;
use yii\base\Model;


/**
 * Aramex form
 */
class Aramex extends Model
{
    public $restaurant_uuid;
    public $shipping_aramex_sandbox;

    public $shipping_aramex_account_number;
    public $shipping_aramex_account_entity;
    public $shipping_aramex_account_pin;
    public $shipping_aramex_username;
    public $shipping_aramex_password;

    public $shipping_aramex_city;
    public $shipping_aramex_country_code;
    public $shipping_aramex_state;
    public $shipping_aramex_post_code;

    public function init()
    {
        parent::init();

        if($this->restaurant_uuid) {

            $this->shipping_aramex_sandbox = (int) Setting::getConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_sandbox');

            $this->shipping_aramex_account_number = Setting::getConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_account_number');
            $this->shipping_aramex_account_entity = Setting::getConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_account_entity');
            $this->shipping_aramex_account_pin = Setting::getConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_account_pin');
            $this->shipping_aramex_username = Setting::getConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_username');
            $this->shipping_aramex_password = Setting::getConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_password');

            $this->shipping_aramex_city = Setting::getConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_city');
            $this->shipping_aramex_country_code = Setting::getConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_country_code');
            $this->shipping_aramex_state = Setting::getConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_state');
            $this->shipping_aramex_post_code = Setting::getConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_post_code');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shipping_aramex_sandbox', 'boolean']],
            [['shipping_aramex_account_number', 'shipping_aramex_account_entity',
                'shipping_aramex_account_pin', 'shipping_aramex_username', 'shipping_aramex_password',
                'shipping_aramex_city', 'shipping_aramex_country_code', 'shipping_aramex_state', 'shipping_aramex_post_code'], 'required']
        ];
    }

    public function save() {

        Setting::setConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_sandbox', $this->shipping_aramex_sandbox? "1": "0");

        Setting::setConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_account_number', $this->shipping_aramex_account_number);
        Setting::setConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_account_entity', $this->shipping_aramex_account_entity);
        Setting::setConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_account_pin', $this->shipping_aramex_account_pin);
        Setting::setConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_username', $this->shipping_aramex_username);
        Setting::setConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_password', $this->shipping_aramex_password);

        Setting::setConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_city', $this->shipping_aramex_city);
        Setting::setConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_country_code', $this->shipping_aramex_country_code);
        Setting::setConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_state', $this->shipping_aramex_state);
        Setting::setConfig($this->restaurant_uuid, "Aramex", 'shipping_aramex_post_code', $this->shipping_aramex_post_code);

        return true;
    }
}

