<?php namespace common\tests;

use common\fixtures\PaymentFixture;
use Codeception\Specify;
use common\models\Payment;

class PaymentTest extends \Codeception\Test\Unit
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
            'payments' => PaymentFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Payment::find()->one(), 'Check data loaded');

        //$this->specify('Payment model fields validation', function () {
            $model = new Payment();

            $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
            $this->assertFalse($model->validate(['customer_id']), 'should not accept empty customer_id');
            $this->assertFalse($model->validate(['order_uuid']), 'should not accept empty order_uuid');
            $this->assertFalse($model->validate(['payment_mode']), 'should not accept empty payment_mode');
            $this->assertFalse($model->validate(['payment_amount_charged']), 'should not accept empty payment_amount_charged');

            $model->restaurant_uuid = 12312312313;
            $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');

            $model->customer_id = 12312312313;
            $this->assertFalse($model->validate(['customer_id']), 'should not accept invalid customer_id');

        //});
    }   
}