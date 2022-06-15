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
            'locations' => PaymentGatewayQueueFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                PaymentGatewayQueue::find()->one()
            )->notNull();
        });

        $this->specify('PaymentGatewayQueue model fields validation', function () {
            $model = new PaymentGatewayQueue;

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty payment_gateway', $model->validate(['payment_gateway']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
        });
    }
}