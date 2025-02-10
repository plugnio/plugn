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
            'agentAssignments' => AgentAssignmentFixture::class
        ];
    }

    // tests
    public function testSomeFeature()
    {
        // Test fixtures loading
        $assignment = AgentAssignment::find()->one();
        $this->assertNotNull($assignment, 'Agent assignment should be loaded');

        // Test model validation
        $agent = new AgentAssignment();
        
        // Test required fields
        $this->assertFalse($agent->validate(['role']), 'Empty role should not be accepted');
        $this->assertFalse($agent->validate(['agent_id']), 'Empty agent_id should not be accepted');
        $this->assertFalse($agent->validate(['restaurant_uuid']), 'Empty restaurant_uuid should not be accepted');

        // Test invalid values
        $agent->restaurant_uuid = 'randomString';
        $this->assertFalse($agent->validate(['restaurant_uuid']), 'Invalid restaurant_uuid should not be accepted');

        $agent->agent_id = 'randomString';
        $this->assertFalse($agent->validate(['agent_id']), 'Invalid agent_id should not be accepted');
    }
}