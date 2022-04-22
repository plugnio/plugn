<?php namespace common\tests;

use common\fixtures\RestaurantCurrencyFixture;
use common\models\RestaurantCurrency;
use Codeception\Specify;

class RestaurantCurrencyTest extends \Codeception\Test\Unit
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
            'locations' => RestaurantCurrencyFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check currency loaded',
                RestaurantCurrency::find()->one()
            )->notNull();
        });

        $this->specify('RestaurantCurrency model fields validation', function () {
            $model = new RestaurantCurrency;

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty currency_id', $model->validate(['currency_id']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->currency_id = 12312312313;
            expect('should not accept invalid currency_id', $model->validate(['currency_id']))->false();

        });
    }
}