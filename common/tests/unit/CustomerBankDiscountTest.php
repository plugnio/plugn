<?php namespace common\tests;

use common\fixtures\CustomerBankDiscountFixture;
use common\models\CustomerBankDiscount;
use Codeception\Specify;

class CustomerBankDiscountTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => CustomerBankDiscountFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                CustomerBankDiscount::find()->one()
            )->notNull();
        });

        $this->specify('CustomerBankDiscount model fields validation', function () {
            $model = new CustomerBankDiscount();

            expect('should not accept empty customer_id', $model->validate(['customer_id']))->false();
            expect('should not accept empty bank_discount_id', $model->validate(['bank_discount_id']))->false();

            $model->customer_id = 12312312313;
            expect('should not accept invalid customer_id', $model->validate(['customer_id']))->false();

            $model->bank_discount_id = 12312312313;
            expect('should not accept invalid bank_discount_id', $model->validate(['bank_discount_id']))->false();

        });
    }
}