<?php namespace common\tests;

use common\fixtures\RestaurantThemeFixture;
use common\models\RestaurantTheme;
use Codeception\Specify;

class RestaurantThemeTest extends \Codeception\Test\Unit
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
            'themes' => RestaurantThemeFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check restaurant theme loaded',
                RestaurantTheme::find()->one()
            )->notNull();
        });

        $this->specify('RestaurantTheme model fields validation', function () {
            $model = new RestaurantTheme;

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
        });
    }
}