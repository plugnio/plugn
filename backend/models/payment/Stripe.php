<?php

namespace backend\models\payment;

use common\models\Setting;
use Yii;
use yii\base\Model;


/**
 * Stripe form
 */
class Stripe extends Model
{
    public $payment_stripe_secret_key;
    public $payment_stripe_publishable_key;

    public function init()
    {
        parent::init();

        $this->payment_stripe_secret_key = Setting::getConfig(null, "Stripe", 'payment_stripe_secret_key');

        $this->payment_stripe_publishable_key = Setting::getConfig(null, "Stripe", 'payment_stripe_publishable_key');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_stripe_secret_key', 'payment_stripe_publishable_key'], 'required']
        ];
    }

    public function save() {
        Setting::setConfig(null, 'Stripe', 'payment_stripe_secret_key', $this->payment_stripe_secret_key);
        Setting::setConfig(null, 'Stripe', 'payment_stripe_publishable_key', $this->payment_stripe_publishable_key);

        return true;
    }
}
