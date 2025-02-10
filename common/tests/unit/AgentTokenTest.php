<?php
namespace common\tests;

use common\models\Agent;
use common\models\AgentToken;
use common\fixtures\AgentTokenFixture;

class AgentTokenTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return [
            'agentToken' => AgentTokenFixture::class
        ];
    }

    protected function _before(){}

    protected function _after(){}

    /**
     * Test Validation
     */
    public function testValidation()
    {
        $this->assertNotNull(Agent::find()->one(), 'Agent is in the table');
        $this->assertNotNull(AgentToken::find()->one(), 'Agent Token is in the table');

        $model = new AgentToken();
        $model->validate();
        $this->assertArrayHasKey('agent_id', $model->errors, 'agent_id required error');
        $this->assertArrayHasKey('token_value', $model->errors, 'token_value required error');
        $this->assertArrayHasKey('token_status', $model->errors, 'token_status required error');
        $this->assertEquals(3, count($model->errors), 'total 3 errors');
    }

    /**
     * testing generate token
     * testing relating data
     */
    public function testGenerateToken()
    {
        $this->assertNull(
            AgentToken::findOne(['token_value' => AgentToken::generateUniqueTokenString()]),
            'unique token string'
        );
    }
}
