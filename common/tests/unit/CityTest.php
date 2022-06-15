<?php namespace common\tests;

use common\fixtures\CityFixture;
use Codeception\Specify;
use common\models\City;

class CityTest extends \Codeception\Test\Unit
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
            'cities' => CityFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                City::find()->one()
            )->notNull();
        });

        $this->specify('City model fields validation', function () {
            $model = new City();

            expect('should not accept empty country_id', $model->validate(['country_id']))->false();
            expect('should not accept empty city_name', $model->validate(['city_name']))->false();

            $model->country_id = 12312312313;
            expect('should not accept invalid country_id', $model->validate(['country_id']))->false();
        });
    }
}