<?php namespace common\tests;

use common\fixtures\OrderItemFixture;
use Codeception\Specify;
use common\models\OrderItem;

class OrderItemTest extends \Codeception\Test\Unit
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
            'orderItems' => OrderItemFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                OrderItem::find()->one()
            )->notNull();
        });

        $this->specify('OrderItem model fields validation', function () {

            $model = new OrderItem();

            expect('should not accept empty order_uuid', $model->validate(['order_uuid']))->false();
            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            //expect('should not accept empty item_uuid', $model->validate(['item_uuid']))->false();
            //expect('should not accept empty item_name', $model->validate(['item_name']))->false();
            //expect('should not accept empty item_price', $model->validate(['item_price']))->false();
            expect('should not accept empty qty', $model->validate(['qty']))->false();

            $model->item_uuid = 12312312313;
            expect('should not accept invalid item_uuid', $model->validate(['item_uuid']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->order_uuid = 12312312313;
            expect('should not accept invalid order_uuid', $model->validate(['order_uuid']))->false();
        });
    }
}