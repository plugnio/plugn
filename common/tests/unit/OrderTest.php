<?php namespace common\tests;

use common\fixtures\OrderFixture;
// Removed Codeception\Specify; using plain PHPUnit assertions
use common\models\Order;

class OrderTest extends \Codeception\Test\Unit
{
    // Specify trait removed
    
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
            'orders' => OrderFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(Order::find()->one(), 'Check data loaded');

        $model = new Order();
        
        $this->assertFalse($model->validate(['customer_name']), 'should not accept empty customer_name');
        $this->assertFalse($model->validate(['customer_phone_number']), 'should not accept empty customer_phone_number');
        $this->assertFalse($model->validate(['customer_phone_country_code']), 'should not accept empty customer_phone_country_code');
        // $this->assertFalse($model->validate(['customer_email']), 'should not accept empty customer_email');
        $this->assertFalse($model->validate(['payment_method_id']), 'should not accept empty payment_method_id');
        // $this->assertFalse($model->validate(['payment_method_name']), 'should not accept empty payment_method_name');

        // delivery_fee

        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
        // $this->assertFalse($model->validate(['restaurant_branch_id']), 'should not accept empty restaurant_branch_id');
        $this->assertFalse($model->validate(['order_mode']), 'should not accept empty order_mode');
        $this->assertFalse($model->validate(['subtotal']), 'should not accept empty subtotal');
        $this->assertFalse($model->validate(['total_price']), 'should not accept empty total_price');

        // $this->assertFalse($model->validate(['store_currency_code']), 'should not accept empty store_currency_code');
        // $this->assertFalse($model->validate(['currency_code']), 'should not accept empty currency_code');

        $model->pickup_location_id = 12312312313;
        $this->assertFalse($model->validate(['pickup_location_id']), 'should not accept invalid pickup_location_id');

        $model->bank_discount_id = 12312312313;
        $this->assertFalse($model->validate(['bank_discount_id']), 'should not accept invalid bank_discount_id');

        $model->voucher_id = 12312312313;
        $this->assertFalse($model->validate(['voucher_id']), 'should not accept invalid voucher_id');

        $model->restaurant_branch_id = 12312312313;
        $this->assertFalse($model->validate(['restaurant_branch_id']), 'should not accept invalid restaurant_branch_id');

        $model->payment_method_id = 12312312313;
        $this->assertFalse($model->validate(['payment_method_id']), 'should not accept invalid payment_method_id');

        $model->customer_id = 12312312313;
        $this->assertFalse($model->validate(['customer_id']), 'should not accept invalid customer_id');

        $model->shipping_country_id = 12312312313;
        $this->assertFalse($model->validate(['shipping_country_id']), 'should not accept invalid shipping_country_id');

        $model->delivery_zone_id = 12312312313;
        $this->assertFalse($model->validate(['delivery_zone_id']), 'should not accept invalid delivery_zone_id');

        $model->area_id = 12312312313;
        $this->assertFalse($model->validate(['area_id']), 'should not accept invalid area_id');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');

        $model->payment_uuid = 12312312313;
        $this->assertFalse($model->validate(['payment_uuid']), 'should not accept invalid payment_uuid');
    }
}