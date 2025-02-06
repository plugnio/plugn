<?php namespace common\tests;

use common\fixtures\CountryPaymentMethodFixture;
use common\models\CountryPaymentMethod;

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
            'cpms' => CountryPaymentMethodFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(CountryPaymentMethod::find()->one(), 'Check data loaded');

        $model = new CountryPaymentMethod;
        $this->assertFalse($model->validate(['payment_method_id']), 'should not accept empty payment_method_id');
        $this->assertFalse($model->validate(['country_id']), 'should not accept empty country_id');

        $model->payment_method_id = 12312312313;
        $this->assertFalse($model->validate(['payment_method_id']), 'should not accept invalid payment_method_id');

        $model->country_id = 12312312313;
        $this->assertFalse($model->validate(['country_id']), 'should not accept invalid country_id');
    }
}