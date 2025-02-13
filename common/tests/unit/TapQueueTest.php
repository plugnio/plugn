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
            'taps' => TapQueueFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(TapQueue::find()->one(), 'Check data loaded');

        $model = new TapQueue();
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
        $this->assertFalse($model->validate(['queue_status']), 'should not accept empty queue_status');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');
    }
}