<?php namespace common\tests;

use common\fixtures\CountryFixture;
use common\models\Country;

class CountryTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => CountryFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Country::find()->one(), 'Check data loaded');

        $model = new Country();
        $this->assertFalse($model->validate(['country_name']), 'should not accept empty country_name');
    }
}