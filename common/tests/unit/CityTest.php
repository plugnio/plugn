<?php namespace common\tests;

use common\fixtures\CityFixture;
use common\models\City;

class CityTest extends \Codeception\Test\Unit
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
            'cities' => CityFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(City::find()->one(), 'Check data loaded');

        $model = new City();
        $this->assertFalse($model->validate(['country_id']), 'should not accept empty country_id');
        $this->assertFalse($model->validate(['city_name']), 'should not accept empty city_name');

        $model->country_id = 12312312313;
        $this->assertFalse($model->validate(['country_id']), 'should not accept invalid country_id');
    }
}