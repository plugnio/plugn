<?php namespace common\tests;

use common\fixtures\PaymentMethodFixture;
use Codeception\Specify;
use common\models\PaymentMethod;

class PaymentMethodTest extends \Codeception\Test\Unit
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
            'methods' => PaymentMethodFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(PaymentMethod::find()->one(), 'Check data loaded');

        //$this->specify('PaymentMethod model fields validation', function () {
            $model = new PaymentMethod;

            $this->assertFalse($model->validate(['payment_method_name']), 'should not accept empty payment_method_name');
        //});
    }
}