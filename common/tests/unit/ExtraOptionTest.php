<?php namespace common\tests;

use common\fixtures\ExtraOptionFixture;
use Codeception\Specify;
use common\models\ExtraOption;

class ExtraOptionTest extends \Codeception\Test\Unit
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
            'options' => ExtraOptionFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                ExtraOption::find()->one()
            )->notNull();
        });

        $this->specify('ExtraOption model fields validation', function () {
            $model = new ExtraOption();

            //expect('should not accept empty option_id', $model->validate(['option_id']))->false();
            expect('should not accept empty extra_option_name', $model->validate(['extra_option_name']))->false();

            $model->option_id = 12312312313;
            expect('should not accept invalid option_id', $model->validate(['option_id']))->false();
        });
    }
}