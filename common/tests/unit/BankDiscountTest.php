<?php namespace common\tests;

use common\fixtures\BankDiscountFixture;
use Codeception\Specify;
use common\models\BankDiscount;

class BankDiscountTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => BankDiscountFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check bank discount loaded',
                BankDiscount::find()->one()
            )->notNull();
        });

        $this->specify('BankDiscount model fields validation', function () {
            $model = new BankDiscount();

           // expect('should not accept empty bank_id', $model->validate(['bank_id']))->false();
            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty discount_type', $model->validate(['discount_type']))->false();
            expect('should not accept empty discount_amount', $model->validate(['discount_amount']))->false();

            $model->bank_id = 12312312313;
            expect('should not accept invalid bank_id', $model->validate(['bank_id']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
        });
    }
}