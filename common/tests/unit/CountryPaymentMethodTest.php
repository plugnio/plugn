<?php namespace common\tests;

use common\fixtures\CountryPaymentMethodFixture;

class CountryPaymentMethodTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => CountryPaymentMethodFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                CountryPaymentMethod::find()->one()
            )->notNull();
        });

        $this->specify('CountryPaymentMethod model fields validation', function () {
            $model = new CountryPaymentMethod;

            expect('should not accept empty payment_method_id', $model->validate(['payment_method_id']))->false();
            expect('should not accept empty country_id', $model->validate(['country_id']))->false();

            $model->payment_method_id = 12312312313;
            expect('should not accept invalid payment_method_id', $model->validate(['payment_method_id']))->false();

            $model->country_id = 12312312313;
            expect('should not accept invalid country_id', $model->validate(['country_id']))->false();
        });
    }
}