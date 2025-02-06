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
            'subscription' => SubscriptionFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Subscription::find()->one(), 'Check data loaded');

        $model = new Subscription();
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');

        $model->payment_method_id = 12312312313;
        $this->assertFalse($model->validate(['payment_method_id']), 'should not accept invalid payment_method_id');

        $model->payment_uuid = 12312312313;
        $this->assertFalse($model->validate(['payment_uuid']), 'should not accept invalid payment_uuid');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');

        $model->plan_id = 12312312313;
        $this->assertFalse($model->validate(['plan_id']), 'should not accept invalid plan_id');
    }
}