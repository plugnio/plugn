<?php namespace common\tests;

use common\fixtures\RestaurantDeliveryFixture;
use common\models\RestaurantDelivery;

class RestaurantDeliveryTest extends \Codeception\Test\Unit
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
            'deliveries' => RestaurantDeliveryFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(RestaurantDelivery::find()->one(), 'Check bank discount loaded');

        $model = new RestaurantDelivery();
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
        $this->assertFalse($model->validate(['area_id']), 'should not accept empty area_id');

        $model->area_id = 12312312313;
        $this->assertFalse($model->validate(['area_id']), 'should not accept invalid area_id');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');
    }
}