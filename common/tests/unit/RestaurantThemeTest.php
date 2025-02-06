<?php namespace common\tests;

use common\fixtures\RestaurantThemeFixture;
use common\models\RestaurantTheme;

class RestaurantThemeTest extends \Codeception\Test\Unit
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
            'themes' => RestaurantThemeFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(RestaurantTheme::find()->one(), 'Check restaurant theme loaded');

        $model = new RestaurantTheme();
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');
    }
}