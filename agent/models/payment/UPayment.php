<?php

namespace agent\models\payment;

use common\models\Setting;
use Yii;
use yii\base\Model;


/**
 * UPayment form
 */
class UPayment extends Model
{
    public $restaurant_uuid;
    public $payment_upayment_api_key;

    public function init()
    {
        parent::init();

        if($this->restaurant_uuid) {
            $this->payment_upayment_api_key = Setting::getConfig($this->restaurant_uuid, "UPayment", 'payment_upayment_api_key');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_upayment_api_key'], 'required']
        ];
    }

    /**
     * @return bool
     */
    public function save() {

        Setting::setConfig($this->restaurant_uuid, 'UPayment', 'payment_upayment_api_key', $this->payment_upayment_api_key);

        return true;
    }
}

