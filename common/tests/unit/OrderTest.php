<?php namespace common\tests;

use common\fixtures\OrderFixture;
use Codeception\Specify;
use common\models\Order;

class OrderTest extends \Codeception\Test\Unit
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
            'orders' => OrderFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                Order::find()->one()
            )->notNull();
        });

        $this->specify('Order model fields validation', function () {
            $model = new Order();

            expect('should not accept empty customer_name', $model->validate(['customer_name']))->false();
            expect('should not accept empty customer_phone_number', $model->validate(['customer_phone_number']))->false();
            expect('should not accept empty customer_phone_country_code', $model->validate(['customer_phone_country_code']))->false();
            //expect('should not accept empty customer_email', $model->validate(['customer_email']))->false();
            expect('should not accept empty payment_method_id', $model->validate(['payment_method_id']))->false();
            //expect('should not accept empty payment_method_name', $model->validate(['payment_method_name']))->false();

            //delivery_fee

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            //expect('should not accept empty restaurant_branch_id', $model->validate(['restaurant_branch_id']))->false();
            expect('should not accept empty order_mode', $model->validate(['order_mode']))->false();
            expect('should not accept empty subtotal', $model->validate(['subtotal']))->false();
            expect('should not accept empty total_price', $model->validate(['total_price']))->false();

            //expect('should not accept empty store_currency_code', $model->validate(['store_currency_code']))->false();
            //expect('should not accept empty currency_code', $model->validate(['currency_code']))->false();

            $model->pickup_location_id = 12312312313;
            expect('should not accept invalid pickup_location_id', $model->validate(['pickup_location_id']))->false();

            $model->bank_discount_id = 12312312313;
            expect('should not accept invalid bank_discount_id', $model->validate(['bank_discount_id']))->false();

            $model->voucher_id = 12312312313;
            expect('should not accept invalid voucher_id', $model->validate(['voucher_id']))->false();

            $model->restaurant_branch_id = 12312312313;
            expect('should not accept invalid restaurant_branch_id', $model->validate(['restaurant_branch_id']))->false();

            $model->payment_method_id = 12312312313;
            expect('should not accept invalid payment_method_id', $model->validate(['payment_method_id']))->false();

            $model->customer_id = 12312312313;
            expect('should not accept invalid customer_id', $model->validate(['customer_id']))->false();

            $model->shipping_country_id = 12312312313;
            expect('should not accept invalid shipping_country_id', $model->validate(['shipping_country_id']))->false();

            $model->delivery_zone_id = 12312312313;
            expect('should not accept invalid delivery_zone_id', $model->validate(['delivery_zone_id']))->false();

            $model->area_id = 12312312313;
            expect('should not accept invalid area_id', $model->validate(['area_id']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->payment_uuid = 12312312313;
            expect('should not accept invalid payment_uuid', $model->validate(['payment_uuid']))->false();
        });
    }
}