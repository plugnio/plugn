<?php namespace common\tests;

use common\fixtures\RefundFixture;
use Codeception\Specify;
use common\models\Refund;

class RefundTest extends \Codeception\Test\Unit
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
            'refunds' => RefundFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                Refund::find()->one()
            )->notNull();
        });

        $this->specify('Refund model fields validation', function () {
            $model = new Refund();

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty order_uuid', $model->validate(['order_uuid']))->false();
            expect('should not accept empty refund_amount', $model->validate(['refund_amount']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->order_uuid = 12312312313;
            expect('should not accept invalid order_uuid', $model->validate(['order_uuid']))->false();
        });
    }
}