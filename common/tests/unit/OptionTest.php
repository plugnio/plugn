<?php namespace common\tests;

use common\fixtures\OptionFixture;
use Codeception\Specify;
use common\models\Option;

class OptionTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => OptionFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                Option::find()->one()
            )->notNull();
        });

        $this->specify('Option model fields validation', function () {
            $model = new Option();

            expect('should not accept empty item_uuid', $model->validate(['item_uuid']))->false();
            expect('should not accept empty option_name', $model->validate(['option_name']))->false();

            $model->item_uuid = 12312312313;
            expect('should not accept invalid item_uuid', $model->validate(['item_uuid']))->false();
        });
    }
}