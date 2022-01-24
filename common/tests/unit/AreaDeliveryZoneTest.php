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
            'areaDeliveryZones' => AreaDeliveryZoneFixture::className(),
            'restaurants' => RestaurantFixture::className(),
            'deliveryZones' => DeliveryZoneFixture::className(),
            'countries' => CountryFixture::className(),
            'cities' => CityFixture::className(),
            'areas' => AreaFixture::className(),
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check area delivery zone loaded',
                AreaDeliveryZone::find()->one()
            )->notNull();
        });

        $this->specify('AreaDeliveryZone model fields validation', function () {
            $area = new AreaDeliveryZone;

            expect('should not accept empty restaurant_uuid', $area->validate(['restaurant_uuid']))->false();
            expect('should not accept empty delivery_zone_id', $area->validate(['delivery_zone_id']))->false();

            $area->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $area->validate(['restaurant_uuid']))->false();

            $area->delivery_zone_id = 12312312313;
            expect('should not accept invalid delivery_zone_id', $area->validate(['delivery_zone_id']))->false();

            $area->country_id = 12312312313;
            expect('should not accept invalid country_id', $area->validate(['country_id']))->false();

            $area->city_id = 12312312313;
            expect('should not accept invalid city_id', $area->validate(['city_id']))->false();

            $area->area_id = 12312312313;
            expect('should not accept invalid area_id', $area->validate(['area_id']))->false();

            $area->country_id = 1;
            expect('should accept valid country_id', $area->validate(['country_id']))->true();

            $area->city_id = 1;
            expect('should accept valid city_id', $area->validate(['city_id']))->true();

            $area->area_id = 1;
            expect('should accept valid area_id', $area->validate(['area_id']))->true();
        });
    }
}