<?php
namespace common\tests;

use Codeception\Specify;
use common\models\Agent;
use common\models\AgentToken;
use common\fixtures\AgentTokenFixture;

class AgentTokenTest extends \Codeception\Test\Unit
{
    use Specify;

    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return [
            'agentToken' => AgentTokenFixture::className()
        ];
    }

    protected function _before(){}

    protected function _after(){}

    /**
     * Test Validation
     */
    public function testValidation()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Agent is in the table', Agent::find()->one())->notNull();
            expect('Agent Token is in the table', AgentToken::find()->one())->notNull();
        });

        $this->specify('Test Validator', function() {
            $model = new AgentToken();
            $model->validate();

            expect('agent_id required error',$model->errors)->hasKey('agent_id');
            expect('token_value required error',$model->errors)->hasKey('token_value');
            expect('token_status required error',$model->errors)->hasKey('token_status');
            expect('total 3 errors',count($model->errors))->equals(3);
        });
    }

    /**
     * testing generate token
     * testing relating data
     */
    public function testGenerateToken()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Agent Token is in the table', AgentToken::find()->one())->notNull();
        });

        $this->specify('Test existing Token', function() {
            expect(
                'unique token string',
                AgentToken::findOne(['token_value' => AgentToken::generateUniqueTokenString()])
            )->null();
        });
    }
}
