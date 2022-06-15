<?php namespace common\tests;

use common\fixtures\VoucherFixture;
use common\models\Voucher;
use Codeception\Specify;

class VoucherTest extends \Codeception\Test\Unit
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
            'vouchers' => VoucherFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check Voucher loaded',
                Voucher::find()->one()
            )->notNull();
        });

        $this->specify('Voucher model fields validation', function () {
            $model = new Voucher;

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty code', $model->validate(['code']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
        });
    }
}