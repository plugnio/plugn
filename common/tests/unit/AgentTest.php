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
            'agents' => AgentFixture::className()
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
        $this->specify('Fixtures should be loaded', function() {
            expect('Check agent loaded',
                Agent::find()->one()
            )->notNull();
        });

        $this->specify('Agent model fields validation', function () {
            $agent = new Agent;
            //$agent->scenario = 'newAccount';
            expect('should not accept empty agent_name', $agent->validate(['agent_name']))->false();
            expect('should not accept empty agent_email', $agent->validate(['agent_email']))->false();
            //expect('should not accept empty agent_password_hash', $agent->validate(['agent_password_hash']))->false();

            $agent->agent_email = 'randomString';
            expect('should not accept invalid email', $agent->validate(['agent_email']))->false();

            $agent->agent_email = 'demo@agent.com';
            expect('should accept valid email', $agent->validate(['agent_email']))->true();
        });
    }
}