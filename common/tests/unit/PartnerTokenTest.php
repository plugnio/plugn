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
            'tokens' => PartnerTokenFixture::className()
        ];
    }

    /**
     * Test Validation
     */
    public function testValidation()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Partner is in the table', Partner::find()->one())->notNull();
            expect('Partner Token is in the table', PartnerToken::find()->one())->notNull();
        });

        $this->specify('Test Validator', function() {
            $model = new PartnerToken();
            $model->validate();
            expect('partner_uuid required error',$model->errors)->hasKey('partner_uuid');
            expect('token_value required error',$model->errors)->hasKey('token_value');
            expect('token_status required error',$model->errors)->hasKey('token_status');
            //expect('total 3 errors',count($model->errors))->equals(3);
        });
    }

    /**
     * testing generate token
     * testing relating data
     */
    public function testGenerateToken()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Partner Token is in the table', PartnerToken::find()->one())->notNull();
        });

        $this->specify('Test existing Token', function() {
            expect(
                'unique token string',
                PartnerToken::findOne(['token_value' => PartnerToken::generateUniqueTokenString()])
            )->null();
        });
    }
}