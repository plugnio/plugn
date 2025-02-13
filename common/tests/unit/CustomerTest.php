<?php namespace common\tests;

use common\fixtures\CustomerFixture;
use common\models\Customer;

class CustomerTest extends \Codeception\Test\Unit
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
            'customers' => CustomerFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Customer::find()->one(), 'Check data loaded');

        $model = new Customer();
        $this->assertFalse($model->validate(['customer_name']), 'should not accept empty customer_name');
        $this->assertFalse($model->validate(['customer_phone_number']), 'should not accept empty customer_phone_number');
    }
}