<?php namespace common\tests;

use common\fixtures\SubscriptionFixture;
use common\models\Subscription;

class SubscriptionTest extends \Codeception\Test\Unit
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
            'subscription' => SubscriptionFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                Subscription::find()->one()
            )->notNull();
        });

        $this->specify('Subscription model fields validation', function () {
            $model = new Subscription;

            //expect('should not accept empty payment_method_id', $model->validate(['payment_method_id']))->false();
            //expect('should not accept empty payment_uuid', $model->validate(['payment_uuid']))->false();
            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->payment_method_id = 12312312313;
            expect('should not accept invalid payment_method_id', $model->validate(['payment_method_id']))->false();

            $model->payment_uuid = 12312312313;
            expect('should not accept invalid payment_uuid', $model->validate(['payment_uuid']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->plan_id = 12312312313;
            expect('should not accept invalid plan_id', $model->validate(['plan_id']))->false();

        });
    }
}