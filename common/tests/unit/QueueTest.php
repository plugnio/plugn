<?php namespace common\tests;

use common\fixtures\QueueFixture;

class QueueTest extends \Codeception\Test\Unit
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
            'queues' => QueueFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                Queue::find()->one()
            )->notNull();
        });

        $this->specify('Queue model fields validation', function () {
            $model = new Queue;

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
        });
    }
}