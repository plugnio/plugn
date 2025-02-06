<?php namespace common\tests;

use common\fixtures\OrderItemExtraOptionFixture;
use common\models\OrderItemExtraOption;

class OrderItemExtraOptionTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => OrderItemExtraOptionFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(OrderItemExtraOption::find()->one(), 'Check data loaded');

        $model = new OrderItemExtraOption();
        $this->assertFalse($model->validate(['order_item_id']), 'should not accept empty order_item_id');

        $model->order_item_id = 12312312313;
        $this->assertFalse($model->validate(['order_item_id']), 'should not accept invalid order_item_id');

        $model->extra_option_id = 12312312313;
        $this->assertFalse($model->validate(['extra_option_id']), 'should not accept invalid extra_option_id');
    }
}