<?php namespace common\tests;

use common\fixtures\RestaurantDeliveryFixture;
use Codeception\Specify;
use common\models\RestaurantDelivery;

class RestaurantDeliveryTest extends \Codeception\Test\Unit
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
            'deliveries' => RestaurantDeliveryFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check bank discount loaded',
                RestaurantDelivery::find()->one()
            )->notNull();
        });

        $this->specify('RestaurantDelivery model fields validation', function () {
            $model = new RestaurantDelivery;

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty area_id', $model->validate(['area_id']))->false();

            $model->area_id = 12312312313;
            expect('should not accept invalid area_id', $model->validate(['area_id']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
        });
    }
}