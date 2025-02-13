<?php namespace common\tests;

use common\fixtures\AreaDeliveryZoneFixture;
use common\fixtures\AreaFixture;
use common\fixtures\CityFixture;
use common\fixtures\CountryFixture;
use common\fixtures\DeliveryZoneFixture;
use common\fixtures\RestaurantFixture;
use common\models\AreaDeliveryZone;

class AreaDeliveryZoneTest extends \Codeception\Test\Unit
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
            'areaDeliveryZones' => AreaDeliveryZoneFixture::class,
            'restaurants' => RestaurantFixture::class,
            'deliveryZones' => DeliveryZoneFixture::class,
            'countries' => CountryFixture::class,
            'cities' => CityFixture::class,
            'areas' => AreaFixture::class,
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(AreaDeliveryZone::find()->one(), 'Check area delivery zone loaded');

        $area = new AreaDeliveryZone();
        $this->assertFalse($area->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
        $this->assertFalse($area->validate(['delivery_zone_id']), 'should not accept empty delivery_zone_id');

        $area->restaurant_uuid = 12312312313;
        $this->assertFalse($area->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');

        $area->delivery_zone_id = 12312312313;
        $this->assertFalse($area->validate(['delivery_zone_id']), 'should not accept invalid delivery_zone_id');

        $area->country_id = 12312312313;
        $this->assertFalse($area->validate(['country_id']), 'should not accept invalid country_id');

        $area->city_id = 12312312313;
        $this->assertFalse($area->validate(['city_id']), 'should not accept invalid city_id');

        $area->area_id = 12312312313;
        $this->assertFalse($area->validate(['area_id']), 'should not accept invalid area_id');

        $area->country_id = 1;
        $this->assertTrue($area->validate(['country_id']), 'should accept valid country_id');

        $area->city_id = 1;
        $this->assertTrue($area->validate(['city_id']), 'should accept valid city_id');

        $area->area_id = 1;
        $this->assertTrue($area->validate(['area_id']), 'should accept valid area_id');
    }
}