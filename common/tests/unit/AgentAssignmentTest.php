<?php namespace common\tests;

use common\fixtures\AgentAssignmentFixture;
use common\models\AgentAssignment;
use Codeception\Specify;

class AgentAssignmentTest extends \Codeception\Test\Unit
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
            'agentAssignments' => AgentAssignmentFixture::className()
        ];
    }

    // tests
    public function testSomeFeature()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check agent assignment loaded',
                AgentAssignment::find()->one()
            )->notNull();
        });

        $this->specify('AgentAssignment model fields validation', function () {
            $agent = new AgentAssignment;

            expect('should not accept empty business_location_id', $agent->validate(['business_location_id']))->false();
            expect('should not accept empty role', $agent->validate(['role']))->false();
            expect('should not accept empty agent_id', $agent->validate(['agent_id']))->false();
            expect('should not accept empty restaurant_uuid', $agent->validate(['restaurant_uuid']))->false();

            $agent->restaurant_uuid = 'randomString';
            expect('should not accept invalid restaurant_uuid', $agent->validate(['restaurant_uuid']))->false();

            $agent->agent_id = 'randomString';
            expect('should not accept invalid agent_id', $agent->validate(['agent_id']))->false();

        });
    }
}