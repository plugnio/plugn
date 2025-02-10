<?php namespace common\tests;

use common\fixtures\QueueFixture;
use Codeception\Specify;
use common\models\Queue;

class QueueTest extends \Codeception\Test\Unit
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
            'queues' => QueueFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Queue::find()->one(), 'Check data loaded');

       // $this->specify('Queue model fields validation', function () {
            $model = new Queue();

            $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');

            $model->restaurant_uuid = 12312312313;
            $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');
       // });
    }
}