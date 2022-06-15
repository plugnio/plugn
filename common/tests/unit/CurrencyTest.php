<?php namespace common\tests;

use common\fixtures\CurrencyFixture;
use Codeception\Specify;
use common\models\Currency;

class CurrencyTest extends \Codeception\Test\Unit
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
            'currencies' => CurrencyFixture::className()];
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
            //expect('should not accept empty currency_symbol', $model->validate(['currency_symbol']))->false();
            expect('should not accept empty rate', $model->validate(['rate']))->false();
        });
    }
}