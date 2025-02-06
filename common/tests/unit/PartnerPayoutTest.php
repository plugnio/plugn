<?php 
namespace common\tests;

use Codeception\Specify;
use common\fixtures\PartnerPayoutFixture;
use common\models\PartnerPayout;

class PartnerPayoutTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => PartnerPayoutFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(PartnerPayout::find()->one(), 'Check data loaded');

        $model = new PartnerPayout();
        $this->assertFalse($model->validate(['partner_uuid']), 'should not accept empty partner_uuid');

        $model->partner_uuid = 12312312313;
        $this->assertFalse($model->validate(['partner_uuid']), 'should not accept invalid partner_uuid');   

        $model->bank_id = 12312312313;
        $this->assertFalse($model->validate(['bank_id']), 'should not accept invalid bank_id');
    }
}