<?php namespace common\tests;

use common\fixtures\CustomerFixture;
use Codeception\Specify;
use common\models\Customer;

class CustomerTest extends \Codeception\Test\Unit
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
            'customers' => CustomerFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                Customer::find()->one()
            )->notNull();
        });

        $this->specify('Customer model fields validation', function () {
            $model = new Customer();

            expect('should not accept empty customer_name', $model->validate(['customer_name']))->false();
            expect('should not accept empty customer_email', $model->validate(['customer_email']))->false();
            expect('should not accept empty customer_phone_number', $model->validate(['customer_phone_number']))->false();
        });
    }
}