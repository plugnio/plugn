<?php namespace common\tests;

use common\fixtures\BankFixture;
use common\models\Bank;

class BankTest extends \Codeception\Test\Unit
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
            'banks' => BankFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Bank::find()->one(), 'Check bank loaded');

        $model = new Bank();
        $this->assertFalse($model->validate(['bank_name']), 'should not accept empty bank_name');
    }
}