<?php

namespace backend\models\payment;

use common\models\Setting;
use Yii;
use yii\base\Model;


/**
 * Login form
 */
class Moyasar extends Model
{
    public $payment_moyasar_api_secret_key;
    public $payment_moyasar_api_key;
    public $payment_moyasar_payment_type;
    public $payment_moyasar_network_type;

    public function init()
    {
        parent::init();

        $this->payment_moyasar_api_secret_key = Setting::getConfig(null, "Moyasar", 'payment_moyasar_api_secret_key');

        $this->payment_moyasar_api_key = Setting::getConfig(null, "Moyasar", 'payment_moyasar_api_key');

        $this->payment_moyasar_payment_type = Setting::getConfig(null, "Moyasar", 'payment_moyasar_payment_type');

        $this->payment_moyasar_network_type = Setting::getConfig(null, "Moyasar", 'payment_moyasar_network_type');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['payment_moyasar_api_secret_key', 'payment_moyasar_api_key', 'payment_moyasar_payment_type', 'payment_moyasar_network_type'], 'required']
        ];
    }

    public function save() {
        Setting::setConfig(null, 'Moyasar', 'payment_moyasar_api_secret_key', $this->payment_moyasar_api_secret_key);
        Setting::setConfig(null, 'Moyasar', 'payment_moyasar_api_key', $this->payment_moyasar_api_key);
        Setting::setConfig(null, 'Moyasar', 'payment_moyasar_payment_type', $this->payment_moyasar_payment_type);
        Setting::setConfig(null, 'Moyasar', 'payment_moyasar_network_type', $this->payment_moyasar_network_type);

        return true;
    }
}
