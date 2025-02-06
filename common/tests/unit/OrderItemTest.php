<?php namespace common\tests;

use common\fixtures\OrderItemFixture;
use common\models\OrderItem;

class OrderItemTest extends \Codeception\Test\Unit
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
            'orderItems' => OrderItemFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {       
        $this->assertNotNull(OrderItem::find()->one(), 'Check data loaded');

        $model = new OrderItem();
        $this->assertFalse($model->validate(['order_uuid']), 'should not accept empty order_uuid');
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
        $this->assertFalse($model->validate(['qty']), 'should not accept empty qty');

        $model->item_uuid = 12312312313;
        $this->assertFalse($model->validate(['item_uuid']), 'should not accept invalid item_uuid');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');

        $model->order_uuid = 12312312313;
        $this->assertFalse($model->validate(['order_uuid']), 'should not accept invalid order_uuid');
    }
}