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
            'plans' => PlanFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                Plan::find()->one()
            )->notNull();
        });

        $this->specify('Plan model fields validation', function () {
            $model = new Plan;

            expect('should not accept empty name', $model->validate(['name']))->false();
            expect('should not accept empty description', $model->validate(['description']))->false();
        });
    }
}