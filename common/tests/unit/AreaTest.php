<?php namespace common\tests;

use common\fixtures\AreaFixture;


class AreaTest extends \Codeception\Test\Unit
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
            'areas' => AreaFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check area loaded',
                Area::find()->one()
            )->notNull();
        });

        $this->specify('Area model fields validation', function () {
            $area = new Area;

            expect('should not accept empty city_id', $area->validate(['city_id']))->false();
            expect('should not accept empty area_name', $area->validate(['area_name']))->false();

            $area->city_id = 12312312313;
            expect('should not accept invalid city_id', $area->validate(['city_id']))->false();
        });
    }
}