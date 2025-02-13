<?php namespace common\tests;

use common\fixtures\PartnerFixture;
use Codeception\Specify;
use common\models\Partner;

class PartnerTest extends \Codeception\Test\Unit
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
            'locations' => PartnerFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Partner::find()->one(), 'Check data loaded');

        $model = new Partner();

        $this->assertFalse($model->validate(['bank_id']), 'should not accept empty bank_id');
        $this->assertFalse($model->validate(['username']), 'should not accept empty username');
        $this->assertFalse($model->validate(['partner_password_hash']), 'should not accept empty partner_password_hash');
        $this->assertFalse($model->validate(['partner_email']), 'should not accept empty partner_email');
        $this->assertFalse($model->validate(['referral_code']), 'should not accept empty referral_code');

        $model->partner_email = 'randomString';
        $this->assertFalse($model->validate(['partner_email']), 'should not accept invalid email');

        $model->partner_email = 'demo@agent.com';
        $this->assertTrue($model->validate(['partner_email']), 'should accept valid email');

        $model->bank_id = 12312312313;
        $this->assertFalse($model->validate(['bank_id']), 'should not accept invalid bank_id');
    }
}