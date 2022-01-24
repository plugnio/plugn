<?php namespace common\tests;

use common\fixtures\DeliveryZoneFixture;
use Codeception\Specify;
use common\models\DeliveryZone;

class DeliveryZoneTest extends \Codeception\Test\Unit
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
            'zones' => DeliveryZoneFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                DeliveryZone::find()->one()
            )->notNull();
        });

        $this->specify('DeliveryZone model fields validation', function () {
            $model = new DeliveryZone();

            expect('should not accept empty country_id', $model->validate(['country_id']))->false();
            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty business_location_id', $model->validate(['business_location_id']))->false();

            $model->restaurant_uuid = 99999999999;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->business_location_id  = 999999999999999;
            expect('should not accept invalid business_location_id ', $model->validate(['business_location_id']))->false();

            $model->country_id = 99999999999;
            expect('should not accept invalid country_id', $model->validate(['country_id']))->false();
        });
    }
}