<?php namespace common\tests;

use common\fixtures\PaymentMethodFixture;

class PaymentMethodTest extends \Codeception\Test\Unit
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
            'methods' => PaymentMethodFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                PaymentMethod::find()->one()
            )->notNull();
        });

        $this->specify('PaymentMethod model fields validation', function () {
            $model = new PaymentMethod;

            expect('should not accept empty payment_method_name', $model->validate(['payment_method_name']))->false();
        });
    }
}