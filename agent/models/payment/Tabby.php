<?php

namespace agent\models\payment;

use common\models\Setting;
use yii\base\Model;

class Tabby extends Model
{
    public $restaurant_uuid;
    public $payment_tabby_public_key;
    public $payment_tabby_secret_key;
    
    public function init()
    {
        parent::init();

        if($this->restaurant_uuid) {
            $this->payment_tabby_public_key = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_public_key');
            $this->payment_tabby_secret_key = Setting::getConfig($this->restaurant_uuid, "Tabby", 'payment_tabby_secret_key');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_tabby_public_key', "payment_tabby_secret_key"], 'required']
        ];
    }

    /**
     * @return bool
     */
    public function save() {

        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_public_key', $this->payment_tabby_public_key);
        Setting::setConfig($this->restaurant_uuid, 'Tabby', 'payment_tabby_secret_key', $this->payment_tabby_secret_key);

        return true;
    }
}