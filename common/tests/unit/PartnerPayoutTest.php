<?php namespace common\tests;

class PartnerPayoutTest extends \Codeception\Test\Unit
{
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
            'bankDiscounts' => PartnerPayoutFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                PartnerPayout::find()->one()
            )->notNull();
        });

        $this->specify('PartnerPayout model fields validation', function () {
            $model = new PartnerPayout;

            expect('should not accept empty partner_uuid', $model->validate(['partner_uuid']))->false();

            $model->partner_uuid = 12312312313;
            expect('should not accept invalid partner_uuid', $model->validate(['partner_uuid']))->false();

            $model->bank_id = 12312312313;
            expect('should not accept invalid bank_id', $model->validate(['bank_id']))->false();
        });
    }
}