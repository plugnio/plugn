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
            'items' => RefundedItemFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                RefundedItem::find()->one()
            )->notNull();
        });

        $this->specify('RefundedItem model fields validation', function () {
            $model = new RefundedItem();

            expect('should not accept empty refund_id', $model->validate(['refund_id']))->false();
            expect('should not accept empty order_item_id', $model->validate(['order_item_id']))->false();
            expect('should not accept empty item_uuid', $model->validate(['item_uuid']))->false();
            expect('should not accept empty order_uuid', $model->validate(['order_uuid']))->false();
            expect('should not accept empty item_name', $model->validate(['item_name']))->false();
            expect('should not accept empty item_price', $model->validate(['item_price']))->false();
            expect('should not accept empty qty', $model->validate(['qty']))->false();

            $model->item_uuid = 12312312313;
            expect('should not accept invalid item_uuid', $model->validate(['item_uuid']))->false();

            $model->order_uuid = 12312312313;
            expect('should not accept invalid order_uuid', $model->validate(['order_uuid']))->false();

            $model->refund_id = 12312312313;
            expect('should not accept invalid refund_id', $model->validate(['refund_id']))->false();

            $model->order_item_id = 12312312313;
            expect('should not accept invalid order_item_id', $model->validate(['order_item_id']))->false();
        });
    }
}