<?php namespace common\tests;

use common\fixtures\BusinessLocationFixture;
use common\models\BusinessLocation;

class BusinessLocationTest extends \Codeception\Test\Unit
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
            'locations' => BusinessLocationFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(BusinessLocation::find()->one(), 'Check data loaded');

        $model = new BusinessLocation;
        $this->assertFalse($model->validate(['country_id']), 'should not accept empty country_id');
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
        $this->assertFalse($model->validate(['business_location_name']), 'should not accept empty business_location_name');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');

        $model->country_id = 12312312313;
        $this->assertFalse($model->validate(['country_id']), 'should not accept invalid country_id');
    }
}