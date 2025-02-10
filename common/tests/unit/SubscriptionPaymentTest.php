<?php namespace common\tests;

use common\fixtures\SubscriptionPaymentFixture;
use common\models\SubscriptionPayment;

class SubscriptionPaymentTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function _fixtures(){
        return [
            'payments' => SubscriptionPaymentFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(SubscriptionPayment::find()->one(), 'Check Subscription Payment loaded');

        $model = new SubscriptionPayment();
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
        $this->assertFalse($model->validate(['subscription_uuid']), 'should not accept empty subscription_uuid');
        $this->assertFalse($model->validate(['payment_mode']), 'should not accept empty payment_mode');
        $this->assertFalse($model->validate(['payment_amount_charged']), 'should not accept empty payment_amount_charged');

        $model->subscription_uuid = 12312312313;
        $this->assertFalse($model->validate(['subscription_uuid']), 'should not accept invalid subscription_uuid');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');
    }
}