<?php namespace common\tests;

use common\fixtures\RestaurantFixture;

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
            'restaurants' => RestaurantFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check Restaurant loaded',
                Restaurant::find()->one()
            )->notNull();
        });

        $this->specify('Restaurant model fields validation', function () {
            $model = new Restaurant;

            expect('should not accept empty name', $model->validate(['name']))->false();
            expect('should not accept empty restaurant_email', $model->validate(['restaurant_email']))->false();

            $model->restaurant_email = 'randomString';
            expect('should not accept invalid restaurant_email', $model->validate(['restaurant_email']))->false();

            $model->restaurant_email = 'demo@admin.com';
            expect('should accept valid restaurant_email', $model->validate(['restaurant_email']))->true();

            $model->country_id = 12312312313;
            expect('should not accept invalid country_id', $model->validate(['country_id']))->false();

            $model->currency_id = 12312312313;
            expect('should not accept invalid currency_id', $model->validate(['currency_id']))->false();
        });
    }
}