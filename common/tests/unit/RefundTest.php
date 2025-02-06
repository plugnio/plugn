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
            'refunds' => RefundFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Refund::find()->one(), 'Check data loaded');

      //  $this->specify('Refund model fields validation', function () {
            $model = new Refund();

            $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
            $this->assertFalse($model->validate(['order_uuid']), 'should not accept empty order_uuid');
            $this->assertFalse($model->validate(['refund_amount']), 'should not accept empty refund_amount');

            $model->restaurant_uuid = 12312312313;
            $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');

            $model->order_uuid = 12312312313;
            $this->assertFalse($model->validate(['order_uuid']), 'should not accept invalid order_uuid');
       // });
    }
}