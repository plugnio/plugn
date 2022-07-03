<?php namespace common\tests;

use common\fixtures\BankFixture;
use Codeception\Specify;
use common\models\Bank;

class BankTest extends \Codeception\Test\Unit
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
            'banks' => BankFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check bank loaded',
                Bank::find()->one()
            )->notNull();
        });

        $this->specify('Bank model fields validation', function () {
            $model = new Bank;

            expect('should not accept empty bank_name', $model->validate(['bank_name']))->false();
        });
    }
}