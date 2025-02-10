<?php namespace common\tests;

use common\fixtures\ExtraOptionFixture;
use common\models\ExtraOption;

class ExtraOptionTest extends \Codeception\Test\Unit
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
            'options' => ExtraOptionFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(ExtraOption::find()->one(), 'Check data loaded');

        $model = new ExtraOption();
        $this->assertFalse($model->validate(['extra_option_name']), 'should not accept empty extra_option_name');

        $model->option_id = 12312312313;
        $this->assertFalse($model->validate(['option_id']), 'should not accept invalid option_id');
    }
}