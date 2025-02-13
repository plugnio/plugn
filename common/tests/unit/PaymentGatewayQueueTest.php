<?php namespace common\tests;

use common\fixtures\PaymentGatewayQueueFixture;
use common\models\PaymentGatewayQueue;
use Codeception\Specify;

class PaymentGatewayQueueTest extends \Codeception\Test\Unit
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
            'locations' => PaymentGatewayQueueFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
       // $this->specify('Fixtures should be loaded', function() {
            $this->assertNotNull(PaymentGatewayQueue::find()->one(), 'Check data loaded');
        //});

        //$this->specify('PaymentGatewayQueue model fields validation', function () {
            $model = new PaymentGatewayQueue;

            $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
            $this->assertFalse($model->validate(['payment_gateway']), 'should not accept empty payment_gateway');

            $model->restaurant_uuid = 12312312313;
            $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');
       // });
    }
}