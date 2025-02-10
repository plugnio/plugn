<?php namespace common\tests;

use common\fixtures\CustomerVoucherFixture;
use common\models\CustomerVoucher;
class CustomerVoucherTest extends \Codeception\Test\Unit
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
            'customerVouchers' => CustomerVoucherFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(CustomerVoucher::find()->one(), 'Check data loaded');

        $model = new CustomerVoucher();
        $this->assertFalse($model->validate(['customer_id']), 'should not accept empty customer_id');
        $this->assertFalse($model->validate(['voucher_id']), 'should not accept empty voucher_id');

        $model->customer_id = 12312312313;
        $this->assertFalse($model->validate(['customer_id']), 'should not accept invalid customer_id');

        $model->voucher_id = 12312312313;
        $this->assertFalse($model->validate(['voucher_id']), 'should not accept invalid voucher_id');
    }
}