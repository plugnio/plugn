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
            'payments' => PaymentFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                Payment::find()->one()
            )->notNull();
        });

        $this->specify('Payment model fields validation', function () {
            $model = new Payment();

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty customer_id', $model->validate(['customer_id']))->false();
            expect('should not accept empty order_uuid', $model->validate(['order_uuid']))->false();
            expect('should not accept empty payment_mode', $model->validate(['payment_mode']))->false();
            expect('should not accept empty payment_amount_charged', $model->validate(['payment_amount_charged']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->customer_id = 12312312313;
            expect('should not accept invalid customer_id', $model->validate(['customer_id']))->false();

        });
    }
}