<?php namespace common\tests;

use common\fixtures\CurrencyFixture;

class CurrencyTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => CurrencyFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                Currency::find()->one()
            )->notNull();
        });

        $this->specify('Currency model fields validation', function () {
            $model = new Currency;

            expect('should not accept empty title', $model->validate(['title']))->false();
            expect('should not accept empty code', $model->validate(['code']))->false();
            expect('should not accept empty currency_symbol', $model->validate(['currency_symbol']))->false();
            expect('should not accept empty rate', $model->validate(['rate']))->false();

            $model->city_id = 12312312313;
            expect('should not accept invalid city_id', $model->validate(['city_id']))->false();
        });
    }
}