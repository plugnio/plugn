<?php namespace common\tests;

use common\fixtures\RestaurantCurrencyFixture;
use common\models\RestaurantCurrency;

class RestaurantCurrencyTest extends \Codeception\Test\Unit
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
            'locations' => RestaurantCurrencyFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(RestaurantCurrency::find()->one(), 'Check currency loaded');

        $model = new RestaurantCurrency();
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
        $this->assertFalse($model->validate(['currency_id']), 'should not accept empty currency_id');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');

        $model->currency_id = 12312312313;
        $this->assertFalse($model->validate(['currency_id']), 'should not accept invalid currency_id');
    }
}