<?php namespace common\tests;

use common\fixtures\CustomerVoucherFixture;
use Codeception\Specify;
use common\models\CustomerVoucher;

class CustomerVoucherTest extends \Codeception\Test\Unit
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
            'customerVouchers' => CustomerVoucherFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                CustomerVoucher::find()->one()
            )->notNull();
        });

        $this->specify('CustomerVoucher model fields validation', function () {
            $model = new CustomerVoucher();

            expect('should not accept empty customer_id', $model->validate(['customer_id']))->false();
            expect('should not accept empty voucher_id', $model->validate(['voucher_id']))->false();

            $model->customer_id = 12312312313;
            expect('should not accept invalid customer_id', $model->validate(['customer_id']))->false();

            $model->voucher_id = 12312312313;
            expect('should not accept invalid voucher_id', $model->validate(['voucher_id']))->false();
        });
    }
}