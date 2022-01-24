<?php namespace common\tests;

use common\fixtures\OpeningHourFixture;
use Codeception\Specify;
use common\models\OpeningHour;

class OpeningHourTest extends \Codeception\Test\Unit
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
            'hours' => OpeningHourFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check bank discount loaded',
                OpeningHour::find()->one()
            )->notNull();
        });

        $this->specify('OpeningHour model fields validation', function () {
            $model = new OpeningHour();

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty day_of_week', $model->validate(['day_of_week']))->false();

            $model->is_closed = false;

            expect('should not accept empty open_at', $model->validate(['open_at']))->false();
            expect('should not accept empty close_at', $model->validate(['close_at']))->false();

            //expect('should not accept empty is_closed', $model->validate(['is_closed']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
        });
    }
}