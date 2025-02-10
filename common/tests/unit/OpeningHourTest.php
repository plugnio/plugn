<?php namespace common\tests;

use common\fixtures\OpeningHourFixture;
use common\models\OpeningHour;

class OpeningHourTest extends \Codeception\Test\Unit
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
            'hours' => OpeningHourFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(OpeningHour::find()->one(), 'Check opening hour loaded');

        $model = new OpeningHour();
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
        $this->assertFalse($model->validate(['day_of_week']), 'should not accept empty day_of_week');

        $model->is_closed = false;

        $this->assertFalse($model->validate(['open_at']), 'should not accept empty open_at');
        $this->assertFalse($model->validate(['close_at']), 'should not accept empty close_at');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');
    }
}