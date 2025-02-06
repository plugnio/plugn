<?php namespace common\tests;

use common\fixtures\DeliveryZoneFixture;
use common\models\DeliveryZone;

class DeliveryZoneTest extends \Codeception\Test\Unit
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
            'zones' => DeliveryZoneFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(DeliveryZone::find()->one(), 'Check data loaded');

        $model = new DeliveryZone();
        $this->assertFalse($model->validate(['country_id']), 'should not accept empty country_id');
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
        $this->assertFalse($model->validate(['business_location_id']), 'should not accept empty business_location_id');

        $model->restaurant_uuid = 99999999999;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');

        $model->business_location_id = 999999999999999;
        $this->assertFalse($model->validate(['business_location_id']), 'should not accept invalid business_location_id');

        $model->country_id = 99999999999;
        $this->assertFalse($model->validate(['country_id']), 'should not accept invalid country_id');
    }
}