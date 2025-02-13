<?php namespace common\tests;

use common\fixtures\RefundedItemFixture;
use Codeception\Specify;
use common\models\RefundedItem;

class RefundedItemTest extends \Codeception\Test\Unit
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
            'items' => RefundedItemFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(RefundedItem::find()->one(), 'Check data loaded');

        //$this->specify('RefundedItem model fields validation', function () {
            $model = new RefundedItem();

            $this->assertFalse($model->validate(['refund_id']), 'should not accept empty refund_id');
            $this->assertFalse($model->validate(['order_item_id']), 'should not accept empty order_item_id');
            $this->assertFalse($model->validate(['item_uuid']), 'should not accept empty item_uuid');
            $this->assertFalse($model->validate(['order_uuid']), 'should not accept empty order_uuid');
            $this->assertFalse($model->validate(['item_name']), 'should not accept empty item_name');
            $this->assertFalse($model->validate(['item_price']), 'should not accept empty item_price');
            $this->assertFalse($model->validate(['qty']), 'should not accept empty qty');

            $model->item_uuid = 12312312313;
            $this->assertFalse($model->validate(['item_uuid']), 'should not accept invalid item_uuid');

            $model->order_uuid = 12312312313;
            $this->assertFalse($model->validate(['order_uuid']), 'should not accept invalid order_uuid');

            $model->refund_id = 12312312313;
            $this->assertFalse($model->validate(['refund_id']), 'should not accept invalid refund_id');

            $model->order_item_id = 12312312313;
            $this->assertFalse($model->validate(['order_item_id']), 'should not accept invalid order_item_id');
      //  });
    }
}