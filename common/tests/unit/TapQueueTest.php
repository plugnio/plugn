<?php namespace common\tests;

use common\fixtures\TapQueueFixture;
use Codeception\Specify;
use common\models\TapQueue;

class TapQueueTest extends \Codeception\Test\Unit
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
            'taps' => TapQueueFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check TapQueue discount loaded',
                TapQueue::find()->one()
            )->notNull();
        });

        $this->specify('BankDiscount model fields validation', function () {
            $model = new TapQueue;

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty queue_status', $model->validate(['queue_status']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
        });
    }
}