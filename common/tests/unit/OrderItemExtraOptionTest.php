<?php namespace common\tests;

use common\fixtures\OrderItemExtraOptionFixture;
use Codeception\Specify;
use common\models\OrderItemExtraOption;

class OrderItemExtraOptionTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => OrderItemExtraOptionFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                OrderItemExtraOption::find()->one()
            )->notNull();
        });

        $this->specify('OrderItemExtraOption model fields validation', function () {
            $model = new OrderItemExtraOption();

            expect('should not accept empty order_item_id', $model->validate(['order_item_id']))->false();
            //expect('should not accept empty extra_option_id', $model->validate(['extra_option_id']))->false();
            //expect('should not accept empty extra_option_name', $model->validate(['extra_option_name']))->false();
            //expect('should not accept empty extra_option_price', $model->validate(['extra_option_price']))->false();

            $model->order_item_id = 12312312313;
            expect('should not accept invalid order_item_id', $model->validate(['order_item_id']))->false();

            $model->extra_option_id = 12312312313;
            expect('should not accept invalid extra_option_id', $model->validate(['extra_option_id']))->false();
        });
    }
}