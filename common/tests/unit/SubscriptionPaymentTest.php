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
            'payments' => SubscriptionPaymentFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check Subscription Payment loaded',
                SubscriptionPayment::find()->one()
            )->notNull();
        });

        $this->specify('SubscriptionPayment model fields validation', function () {
            $model = new SubscriptionPayment();

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty subscription_uuid', $model->validate(['subscription_uuid']))->false();
            expect('should not accept empty payment_mode', $model->validate(['payment_mode']))->false();
            expect('should not accept empty payment_amount_charged', $model->validate(['payment_amount_charged']))->false();

            $model->subscription_uuid = 12312312313;
            expect('should not accept invalid subscription_uuid', $model->validate(['subscription_uuid']))->false();

            $model->city_id = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
        });
    }
}