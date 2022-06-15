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
            'locations' => PartnerFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                Partner::find()->one()
            )->notNull();
        });

        $this->specify('Partner model fields validation', function () {
            $model = new Partner();

            expect('should not accept empty bank_id', $model->validate(['bank_id']))->false();
            expect('should not accept empty username', $model->validate(['username']))->false();
            expect('should not accept empty partner_password_hash', $model->validate(['partner_password_hash']))->false();
            expect('should not accept empty partner_email', $model->validate(['partner_email']))->false();
            expect('should not accept empty referral_code', $model->validate(['referral_code']))->false();

            $model->partner_email = 'randomString';
            expect('should not accept invalid email', $model->validate(['partner_email']))->false();

            $model->partner_email = 'demo@agent.com';
            expect('should accept valid email', $model->validate(['partner_email']))->true();

            $model->bank_id = 12312312313;
            expect('should not accept invalid bank_id', $model->validate(['bank_id']))->false();
        });
    }
}