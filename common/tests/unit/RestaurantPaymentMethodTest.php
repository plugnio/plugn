<?php namespace common\tests;

use common\fixtures\RestaurantPaymentMethodFixture;
use Codeception\Specify;
use common\models\RestaurantPaymentMethod;

class RestaurantPaymentMethodTest extends \Codeception\Test\Unit
{
    use Specify;
    
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
            'methods' => RestaurantPaymentMethodFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                RestaurantPaymentMethod::find()->one()
            )->notNull();
        });

        $this->specify('RestaurantPaymentMethod model fields validation', function () {
            $model = new RestaurantPaymentMethod;

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty payment_method_id', $model->validate(['payment_method_id']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->payment_method_id = 12312312313;
            expect('should not accept invalid payment_method_id', $model->validate(['payment_method_id']))->false();

        });
    }
}