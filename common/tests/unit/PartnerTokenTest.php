<?php namespace common\tests;

use common\fixtures\PartnerTokenFixture;
use Codeception\Specify;
use common\models\Partner;
use common\models\PartnerToken;

class PartnerTokenTest extends \Codeception\Test\Unit
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
            'tokens' => PartnerTokenFixture::class
        ];
    }

    /**
     * Test Validation
     */
    public function testValidation()
    {
        $this->assertNotNull(Partner::find()->one(), 'Check data loaded');
        $this->assertNotNull(PartnerToken::find()->one(), 'Check data loaded');

        $model = new PartnerToken();
        $this->assertFalse($model->validate(['partner_uuid']), 'should not accept empty partner_uuid');
        $this->assertFalse($model->validate(['token_value']), 'should not accept empty token_value');
     
        $model->partner_uuid = 12312312313;
        $this->assertFalse($model->validate(['partner_uuid']), 'should not accept invalid partner_uuid');
  
      //  $model->token_status = 12312312313;
      //  $this->assertFalse($model->validate(['token_status']), 'should not accept invalid token_status');
         
    }

    /**
     * testing generate token
     * testing relating data
     */
    public function testGenerateToken()
    {
        $this->assertNotNull(PartnerToken::find()->one(), 'Check data loaded');

        $token = PartnerToken::generateUniqueTokenString();
        $this->assertNull(PartnerToken::findOne(['token_value' => $token]), 'Check unique token string');
    }
}