<?php namespace common\tests;

use common\fixtures\CurrencyFixture;
use common\models\Currency;

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
            'currencies' => CurrencyFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Currency::find()->one(), 'Check data loaded');

        $model = new Currency();
        $this->assertFalse($model->validate(['title']), 'should not accept empty title');
        $this->assertFalse($model->validate(['code']), 'should not accept empty code');
        $this->assertFalse($model->validate(['rate']), 'should not accept empty rate');
    }
}