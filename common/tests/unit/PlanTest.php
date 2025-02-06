<?php namespace common\tests;

use common\fixtures\PlanFixture;
use Codeception\Specify;
use common\models\Plan;

class PlanTest extends \Codeception\Test\Unit
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
            'plans' => PlanFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Plan::find()->one(), 'Check data loaded');

       /// $this->specify('Plan model fields validation', function () {
            $model = new Plan;

            $this->assertFalse($model->validate(['name']), 'should not accept empty name');
            $this->assertFalse($model->validate(['description']), 'should not accept empty description');
      //  });
    }
}