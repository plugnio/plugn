<?php
namespace common\tests;

use common\fixtures\AgentFixture;
use common\models\Agent;
use Codeception\Specify;

class AgentTest extends \Codeception\Test\Unit
{
    use Specify;
    
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    public function _fixtures(){
        return [
            'agents' => AgentFixture::class
        ];
    }
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {   
        // Test fixtures loading
        $agent = Agent::find()->one();
        $this->assertNotNull($agent, 'Agent fixture should be loaded');

        // Test model validation
        $agent = new Agent();           

        // Test required fields
        $this->assertFalse($agent->validate(['agent_name']), 'Empty agent_name should not be accepted');
        $this->assertFalse($agent->validate(['agent_email']), 'Empty agent_email should not be accepted');

        // Test invalid values
        $agent->agent_email = 'randomString';
        $this->assertFalse($agent->validate(['agent_email']), 'Invalid agent_email should not be accepted');

        $agent->agent_email = 'demo@agent.com';
        $this->assertTrue($agent->validate(['agent_email']), 'Valid agent_email should be accepted');
    }
}