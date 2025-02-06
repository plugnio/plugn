<?php namespace common\tests;

use common\fixtures\CustomerBankDiscountFixture;
use common\models\CustomerBankDiscount;

class CustomerBankDiscountTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => CustomerBankDiscountFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(CustomerBankDiscount::find()->one(), 'Check data loaded');

        $model = new CustomerBankDiscount();
        $this->assertFalse($model->validate(['customer_id']), 'should not accept empty customer_id');
        $this->assertFalse($model->validate(['bank_discount_id']), 'should not accept empty bank_discount_id');

        $model->customer_id = 12312312313;
        $this->assertFalse($model->validate(['customer_id']), 'should not accept invalid customer_id');

        $model->bank_discount_id = 12312312313;
        $this->assertFalse($model->validate(['bank_discount_id']), 'should not accept invalid bank_discount_id');
    }
}