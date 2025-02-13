<?php namespace common\tests;

use common\fixtures\BankDiscountFixture;
use common\models\BankDiscount;

class BankDiscountTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => BankDiscountFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(BankDiscount::find()->one(), 'Check bank discount loaded');

        $model = new BankDiscount();
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
        $this->assertFalse($model->validate(['discount_type']), 'should not accept empty discount_type');
        $this->assertFalse($model->validate(['discount_amount']), 'should not accept empty discount_amount');

        $model->bank_id = 12312312313;
        $this->assertFalse($model->validate(['bank_id']), 'should not accept invalid bank_id');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');
    }
}