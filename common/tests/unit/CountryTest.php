<?php namespace common\tests;

use common\fixtures\CountryFixture;
use Codeception\Specify;
use common\models\Country;

class CountryTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => CountryFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                Country::find()->one()
            )->notNull();
        });

        $this->specify('Country model fields validation', function () {
            $model = new Country;

            expect('should not accept empty country_name', $model->validate(['country_name']))->false();
        });
    }
}