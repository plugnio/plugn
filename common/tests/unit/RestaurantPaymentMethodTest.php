<?php namespace common\tests;

use common\fixtures\RestaurantPaymentMethodFixture;
use common\models\RestaurantPaymentMethod;

class RestaurantPaymentMethodTest extends \Codeception\Test\Unit
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
            'methods' => RestaurantPaymentMethodFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(RestaurantPaymentMethod::find()->one(), 'Check data loaded');

        $model = new RestaurantPaymentMethod();
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
        $this->assertFalse($model->validate(['payment_method_id']), 'should not accept empty payment_method_id');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');

        $model->payment_method_id = 12312312313;
        $this->assertFalse($model->validate(['payment_method_id']), 'should not accept invalid payment_method_id');
    }
}