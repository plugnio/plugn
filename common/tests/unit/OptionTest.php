<?php namespace common\tests;

use common\fixtures\OptionFixture;
use common\models\Option;
class OptionTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => OptionFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Option::find()->one(), 'Check data loaded');

        $model = new Option();
        $this->assertFalse($model->validate(['item_uuid']), 'should not accept empty item_uuid');
        $this->assertFalse($model->validate(['option_name']), 'should not accept empty option_name');

        $model->item_uuid = 12312312313;
        $this->assertFalse($model->validate(['item_uuid']), 'should not accept invalid item_uuid');
    }
}