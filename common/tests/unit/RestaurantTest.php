<?php namespace common\tests;

use common\fixtures\RestaurantFixture;
use common\models\Restaurant;

class RestaurantTest extends \Codeception\Test\Unit
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
            'restaurants' => RestaurantFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Restaurant::find()->one(), 'Check Restaurant loaded');

        $model = new Restaurant();
        $this->assertFalse($model->validate(['name']), 'should not accept empty name');
        $this->assertFalse($model->validate(['restaurant_email']), 'should not accept empty restaurant_email');

        $model->restaurant_email = 'randomString';
        $this->assertFalse($model->validate(['restaurant_email']), 'should not accept invalid restaurant_email');

        $model->restaurant_email = 'demo@admin.com';
        $this->assertTrue($model->validate(['restaurant_email']), 'should accept valid restaurant_email');

        $model->country_id = 12312312313;
        $this->assertFalse($model->validate(['country_id']), 'should not accept invalid country_id');

        $model->currency_id = 12312312313;
        $this->assertFalse($model->validate(['currency_id']), 'should not accept invalid currency_id');
    }
}