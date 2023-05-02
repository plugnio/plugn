<?php

namespace agent\models\payment;

use common\models\Setting;
use Yii;
use yii\base\Model;


/**
 * Login form
 */
class Moyasar extends Model
{
    public $restaurant_uuid;
    public $payment_moyasar_api_secret_key;
    public $payment_moyasar_api_key;
    public $payment_moyasar_payment_type;
    public $payment_moyasar_network_type;
    public $payment_moyasar_apple_domain_association;

    public function init()
    {
        parent::init();

        if($this->restaurant_uuid) {
            $this->payment_moyasar_api_secret_key = Setting::getConfig($this->restaurant_uuid, "Moyasar", 'payment_moyasar_api_key');

            $this->payment_moyasar_api_key = Setting::getConfig($this->restaurant_uuid, "Moyasar", 'payment_moyasar_api_key');

            $this->payment_moyasar_payment_type = Setting::getConfig($this->restaurant_uuid, "Moyasar", 'payment_moyasar_payment_type');

            $this->payment_moyasar_network_type = Setting::getConfig($this->restaurant_uuid, "Moyasar", 'payment_moyasar_network_type');

            $this->payment_moyasar_apple_domain_association = Setting::getConfig($this->restaurant_uuid, "Moyasar", 'payment_moyasar_apple_domain_association');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['restaurant_uuid', 'payment_moyasar_api_secret_key', 'payment_moyasar_api_key'], 'required'],
            [['payment_moyasar_payment_type', 'payment_moyasar_network_type', 'payment_moyasar_apple_domain_association'], 'string']
        ];
    }

    public function save() {
        Setting::setConfig($this->restaurant_uuid, 'Moyasar', 'payment_moyasar_api_secret_key', $this->payment_moyasar_api_secret_key);
        Setting::setConfig($this->restaurant_uuid, 'Moyasar', 'payment_moyasar_api_key', $this->payment_moyasar_api_key);
        Setting::setConfig($this->restaurant_uuid, 'Moyasar', 'payment_moyasar_payment_type', $this->payment_moyasar_payment_type);
        Setting::setConfig($this->restaurant_uuid, 'Moyasar', 'payment_moyasar_network_type', $this->payment_moyasar_network_type);
        Setting::setConfig($this->restaurant_uuid, 'Moyasar', 'payment_moyasar_apple_domain_association', $this->payment_moyasar_apple_domain_association);

        return true;
    }
}
