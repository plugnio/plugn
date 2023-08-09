<?php

namespace agent\models\shipping;
 
use common\models\Setting;
use yii\base\Model;


/**
 * Fedex form
 */
class Fedex extends Model
{
    public $restaurant_uuid;

    public $shipping_fedex_key;
    public $shipping_fedex_account;
    public $shipping_fedex_meter;
    public $shipping_fedex_dropoff_type;
    public $shipping_fedex_fedpack_type;
    public $shipping_fedex_password;
    public $shipping_fedex_country_code;
    public $shipping_fedex_postcode;

    public function init()
    {
        parent::init();

        if ($this->restaurant_uuid) {

            $this->shipping_fedex_key = Setting::getConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_key');
            $this->shipping_fedex_password = Setting::getConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_password');
            $this->shipping_fedex_account = Setting::getConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_account');
            $this->shipping_fedex_meter = Setting::getConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_meter');
            $this->shipping_fedex_dropoff_type = Setting::getConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_dropoff_type');
            $this->shipping_fedex_fedpack_type = Setting::getConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_fedpack_type');
            $this->shipping_fedex_country_code = Setting::getConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_country_code');
            $this->shipping_fedex_postcode = Setting::getConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_postcode');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shipping_fedex_key', 'shipping_fedex_password', 'shipping_fedex_account',
                'shipping_fedex_meter', 'shipping_fedex_dropoff_type', 'shipping_fedex_fedpack_type',
                'shipping_fedex_country_code', 'shipping_fedex_postcode'], 'required']
        ];
    }

    public function save()
    {

        Setting::setConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_key', $this->shipping_fedex_key);

        Setting::setConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_password', $this->shipping_fedex_password);
        Setting::setConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_account', $this->shipping_fedex_account);
        Setting::setConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_meter', $this->shipping_fedex_meter);
        Setting::setConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_dropoff_type', $this->shipping_fedex_dropoff_type);
        Setting::setConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_fedpack_type', $this->shipping_fedex_fedpack_type);

        Setting::setConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_country_code', $this->shipping_fedex_country_code);
        Setting::setConfig($this->restaurant_uuid, "Fedex", 'shipping_fedex_postcode', $this->shipping_fedex_postcode);

        return true;
    }
}

