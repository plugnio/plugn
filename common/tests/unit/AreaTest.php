<?php namespace common\tests;

use common\fixtures\AreaFixture;
use common\models\Area;

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
            'areas' => AreaFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Area::find()->one(), 'Check area loaded');

        $area = new Area();
        $this->assertFalse($area->validate(['city_id']), 'should not accept empty city_id');
        $this->assertFalse($area->validate(['area_name']), 'should not accept empty area_name');

        $area->city_id = 12312312313;
        $this->assertFalse($area->validate(['city_id']), 'should not accept invalid city_id');
    }
}