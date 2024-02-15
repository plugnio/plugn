<?php

namespace common\models;

use agent\models\PaymentMethod;
use api\models\Item;
use borales\extensions\phoneInput\PhoneInputBehavior;
use Cloudinary\Error;
use Swift_TransportException;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;
use yii\behaviors\AttributeBehavior;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "restaurant".
 *
 * @property string $restaurant_uuid
 * @property int $country_id
 * @property int $currency_id
 * @property string $name
 * @property string|null $name_ar
 * @property string|null $meta_title
 * @property string|null $meta_title_ar
 * @property string|null $meta_description
 * @property string|null $meta_description_ar
 * @property string|null $tagline
 * @property string|null $tagline_ar
 * @property string|null $restaurant_domain
 * @property string|null $app_id
 * @property int $restaurant_status
 * @property string|null $thumbnail_image
 * @property string|null $logo
 * @property string|null $logo_file_id
 * @property int|null $support_delivery
 * @property int|null $support_pick_up
 * @property string|null $phone_number
 * @property int|null $phone_number_country_code
 * @property string $restaurant_email
 * @property string|null $restaurant_created_at
 * @property string|null $restaurant_updated_at
 * @property string|null $restaurant_deleted_at
 * @property string|null $business_id
 * @property string|null $business_entity_id
 * @property string|null $wallet_id
 * @property string|null $merchant_id
 * @property string|null $tap_merchant_status
 * @property string|null $operator_id
 * @property string|null $live_api_key
 * @property string|null $test_api_key
 * @property string|null $business_type
 * @property string|null $vendor_sector
 * @property string|null $license_number
 * @property int $not_for_profit
 * @property string|null $authorized_signature_issuing_date
 * @property string|null $authorized_signature_expiry_date
 * @property string|null $authorized_signature_title
 * @property string|null $authorized_signature_file
 * @property string|null $authorized_signature_file_id
 * @property string|null $authorized_signature_file_purpose
 * @property string|null $iban_certificate_file
 * @property string|null $iban_certificate_file_id
 * @property string|null $iban
 * @property string|null $owner_first_name
 * @property string|null $owner_last_name
 * @property string|null $owner_email
 * @property string|null $owner_number
 * @property int|null $owner_phone_country_code
 * @property string|null $identification_issuing_date
 * @property string|null $identification_expiry_date
 * @property string|null $identification_file_front_side
 * @property string|null $identification_file_id_front_side
 * @property string|null $identification_title
 * @property string|null $identification_file_purpose
 * @property int|null $restaurant_email_notification
 * @property string|null $developer_id
 * @property string|null $armada_api_key
 * @property int|null $phone_number_display
 * @property string|null $store_branch_name
 * @property string|null $custom_css
 * @property int|null $store_layout
 * @property string|null $commercial_license_issuing_date
 * @property string|null $commercial_license_expiry_date
 * @property string|null $commercial_license_title
 * @property string|null $commercial_license_file
 * @property string|null $commercial_license_file_id
 * @property string|null $commercial_license_file_purpose
 * @property float|null $platform_fee
 * @property boolean $enable_cod_fee
 * @property string|null $google_analytics_id
 * @property string|null $facebook_pixil_id
 * @property string|null $google_tag_id
 * @property string|null $google_tag_manager_id
 * @property string|null $tiktok_pixel_id
 * @property int|null $show_opening_hours
 * @property string|null $instagram_url
 * @property int|null $schedule_order
 * @property int|null $schedule_interval
 * @property string|null $mashkor_branch_id
 * @property string|null $live_public_key
 * @property string|null $test_public_key
 * @property string|null $site_id
 * @property string|null $company_name
 * @property int|null $is_tap_enable
 * @property int|null $is_tap_created
 * @property int|null $is_tap_business_active
 * @property int|null $is_myfatoorah_enable
 * @property int|null $supplierCode
 * @property int|null $has_deployed
 * @property int|null $tap_queue_id
 * @property string|null $identification_file_back_side
 * @property string|null $identification_file_id_back_side
 * @property float|null $warehouse_fee
 * @property float $warehouse_delivery_charges
 * @property int|null $hide_request_driver_button
 * @property int|null $version
 * @property int|null $sitemap_require_update
 * @property string|null $snapchat_pixil_id
 * @property int|null $retention_email_sent
 * @property int|null $enable_gift_message
 * @property int|null $payment_gateway_queue_id
 * @property string|null $default_language
 * @property string|null $annual_revenue
 * @property boolean $demand_delivery
 * @property number $custom_subscription_price
 * @property boolean $is_sandbox
 * @property boolean $enable_debugger
 * @property boolean $accept_order_247
 * @property boolean $is_public
 * @property boolean $is_deleted
 * @property boolean $is_under_maintenance
 * @property string|null $last_active_at
 * @property string|null $last_order_at
 * @property number $total_orders
 * @property boolean $enable_guest_checkout
 * @property string $owner_name_title
 * @property string $owner_middle_name
 * @property string $owner_nationality
 * @property string $owner_date_of_birth
 * @property string $tax_number
 * @property string $swift_code
 * @property string $account_number
 *
 * @property AgentAssignment[] $agentAssignments
 * @property AreaDeliveryZone[] $areaDeliveryZones
 * @property BankDiscount[] $bankDiscounts
 * @property BusinessLocation[] $businessLocations
 * @property Category[] $categories
 * @property Customer[] $customers
 * @property DeliveryZone[] $deliveryZones
 * @property Item[] $items
 * @property OpeningHour[] $openingHours
 * @property Order[] $orders
 * @property Payment[] $payments
 * @property PaymentGatewayQueue[] $paymentGatewayQueues
 * @property Queue[] $queues
 * @property Refund[] $refunds
 * @property Country $country
 * @property Currency $currency
 * @property PaymentGatewayQueue $paymentGatewayQueue
 * @property TapQueue $tapQueue
 * @property RestaurantBranch[] $restaurantBranches
 * @property RestaurantDelivery[] $restaurantDeliveries
 * @property Area[] $areas
 * @property RestaurantPaymentMethod[] $restaurantPaymentMethods
 * @property PaymentMethod[] $paymentMethods
 * @property RestaurantTheme $restaurantTheme
 * @property StoreWebLink[] $storeWebLinks
 * @property Subscription[] $subscriptions
 * @property SubscriptionPayment[] $subscriptionPayments
 * @property TapQueue[] $tapQueues
 * @property Voucher[] $vouchers
 * @property WebLink[] $webLinks
 * @property RestaurantType[] $restaurantType
 * @property RestaurantItemType[] $restaurantItemTypes
 */
class Restaurant extends ActiveRecord
{
    //Values for `restaurant_status`
    const RESTAURANT_STATUS_OPEN = 1;
    const RESTAURANT_STATUS_BUSY = 2;
    const RESTAURANT_STATUS_CLOSED = 3;

    //Values for `phone_number_display`
    const PHONE_NUMBER_DISPLAY_DONT_SHOW_PHONE_NUMBER = 1;
    const PHONE_NUMBER_DISPLAY_ICON = 2;
    const PHONE_NUMBER_DISPLAY_SHOW_PHONE_NUMBER = 3;

    //Values for `store_layout`
    const STORE_LAYOUT_LIST_FULLWIDTH = 1;
    const STORE_LAYOUT_GRID_FULLWIDTH = 2;
    const STORE_LAYOUT_CATEGORY_FULLWIDTH = 3;
    const STORE_LAYOUT_LIST_HALFWIDTH = 4;
    const STORE_LAYOUT_GRID_HALFWIDTH = 5;
    const STORE_LAYOUT_CATEGORY_HALFWIDTH = 6;

    const SCENARIO_UPDATE_BANK = 'updateBankAccount';
    const SCENARIO_TOGGLE_DEBUGGER = 'toggleDebugger';
    const SCENARIO_UPDATE_DESIGN_LAYOUT = 'update_design_layout';
    const SCENARIO_CREATE_STORE_BY_AGENT = 'create-by-agent';
    const SCENARIO_CREATE_TAP_ACCOUNT = 'tap_account';
    const SCENARIO_RESET_TAP_ACCOUNT = 'resetTapAccount';
    const SCENARIO_CREATE_MYFATOORAH_ACCOUNT = 'myfatoorah_account';
    const SCENARIO_UPLOAD_STORE_DOCUMENT = 'upload';
    const SCENARIO_CONNECT_DOMAIN = 'domain';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_UPDATE_LOGO = 'update-logo';
    const SCENARIO_UPDATE_THUMBNAIL = 'update-thumbnail';

    const SCENARIO_UPDATE_ANALYTICS = 'update_analytics';
    const SCENARIO_UPDATE_DELIVERY = 'update_delivery';
    const SCENARIO_CURRENCY = 'currency';
    const SCENARIO_UPDATE_STATUS = 'update-status';
    const SCENARIO_DELETE = 'delete';

    public $restaurant_delivery_area;
    public $restaurant_payments_method;
    public $restaurant_logo;
    public $restaurant_thumbnail_image;
    public $export_orders_data_in_specific_date_range;
    public $export_sold_items_data_in_specific_date_range;
    public $owner_identification_file_front_side;
    public $owner_identification_file_back_side;
    public $restaurant_authorized_signature_file;
    public $restaurant_commercial_license_file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant';
    }

    public static function arrStatus()
    {
        return [
            self::RESTAURANT_STATUS_OPEN => "Open",
            self::RESTAURANT_STATUS_BUSY => "Busy",
            self::RESTAURANT_STATUS_CLOSED => "Closed"
        ];
    }

    public static function getTotalStoresByInterval($interval)
    {
        switch ($interval) {
            case "last-month":
                return self::getTotalStoresByMonth();
            case "week":
                return self::getTotalStoresByWeek();
            default:
                return self::getTotalStoresByMonths(str_replace(["last-", "-months"], ["", ""], $interval));
        }
    }

    public static function getTotalStoresByMonth()
    {
        $cacheDuration = 60 * 60 * 24 * 365;// 365 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM restaurant',
        ]);

        $customer_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-1 month')) . '-1';

        for ($i = 1; $i <= date('t', strtotime($date_start)); $i++) {
            $customer_data[$i] = array(
                'day' => $i,
                'total' => 0
            );
        }

        $rows = Restaurant::getDb()->cache(function ($db) {

            return Restaurant::find()
                ->select(new Expression('restaurant_created_at, COUNT(*) as total'))
                ->andWhere('`restaurant_created_at` >= (NOW() - INTERVAL 1 MONTH)')
                ->groupBy(new Expression('DAY(restaurant_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $customer_data[date('j', strtotime($result['restaurant_created_at']))] = array(
                'day' => (int)date('j', strtotime($result['restaurant_created_at'])),
                'total' => (int)$result['total']
            );
        }

        $number_of_all_customer_gained = Restaurant::getDb()->cache(function ($db) {

            return Restaurant::find()
                ->andWhere('`restaurant_created_at` >= (NOW() - INTERVAL 1 MONTH)')
                ->count();

        }, $cacheDuration, $cacheDependency);

        return [
            'store_created_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int)$number_of_all_customer_gained
        ];
    }

    public static function getTotalStoresByWeek()
    {
        $cacheDuration = 60 * 60 * 24 * 365;// 365 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM restaurant',
        ]);

        $customer_data = [];

        $date_start = strtotime('-6 days');//date('w')

        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', $date_start + ($i * 86400));

            $customer_data[date('w', strtotime($date))] = array(
                'day' => date('D', strtotime($date)),
                'total' => 0
            );
        }

        $rows = Restaurant::getDb()->cache(function ($db) {

            return Restaurant::find()
                ->select(new Expression('restaurant_created_at, COUNT(*) as total'))
                ->andWhere(new Expression("DATE(restaurant_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
                ->groupBy(new Expression('DAYNAME(restaurant_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $customer_data[date('w', strtotime($result['restaurant_created_at']))] = array(
                'day' => date('D', strtotime($result['restaurant_created_at'])),
                'total' => (int)$result['total']
            );
        }

        $number_of_all_customer_gained = Restaurant::getDb()->cache(function ($db) {

            return Restaurant::find()
                ->andWhere(new Expression("date(restaurant_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
                ->count();

        }, $cacheDuration, $cacheDependency);

        return [
            'store_created_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int)$number_of_all_customer_gained
        ];
    }

    public static function getTotalStoresByMonths($months)
    {
        $cacheDuration = 60 * 60 * 24 * 365;// 365 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM restaurant',
        ]);

        $customer_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-' . $months . ' month')) . '-1';
        $date_end = date('Y-m-d', strtotime('last day of previous month'));
        //date('Y-m-d');//date('Y') . '-' . date('m') . '-1';

        for ($i = 0; $i <= $months; $i++) {

            $month = date('m', strtotime('-' . ($months - $i) . ' month'));

            $customer_data[$month] = array(
                'month' => date('F', strtotime('-' . ($months - $i) . ' month')),
                'total' => 0
            );
        }

        $rows = Restaurant::getDb()->cache(function ($db) use ($months) {

            return Restaurant::find()
                ->select(new Expression('restaurant_created_at, COUNT(*) as total'))
                ->andWhere('`restaurant_created_at` >= (NOW() - INTERVAL ' . $months . ' MONTH)')
//            ->andWhere('DATE(`customer_created_at`) >= DATE("'.$date_start.'") AND DATE(`customer_created_at`) <= DATE("'.$date_end.'")')
                ->groupBy(new Expression('MONTH(restaurant_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $customer_data[date('m', strtotime($result['restaurant_created_at']))] = array(
                'month' => Yii::t('app', date('F', strtotime($result['restaurant_created_at']))),
                'total' => (int)$result['total']
            );
        }

        $number_of_all_customer_gained = Restaurant::getDb()->cache(function ($db) use ($months) {

            return Restaurant::find()
                ->andWhere('`restaurant_created_at` >= (NOW() - INTERVAL ' . $months . ' MONTH)')
//            ->andWhere('DATE(`customer_created_at`) >= DATE("'.$date_start.'") AND DATE(`customer_created_at`) <= DATE("'.$date_end.'")')
                ->count();

        }, $cacheDuration, $cacheDependency);

        return [
            'store_created_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int)$number_of_all_customer_gained
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['owner_first_name', 'owner_last_name', 'owner_email', 'owner_number'], 'required', 'on' => [self::SCENARIO_CREATE_TAP_ACCOUNT, self::SCENARIO_CREATE_MYFATOORAH_ACCOUNT]],
            [
                [
                    'vendor_sector', 'iban', 'company_name', 'business_type'
                ],
                'required', 'on' => [self::SCENARIO_CREATE_TAP_ACCOUNT, self::SCENARIO_CREATE_MYFATOORAH_ACCOUNT]
            ],
            [
                [
                    'identification_file_front_side', 'identification_file_back_side', 'commercial_license_file',
                ],
                'required', 'on' => self::SCENARIO_UPLOAD_STORE_DOCUMENT
            ],

            [['meta_title', 'meta_title_ar', 'meta_description', 'meta_description_ar', "owner_name_title", "owner_middle_name", "owner_nationality", "tax_number",  "swift_code", "account_number"], 'string'],

            [["owner_date_of_birth"], "string"],

            [['authorized_signature_file'], 'required'],
    /*, 'on' => self::SCENARIO_UPLOAD_STORE_DOCUMENT, 'when' => function ($model) {
                return $model->business_type == 'corp';
            }*/
            [['owner_first_name', 'owner_last_name'], 'string', 'min' => 3, 'on' => [self::SCENARIO_CREATE_TAP_ACCOUNT, self::SCENARIO_CREATE_MYFATOORAH_ACCOUNT]],
            [['identification_file_id_back_side', 'identification_file_id_front_side', 'authorized_signature_file_id', 'commercial_license_file_id', 'iban_certificate_file', 'iban_certificate_file_id', 'logo_file_id'], 'safe', 'on' => [
                self::SCENARIO_CREATE_TAP_ACCOUNT,
                self::SCENARIO_CREATE_MYFATOORAH_ACCOUNT
            ]],
            [['not_for_profit', 'total_orders'], 'number'],

            [['authorized_signature_issuing_date', 'authorized_signature_expiry_date', 'commercial_license_issuing_date', 'commercial_license_expiry_date', 'identification_issuing_date', 'identification_expiry_date'], 'safe', 'on' => [self::SCENARIO_CREATE_TAP_ACCOUNT, self::SCENARIO_CREATE_MYFATOORAH_ACCOUNT]],
            ['owner_email', 'email'],
            ['iban', 'safe'],
            [
                [
                    'business_type', 'vendor_sector', 'license_number',
                    'authorized_signature_issuing_date', 'authorized_signature_expiry_date',
                    'authorized_signature_file', 'authorized_signature_file_purpose', 'authorized_signature_title',
                    'commercial_license_issuing_date', 'commercial_license_expiry_date',
                    'commercial_license_file', 'commercial_license_file_purpose', 'commercial_license_title',
                    'owner_first_name', 'owner_last_name',
                    'owner_email',
                    'tap_merchant_status',
                    'identification_issuing_date', 'identification_title',
                    'identification_expiry_date', 'identification_file_back_side', 'identification_file_front_side', 'identification_file_purpose',
                    'business_id', 'business_entity_id', 'wallet_id', 'merchant_id', 'operator_id',
                    'live_api_key', 'test_api_key', 'developer_id', 'live_public_key', 'test_public_key', 'annual_revenue'
                ],
                'string', 'max' => 255
            ],
            ['iban', 'string', 'min' => 10, 'max' => 34, 'message' => 'The IBAN must be at least 10 characters long.', 'on' => [self::SCENARIO_CREATE_TAP_ACCOUNT, self::SCENARIO_CREATE_MYFATOORAH_ACCOUNT]],

            ['iban', 'match', 'pattern' => '/^[a-zA-Z0-9-]+$/', 'message' => 'Please check the IBAN, we might not support transfering to this bank.', 'on' => [self::SCENARIO_CREATE_TAP_ACCOUNT, self::SCENARIO_CREATE_MYFATOORAH_ACCOUNT]],
            [['restaurant_commercial_license_file', 'owner_identification_file_front_side', 'owner_identification_file_back_side'], 'file', 'skipOnEmpty' => true, 'on' => self::SCENARIO_UPLOAD_STORE_DOCUMENT],
            [['restaurant_authorized_signature_file', 'owner_identification_file_front_side', 'owner_identification_file_back_side'], 'file', 'skipOnEmpty' => true, 'on' => self::SCENARIO_UPLOAD_STORE_DOCUMENT],
            [['name', 'name_ar', 'support_delivery', 'support_pick_up', 'restaurant_payments_method', 'restaurant_domain', 'restaurant_email', 'store_branch_name', 'app_id'], 'required', 'on' => 'create'],
            [['name', 'name_ar', 'restaurant_email'], 'required', 'on' => 'default'],
            [['name', 'restaurant_domain', 'currency_id', 'country_id'], 'required', 'on' => self::SCENARIO_CREATE_STORE_BY_AGENT],
// 'owner_number',
            ['name', 'match', 'pattern' => '/^[a-zA-Z0-9-\s]+$/', 'message' => 'Your store name can only contain alphanumeric characters', 'on' => self::SCENARIO_CREATE_STORE_BY_AGENT],

            ['restaurant_domain', 'match', 'pattern' => '/^[a-zA-Z0-9-]+$/', 'message' => 'Your store url can only contain alphanumeric characters', 'on' => self::SCENARIO_CREATE_STORE_BY_AGENT],

            [['restaurant_domain'], 'url', 'except' => self::SCENARIO_CREATE_STORE_BY_AGENT],

            [['restaurant_domain'], 'string', 'min' => 3, 'max' => 20, 'on' => self::SCENARIO_CREATE_STORE_BY_AGENT],

            ['restaurant_domain', 'validateDomain'],

            [['ip_address'], 'string', 'max' => 45],

            [['name', 'name_ar'], 'string', 'min' => 3],
            [['restaurant_thumbnail_image', 'restaurant_logo'], 'file', 'extensions' => 'jpg, jpeg , png, pdf', 'maxFiles' => 1],
            [['restaurant_delivery_area', 'restaurant_payments_method'], 'safe'],
            [['restaurant_status', 'support_delivery', 'support_pick_up', 'hide_request_driver_button', 'sitemap_require_update', 'version'], 'integer', 'min' => 0],
            [['schedule_interval'], 'integer', 'min' => 5],
            ['restaurant_status', 'in', 'range' => [self::RESTAURANT_STATUS_OPEN, self::RESTAURANT_STATUS_BUSY, self::RESTAURANT_STATUS_CLOSED]],
            ['store_layout', 'in', 'range' => [self::STORE_LAYOUT_LIST_FULLWIDTH, self::STORE_LAYOUT_GRID_FULLWIDTH, self::STORE_LAYOUT_CATEGORY_FULLWIDTH, self::STORE_LAYOUT_LIST_HALFWIDTH, self::STORE_LAYOUT_GRID_HALFWIDTH, self::STORE_LAYOUT_CATEGORY_HALFWIDTH]],
            ['phone_number_display', 'in', 'range' => [self::PHONE_NUMBER_DISPLAY_ICON, self::PHONE_NUMBER_DISPLAY_SHOW_PHONE_NUMBER, self::PHONE_NUMBER_DISPLAY_DONT_SHOW_PHONE_NUMBER]],
            [['restaurant_created_at', 'restaurant_updated_at', 'restaurant_deleted_at', 'has_deployed', 'tap_queue_id', 'payment_gateway_queue_id'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['default_language'], 'string', 'max' => 2],
            [['custom_css'], 'string'],
            [['is_public', 'is_deleted', 'is_under_maintenance', 'accept_order_247', 'enable_debugger', 'is_sandbox', 'enable_cod_fee'], 'boolean'],
            [['platform_fee', 'warehouse_fee', 'warehouse_delivery_charges'], 'number'],
            [['instagram_url'], 'url'],
            [['export_orders_data_in_specific_date_range', 'export_sold_items_data_in_specific_date_range', 'google_analytics_id', 'facebook_pixil_id', 'google_tag_id', 'google_tag_manager_id', 'tiktok_pixel_id', 'snapchat_pixil_id', 'site_id'], 'safe'],
            [['name', 'name_ar', 'tagline', 'tagline_ar', 'thumbnail_image', 'logo', 'app_id', 'armada_api_key', 'mashkor_branch_id', 'store_branch_name', 'live_public_key', 'test_public_key', 'company_name'], 'string', 'max' => 255],

            [['live_public_key', 'test_public_key'], 'default', 'value' => null],

            [['authorized_signature_title'], 'default', 'value' => 'Authorized Signature', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],
            [['authorized_signature_file_purpose'], 'default', 'value' => 'customer_signature', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],

            [['commercial_license_title'], 'default', 'value' => 'Commercial License', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],
            [['commercial_license_file_purpose'], 'default', 'value' => 'customer_signature', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],


            [['identification_title'], 'default', 'value' => 'Owner civil id', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],
            [['identification_file_purpose'], 'default', 'value' => 'identity_document', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],


            [['country_id', 'currency_id', 'owner_phone_country_code', 'phone_number_country_code', 'retention_email_sent', 'enable_gift_message'], 'integer'],

            [['phone_number', 'owner_number'], 'string', 'min' => 6, 'max' => 20],

            [['last_active_at', 'last_order_at'], 'safe'],

            //[['phone_number', 'owner_number'], 'number'],

            // [['owner_number'], PhoneInputValidator::className(), 'message' => 'Please insert a valid phone number', 'on' => [self::SCENARIO_CREATE_TAP_ACCOUNT, self::SCENARIO_CREATE_MYFATOORAH_ACCOUNT ,self::SCENARIO_CREATE_STORE_BY_AGENT]],
            // [['phone_number'], PhoneInputValidator::className(), 'message' => 'Please insert a valid phone number'],

            //  ['currency_id', function ($attribute, $params, $validator) {
            //     if ($this->getOrders()->exists())
            //         $this->addError($attribute, "You've made your first sale, so you need to contact support if you want to change your currency.");
            // }],

            [
                ['identification_file_front_side'],
                '\common\components\S3FileExistValidator',
                'filePath' => '',
                'message' => Yii::t('app', "Please upload identification file (front side)"),
                'resourceManager' => Yii::$app->temporaryBucketResourceManager,
                'when' => function ($model, $attribute) {
                    return $model->{$attribute} !== $model->getOldAttribute($attribute) && $this->scenario == self::SCENARIO_CREATE_TAP_ACCOUNT;
                }
            ],
            [
                ['identification_file_back_side'],
                '\common\components\S3FileExistValidator',
                'filePath' => '',
                'message' => Yii::t('app', "Please upload identification file (back side)"),
                'resourceManager' => Yii::$app->temporaryBucketResourceManager,
                'when' => function ($model, $attribute) {
                    return $model->{$attribute} !== $model->getOldAttribute($attribute) && $this->scenario == self::SCENARIO_CREATE_TAP_ACCOUNT;
                }
            ],
            [
                ['commercial_license_file'],
                '\common\components\S3FileExistValidator',
                'filePath' => '',
                'message' => Yii::t('app', "Please upload commercial license file"),
                'resourceManager' => Yii::$app->temporaryBucketResourceManager,
                'when' => function ($model, $attribute) {
                    return $model->{$attribute} !== $model->getOldAttribute($attribute) && $this->scenario == self::SCENARIO_CREATE_TAP_ACCOUNT;
                }
            ],
            [
                ['authorized_signature_file'],
                '\common\components\S3FileExistValidator',
                'filePath' => '',
                'message' => Yii::t('app', "Please upload a authorized signature file"),
                'resourceManager' => Yii::$app->temporaryBucketResourceManager,
                'when' => function ($model, $attribute) {
                    return $model->{$attribute} !== $model->getOldAttribute($attribute) && $this->scenario == self::SCENARIO_CREATE_TAP_ACCOUNT;
                }
            ],
            [
                ['iban_certificate_file'],
                '\common\components\S3FileExistValidator',
                'filePath' => '',
                'message' => Yii::t('app', "Please upload IBAN certificate file"),
                'resourceManager' => Yii::$app->temporaryBucketResourceManager,
                'when' => function ($model, $attribute) {
                    return $model->{$attribute} !== $model->getOldAttribute($attribute) &&
                        $this->scenario == self::SCENARIO_CREATE_TAP_ACCOUNT;
                }
            ],
            [['restaurant_email_notification', 'demand_delivery', 'schedule_order', 'phone_number_display', 'store_layout', 'show_opening_hours', 'is_tap_enable', 'is_tap_created', 'is_tap_business_active', 'is_myfatoorah_enable', 'supplierCode'], 'integer'],
            [['schedule_interval'], 'required', 'when' => function ($model) {
                return $model->schedule_order;
            }
            ],
            [['custom_subscription_price'], 'number', 'min' => 0],
            [['referral_code'], 'string', 'max' => 6],
            [['referral_code'], 'default', 'value' => null],
            ['restaurant_email', 'email'],
            [['enable_guest_checkout'], 'boolean'],
            [['restaurant_uuid', 'restaurant_domain', 'name', 'owner_email'], 'unique'],
            [['payment_gateway_queue_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentGatewayQueue::className(), 'targetAttribute' => ['payment_gateway_queue_id' => 'payment_gateway_queue_id']],
            [['tap_queue_id'], 'exist', 'skipOnError' => true, 'targetClass' => TapQueue::className(), 'targetAttribute' => ['tap_queue_id' => 'tap_queue_id']],
            [['referral_code'], 'exist', 'skipOnError' => true, 'targetClass' => Partner::className(), 'targetAttribute' => ['referral_code' => 'referral_code']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'country_id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'currency_id']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        return array_merge($scenarios, [
            self::SCENARIO_DELETE => ['is_deleted', 'site_id', 'ip_address', 'restaurant_deleted_at'],
            self::SCENARIO_UPDATE_BANK => ['iban', 'ip_address'],
            self::SCENARIO_TOGGLE_DEBUGGER => ['enable_debugger', 'ip_address'],
            self::SCENARIO_CONNECT_DOMAIN => ['restaurant_domain', 'ip_address'],
            self::SCENARIO_UPDATE_STATUS => ['restaurant_status', 'ip_address'],
            self::SCENARIO_UPDATE_ANALYTICS => [
                'google_analytics_id',
                'facebook_pixil_id',
                'snapchat_pixil_id',
                'google_tag_id',
                'google_tag_manager_id',
                'tiktok_pixel_id',
                'sitemap_require_update',
                'ip_address'
            ],
            self::SCENARIO_CREATE_STORE_BY_AGENT => [
                'name',
                'owner_number',
                'restaurant_domain',
                'currency_id',
                'country_id',
                'ip_address'
            ],
            self::SCENARIO_UPDATE_LOGO => [
                'logo', 'ip_address'
            ],
            self::SCENARIO_UPDATE_THUMBNAIL => [
                'thumbnail_image', 'ip_address'
            ],
            self::SCENARIO_UPDATE_DESIGN_LAYOUT => [
                'logo',
                'thumbnail_image',
                'restaurant_logo',
                'restaurant_thumbnail_image',

                "custom_css",
                "default_language",
                "store_layout",
                'phone_number_display',

                'sitemap_require_update',
                'ip_address'
            ],
            self::SCENARIO_RESET_TAP_ACCOUNT => [
                'business_id',
                'business_entity_id',
                'tap_merchant_status',
                'wallet_id',
                'merchant_id',
                'operator_id',
                'live_api_key',
                'test_api_key',
                'license_number',
                'authorized_signature_issuing_date',
                'authorized_signature_expiry_date',
                'authorized_signature_title',
                'authorized_signature_file',
                'authorized_signature_file_id',
                'logo_file_id',
                'authorized_signature_file_purpose',
                'iban',
                'identification_issuing_date',
                'identification_expiry_date',
                'identification_file_front_side',
                'identification_file_id_front_side',
                'identification_title',
                'identification_file_purpose',
                'restaurant_email_notification',
                'developer_id',
                'commercial_license_issuing_date',
                'commercial_license_expiry_date',
                'commercial_license_title',
                'commercial_license_file',
                'commercial_license_file_id',
                'commercial_license_file_purpose',
                'iban_certificate_file',
                'iban_certificate_file_id',
                'live_public_key',
                'test_public_key',
                'is_tap_enable',
                'is_tap_created',
                'is_tap_business_active',
                'identification_file_back_side',
                'identification_file_id_back_side',
                'payment_gateway_queue_id',
                'tap_queue_id',
                'ip_address',
                "owner_name_title", "owner_middle_name", "owner_nationality", "owner_date_of_birth", "tax_number", "swift_code", "account_number",
            ],
            self::SCENARIO_CREATE_TAP_ACCOUNT => [
                'owner_first_name', 'owner_last_name', 'owner_email', 'owner_number',
                'vendor_sector', 'iban', 'company_name', 'business_type',
                'identification_file_id_back_side', 'identification_file_id_front_side',
                'authorized_signature_file_id', 'commercial_license_file_id', 'logo_file_id',
                'iban', 'authorized_signature_issuing_date', 'authorized_signature_expiry_date',
                'commercial_license_issuing_date', 'commercial_license_expiry_date', 'identification_issuing_date',
                'identification_expiry_date', 'iban_certificate_file', 'iban_certificate_file_id', 'tap_merchant_status',
                'is_tap_business_active', 'ip_address', "owner_name_title", "owner_middle_name", "owner_nationality",
                "owner_date_of_birth", "tax_number", "swift_code", "account_number"],

            self::SCENARIO_UPLOAD_STORE_DOCUMENT => [
                'commercial_license_file', 'authorized_signature_file', 'identification_file_front_side',
                'identification_file_back_side', 'restaurant_commercial_license_file', 'owner_identification_file_front_side',
                'owner_identification_file_back_side', 'restaurant_authorized_signature_file', 'ip_address'
            ],
            self::SCENARIO_UPDATE => [
                'country_id', 'restaurant_email_notification', 'demand_delivery', 'phone_number', 'phone_number_country_code',
                'name', 'name_ar', 'schedule_interval', 'schedule_order', 'is_sandbox',
                'restaurant_email', 'tagline', 'tagline_ar', 'currency_id', 'meta_description', 'meta_description_ar',
                'owner_first_name', 'owner_last_name', 'owner_number', 'owner_email', 'owner_phone_country_code',
                "enable_gift_message", "accept_order_247", "is_public", "currency_id", 'ip_address', 'enable_guest_checkout'
            ],
            self::SCENARIO_UPDATE_DELIVERY => [
                'armada_api_key',
                'mashkor_branch_id', 'ip_address'
            ],
            self::SCENARIO_CURRENCY => [
                'currency_id', 'ip_address'
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'country_id' => Yii::t('app', 'Country'),
            'currency_id' => Yii::t('app', 'Store currency'),
            'name' => Yii::t('app', 'Store name / Business name in English'),
            'name_ar' => Yii::t('app', 'Store name / Business name in Arabic'),
            'tagline' => Yii::t('app', 'Tagline in English'),
            'tagline_ar' => Yii::t('app', 'Tagline in Arabic'),
            'meta_title' => Yii::t('app', 'Page Title'),
            'meta_title_ar' => Yii::t('app', 'Page Title in Arabic'),
            'meta_description' => Yii::t('app', 'Meta Tag Description'),
            'meta_description_ar' => Yii::t('app', 'Meta Tag Description in Arabic'),
            'restaurant_domain' => Yii::t('app', 'Store Url'),
            'app_id' => Yii::t('app', 'App id'),
            'restaurant_payments_method' => Yii::t('app', 'Payment method'),
            'restaurant_status' => Yii::t('app', 'Store Status'),
            'thumbnail_image' => Yii::t('app', 'Header Image'),
            'logo' => Yii::t('app', 'Logo'),
            'restaurant_thumbnail_image' => Yii::t('app', 'Header Image'),
            'restaurant_logo' => Yii::t('app', 'Logo'),
            'support_delivery' => Yii::t('app', 'Support Delivery'),
            'support_pick_up' => Yii::t('app', 'Support Pick Up'),
            'hide_request_driver_button' => Yii::t('app', 'Hide request driver button'),
            'restaurant_delivery_area' => Yii::t('app', 'Delivery Areas'),
            'export_orders_data_in_specific_date_range' => Yii::t('app', 'Export orders data in a specific date range'),
            'export_sold_items_data_in_specific_date_range' => Yii::t('app', 'Export sold items data in a specific date range'),
            'phone_number' => Yii::t('app', "Store's phone number"),
            'restaurant_email' => Yii::t('app', "Store's Email"),
            'restaurant_created_at' => Yii::t('app', 'Store Created At'),
            'restaurant_updated_at' => Yii::t('app', 'Store Updated At'),
            'restaurant_deleted_at' => Yii::t('app', 'Store Deleted At'),
            'armada_api_key' => Yii::t('app', 'Armada Api Key'),
            'armada_branch_id' => Yii::t('app', 'Mashkor Branch ID'),
            'restaurant_email_notification' => Yii::t('app', 'Email notification'),
            'show_opening_hours' => Yii::t('app', 'Show Opening hours'),
            'phone_number_display' => Yii::t('app', 'Phone number display'),
            'store_branch_name' => Yii::t('app', 'Github repo branch name'),
            'custom_css' => Yii::t('app', 'Custom css'),
            'platform_fee' => Yii::t('app', 'Platform fee'),
            'enable_cod_fee' => yii::t('app', 'Enable cod fee'),
            'warehouse_fee' => Yii::t('app', 'Warehouse fee'),
            'warehouse_delivery_charges' => Yii::t('app', 'Delivery charges'),
            'company_name' => Yii::t('app', 'Company name'),
            'store_layout' => Yii::t('app', 'Store layout'),
            'google_analytics_id' => Yii::t('app', 'Google Analytics ID'),
            'facebook_pixil_id' => Yii::t('app', 'Facebook Pixil ID'),
            'google_tag_id' => Yii::t('app', 'Google Tag ID'),
            'google_tag_manager_id' => Yii::t('app', 'Google Tag Manager ID'),
            'tiktok_pixel_id' => Yii::t('app', 'TokTok Pixel ID'),
            'instagram_url' => Yii::t('app', 'Instagram url'),
            'schedule_order' => Yii::t('app', 'Schedule order'),
            'schedule_interval' => Yii::t('app', 'Schedule interval'),
            'business_type' => Yii::t('app', 'Account type'),
            'vendor_sector' => Yii::t('app', 'Vendor sector'),
            'license_number' => Yii::t('app', 'License number'),
            'owner_identification_file_front_side' => Yii::t('app', 'Civil ID Front side'),
            'owner_identification_file_back_side' => Yii::t('app', 'Civil ID Back side'),
            'authorized_signature_issuing_date' => Yii::t('app', 'Authorized Signature Issuing Date'),
            'authorized_signature_expiry_date' => Yii::t('app', 'Authorized Signature Expiry Date'),
            'authorized_signature_file' => Yii::t('app', 'Authorized signature'),
            'restaurant_authorized_signature_file' => Yii::t('app', 'Authorized Signature'),
            'authorized_signature_title' => Yii::t('app', 'Authorized Signature Title'),
            'authorized_signature_file_purpose' => Yii::t('app', 'Authorized Signature File Purpose'),
            'commercial_license_issuing_date' => Yii::t('app', 'Commercial License Issuing Date'),
            'commercial_license_expiry_date' => Yii::t('app', 'Commercial License Expiry Date'),
            'commercial_license_file' => Yii::t('app', 'License copy'),
            'restaurant_commercial_license_file' => Yii::t('app', 'License copy'),
            'commercial_license_title' => Yii::t('app', 'Commercial License Title'),
            'commercial_license_file_purpose' => Yii::t('app', 'Commercial License File Purpose'),
            'iban' => Yii::t('app', 'IBAN'),
            'owner_first_name' => Yii::t('app', 'First Name'),
            'owner_last_name' => Yii::t('app', 'Last Name'),
            'owner_email' => Yii::t('app', 'Owner/ Tap account Email Address'),
            'owner_number' => Yii::t('app', 'Owner Phone Number'),
            'identification_issuing_date' => Yii::t('app', 'Identification Issuing Date'),
            'identification_expiry_date' => Yii::t('app', 'Identification Expiry Date'),
            'identification_file_front_side' => Yii::t('app', ' National ID File front side'),
            'identification_file_back_side' => Yii::t('app', ' National ID File back side'),
            'identification_title' => Yii::t('app', 'Identification Title'),
            'identification_file_purpose' => Yii::t('app', 'Identification File Purpose'),
            'iban_certificate_file' => Yii::t('app', 'IBAN Certificate'),
            'live_api_key' => Yii::t('app', 'Live secret key'),
            'test_api_key' => Yii::t('app', 'Test secret key'),
            'default_language' => Yii::t('app', 'Default Language'),
            'custom_subscription_price' => Yii::t('app', 'Custom Subscription Price'),
            //'demand_delivery' => Yii::t('app','Accept order 24/7')
            'accept_order_247' => Yii::t('app', 'Accept order 24/7'),
            'is_public' => Yii::t('app', 'Is Public?'),
            'is_deleted' => Yii::t('app', 'Is Deleted?'),
            'is_under_maintenance' => Yii::t('app', 'Is Under Maintenance?'),
            'enable_guest_checkout' => Yii::t('app', 'Enable Guest Checkout'),
        ];
    }

    /**
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'restaurant_uuid',
                ],
                'value' => function () {
                    if (!$this->restaurant_uuid)
                        $this->restaurant_uuid = 'rest_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->restaurant_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'restaurant_created_at',
                'updatedAtAttribute' => 'restaurant_updated_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => PhoneInputBehavior::className(),
                // 'attributes' => [
                //           ActiveRecord::EVENT_BEFORE_INSERT => ['owner_phone_country_code', 'owner_number'],
                //       ],
                'countryCodeAttribute' => 'owner_phone_country_code',
                'phoneAttribute' => 'owner_number',
            ],
            [
                'class' => PhoneInputBehavior::className(),
                // 'attributes' => [
                //           ActiveRecord::EVENT_BEFORE_INSERT => ['phone_number_country_code', 'phone_number'],
                //       ],
                'countryCodeAttribute' => 'phone_number_country_code',
                'phoneAttribute' => 'phone_number',
            ],
        ];
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getStatus()
    {
        switch ($this->restaurant_status) {
            case self::RESTAURANT_STATUS_OPEN:
                return "Open";
                break;
            case self::RESTAURANT_STATUS_BUSY:
                return "Busy";
                break;
            case self::RESTAURANT_STATUS_CLOSED:
                return "Closed";
                break;
        }

        return "Couldnt find a status";
    }

    /**
     * Upload a File from AWS to cloudinary
     * @param $file_path
     * @param $attribute
     * @return bool
     * @throws \yii\base\Exception
     */
    public function uploadFileFromAwsToCloudinary($file, $attribute)
    {
        $url = Yii::$app->temporaryBucketResourceManager->getUrl($file);

        try {
            $filename = Yii::$app->security->generateRandomString();

            $result = Yii::$app->cloudinaryManager->upload(
                $url, [
                'public_id' => "restaurants/" . $this->restaurant_uuid . "/private_documents/" . $filename
            ]);

            if ($result || count($result) > 0) {
                $this[$attribute] = basename($result['url']);
                $this->$attribute = basename($result['url']);
                return true;
            }

        } catch (Error $err) {
            //Yii::error ('Error when uploading restaurant document to Cloudinary: ' . json_encode ($err));
            $this->addError($attribute, $err->getMessage());
            return false;
        }
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     * @return bool
     */
    public function validateDomain($attribute, $params, $validator)
    {
        $count = $this->getRestaurantDomainRequests()->count();

        if ($count > 3) {
            Yii::error("Store #" . $this->restaurant_uuid . " having more than 3 domain assigned to store");
            //$this->addError($attribute, 'You can not assign more than 3 domain to site');
        }

        $restaurantDomain = $this->getRestaurantDomainRequests()
            ->one();

        if ($restaurantDomain && date("Y-m-d", strtotime($restaurantDomain->created_at)) == date("Y-m-d")) {
            $this->addError($attribute, 'You can not update domain more than twice in single day');
            return false;
        }

        return true;
    }

    /**
     * Gets query for [[RestaurantDomainRequest]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantDomainRequests($modelClass = "\common\models\RestaurantDomainRequest")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->orderBy('created_at DESC');
    }

    /**
     * request for new domain
     * @param $old_domain
     * @return array
     */
    public function notifyDomainRequest($old_domain)
    {

        $model = new RestaurantDomainRequest;
        $model->restaurant_uuid = $this->restaurant_uuid;
        $model->created_by = Yii::$app->user->getId();
        $model->domain = $this->restaurant_domain;
        $model->status = RestaurantDomainRequest::STATUS_PENDING;
        $model->save(false);

        //if custom domain + want to purchase

        Yii::info("[Store Domain Update Request] " . $this->name . " want to change domain from " .
            $old_domain . " to " . $this->restaurant_domain, __METHOD__);

        $mailer = Yii::$app->mailer->compose([
            'html' => 'domain-update-request',
        ], [
            'store_name' => $this->name,
            'new_domain' => $this->restaurant_domain,
            'old_domain' => $old_domain
        ])
            ->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->name])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('[Plugn] Agent updated DN');

        try {
            $mailer->send();
        } catch (Swift_TransportException $e) {
            Yii::error($e->getMessage(), "email");
        }

        return self::message("success", "Our customer service agent will contact you soon!");
    }

    /**
     * @param string $type
     * @param $message
     * @return array
     */
    public static function message($type = "success", $message)
    {
        return [
            "operation" => $type,
            "message" => is_string($message) ? Yii::t('agent', $message) : $message
        ];
    }

    public function notifyDomainUpdated($old_domain)
    {

        $model = new RestaurantDomainRequest;
        $model->restaurant_uuid = $this->restaurant_uuid;
        $model->created_by = Yii::$app->user->getId();
        $model->domain = $this->restaurant_domain;
        $model->status = RestaurantDomainRequest::STATUS_ASSIGNED;
        $model->save(false);

        Yii::info("[Store Domain Updated] " . $this->name . " changed domain from " .
            $old_domain . " to " . $this->restaurant_domain, __METHOD__);

        $mailer = Yii::$app->mailer->compose([
            'html' => 'store/domain-updated',
        ], [
            'store_name' => $this->name,
            'new_domain' => $this->restaurant_domain,
            'old_domain' => $old_domain
        ])
            ->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->name])
            //->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('Store Domain Updated');

        $agents = $this->getAgentAssignments()
            //->andWhere(['email_notification' => true])
            ->all();

        foreach ($agents as $agentAssignment) {
            try {
                $mailer->setTo($agentAssignment->agent->agent_email)
                    ->send();
            } catch (Swift_TransportException $e) {
                Yii::error($e->getMessage(), "email");
            }
        }

        if ($this->restaurant_email_notification && $this->restaurant_email) {
            try {
                $mailer->setTo($this->restaurant_email)
                    ->send();
            } catch (Swift_TransportException $e) {
                Yii::error($e->getMessage(), "email");
            }
        }

        return self::message("success", "Congratulations you have successfully changed your domain name");
    }

    /**
     * @return void
     */
    public function checkOnboardCompleted()
    {
        $itemCount = \common\models\Item::find()
            ->andWhere(['restaurant_uuid' => $this->restaurant_uuid])
            ->count();

        $pmCount = RestaurantPaymentMethod::find()
            ->andWhere(['restaurant_uuid' => $this->restaurant_uuid])
            ->count();

        /*$blCount  = BusinessLocation::find()
            ->andWhere(['restaurant_uuid' => $this->restaurant_uuid])
            ->count();*/

        $supportPickUp = BusinessLocation::find()
            ->andWhere(['restaurant_uuid' => $this->restaurant_uuid, 'support_pick_up' => 1])
            ->count();

        $dzCount = DeliveryZone::find()
            ->andWhere(['restaurant_uuid' => $this->restaurant_uuid])
            ->count();

        if($itemCount > 0 && $pmCount > 0 && ($supportPickUp || $dzCount > 0)) {
            Yii::$app->eventManager->track('Onboard Complete', [
            ], null, $this->restaurant_uuid);

            Yii::$app->eventManager->track('Store Setup Completed', [
            ], null, $this->restaurant_uuid);
        }
    }

    /**
     * Get Agent Assignment Records
     * @return ActiveQuery
     */
    public function getAgentAssignments($modelClass = "\common\models\AgentAssignment")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->with('agent');
    }

    public function alertInActive()
    {
        $mailer = Yii::$app->mailer->compose([
            'html' => 'store/in-active'
        ])
            ->setFrom([Yii::$app->params['noReplyEmail']])
            ->setSubject("We miss you!");

        $agents = $this->getAgentAssignments()
            //->andWhere(['email_notification' => true])
            ->all();

        foreach ($agents as $agentAssignment) {
            try {
                $mailer->setTo($agentAssignment->agent->agent_email)
                    ->send();
            } catch (Swift_TransportException $e) {
                Yii::error($e->getMessage(), "email");
            }
        }

        if ($this->restaurant_email_notification && $this->restaurant_email) {
            try {
                $mailer->setTo($this->restaurant_email)
                    ->send();
            } catch (Swift_TransportException $e) {
                Yii::error($e->getMessage(), "email");
            }
        }

        //mark store as notified

        $this->warned_delete_at = date('Y-m-d');
        $this->save(false);
    }

    /**
     * send campaign message
     * @param $campaign
     * @return void
     */
    public function sendVendorEmailTemplate($campaign)
    {

        $html = $campaign->template->message
            . '<img src="' . Yii::$app->agentApiUrlManager->baseUrl . '/v1/store/log-email-campaign/'
            . $campaign->campaign_uuid . '" />';

        $mailer = Yii::$app->mailer->compose()
            ->setHtmlBody($html)
            ->setFrom([Yii::$app->params['noReplyEmail']])
            ->setSubject($campaign->template->subject);

        $agents = $this->getAgentAssignments()
            //->andWhere(['email_notification' => true])
            ->all();

        foreach ($agents as $agentAssignment) {
            try {
                $mailer->setTo($agentAssignment->agent->agent_email)
                    ->send();
            } catch (Swift_TransportException $e) {
                Yii::error($e->getMessage(), "email");
            }
        }

        if ($this->restaurant_email_notification && $this->restaurant_email) {
            try {
                $mailer->setTo($agentAssignment->agent->agent_email)
                    ->send();
            } catch (Swift_TransportException $e) {
                Yii::error($e->getMessage(), "email");
            }
        }
    }

    /**
     * Create an account for vendor on MyFatoorah
     */
    public function createMyFatoorahAccount()
    {
        //Create  supplier for a vendor on MyFatoorah
        Yii::$app->myFatoorahPayment->setApiKeys($this->currency->code);

        $response = Yii::$app->myFatoorahPayment->createSupplier($this);

        $supplierApiResponse = json_decode($response->content);

        if ($supplierApiResponse->IsSuccess) {

            $this->supplierCode = $supplierApiResponse->Data->SupplierCode;

            Yii::info($this->name . " has just created MyFatooraha account", __METHOD__);

            if ($this->supplierCode) {
                $this->is_myfatoorah_enable = 1;
                //$this->is_tap_enable = 0;
            } else {
                $this->is_myfatoorah_enable = 0;
            }

            if ($this->save()) {
                // //Upload documents file on our server before we create an account on MyFatoorah we gonaa delete them
                $this->uploadDocumentsToMyFatoorah();
            }

            $this->onMyFatoorahCreated();

            return [
                "operation" => 'success',
            ];

        } else {

            Yii::error('Error while create supplier [' . $this->name . '] ' . json_encode($supplierApiResponse));

            return [
                "operation" => 'error',
                "message" => $supplierApiResponse
            ];
        }
    }

    public function uploadDocumentsToMyFatoorah()
    {

        //Upload Authorized Signature file
        if ($this->authorized_signature_file) {

            $tmpFile = sys_get_temp_dir() . '/' . $this->authorized_signature_file;

            if (!file_put_contents($tmpFile, file_get_contents($this->getAuthorizedSignaturePhoto())))
                return Yii::error('Error reading authorized signature document: ');

            Yii::$app->myFatoorahPayment->setApiKeys($this->currency->code);

            $response = Yii::$app->myFatoorahPayment->uploadSupplierDocument($tmpFile, 2, $this->supplierCode); //Upload Signature file

            $responseContent = json_decode($response->content);

            @unlink($tmpFile);


            if (!$response->isOk || ($responseContent && !$responseContent->IsSuccess)) {
                $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ?
                    json_encode($responseContent->ValidationErrors) : $responseContent->Message;
                return Yii::error('Error when uploading authorized signature document: ' . $errorMessage);
            }

        }

        //Upload commercial_license file
        if ($this->commercial_license_file) {

            $commercialLicenseTmpFile = sys_get_temp_dir() . '/' . $this->commercial_license_file;

            if (!file_put_contents($commercialLicenseTmpFile, file_get_contents($this->getCommercialLicensePhoto())))
                return Yii::error('Error reading commercial license document: ');

            Yii::$app->myFatoorahPayment->setApiKeys($this->currency->code);
            $response = Yii::$app->myFatoorahPayment->uploadSupplierDocument($commercialLicenseTmpFile, 1, $this->supplierCode); //Upload commercial License

            $responseContent = json_decode($response->content);

            @unlink($commercialLicenseTmpFile);


            if (!$response->isOk || ($responseContent && !$responseContent->IsSuccess)) {
                $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ? json_encode($responseContent->ValidationErrors) : $responseContent->Message;
                return Yii::error('Error when uploading commercial license document: ' . $errorMessage);
            }

        }

        //Upload Owner civil id front side
        if ($this->identification_file_front_side) {

            $civilIdFrontSideTmpFile = sys_get_temp_dir() . '/' . $this->identification_file_front_side;

            if (!file_put_contents($civilIdFrontSideTmpFile, file_get_contents($this->getCivilIdFrontSidePhoto())))
                return Yii::error('Error reading civil id (front side): ');

            Yii::$app->myFatoorahPayment->setApiKeys($this->currency->code);
            $response = Yii::$app->myFatoorahPayment->uploadSupplierDocument($civilIdFrontSideTmpFile, 4, $this->supplierCode); //Upload civil Id Front Side

            $responseContent = json_decode($response->content);

            @unlink($civilIdFrontSideTmpFile);

            if (!$response->isOk || ($responseContent && !$responseContent->IsSuccess)) {
                $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ? json_encode($responseContent->ValidationErrors) : $responseContent->Message;
                return Yii::error('Error when uploading civil id (front side): ' . $errorMessage);
            }
        }

        //Upload Owner civil id back side
        if ($this->identification_file_back_side) {
            $civilIdBackSideTmpFile = sys_get_temp_dir() . '/' . $this->identification_file_back_side;

            if (!file_put_contents($civilIdBackSideTmpFile, file_get_contents($this->getCivilIdBackSidePhoto())))
                return Yii::error('Error reading civil id (back side): ');

            Yii::$app->myFatoorahPayment->setApiKeys($this->currency->code);

            $response = Yii::$app->myFatoorahPayment->uploadSupplierDocument($civilIdBackSideTmpFile, 5, $this->supplierCode); //Upload civil Id back Side

            $responseContent = json_decode($response->content);

            @unlink($civilIdBackSideTmpFile);

            if (!$response->isOk || ($responseContent && !$responseContent->IsSuccess)) {
                $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ? json_encode($responseContent->ValidationErrors) : $responseContent->Message;
                return Yii::error('Error when uploading civil id (back side): ' . $errorMessage);
            }
        }
    }

    /**
     * Return authorized_signature_file url
     * @return string
     */
    public function getAuthorizedSignaturePhoto()
    {
        if (!$this->authorized_signature_file) {
            return false;
        }

        return 'https://res.cloudinary.com/plugn/image/upload/restaurants/'
            . $this->restaurant_uuid . '/private_documents/'
            . $this->authorized_signature_file;
    }

    /**
     * Return commercial_license_file
     * @return string
     */
    public function getCommercialLicensePhoto()
    {
        $photo_url = [];

        if ($this->commercial_license_file) {

            $url = 'https://res.cloudinary.com/plugn/image/upload/restaurants/'
                . $this->restaurant_uuid . '/private_documents/'
                . $this->commercial_license_file;
            $photo_url = $url;
        }

        return $photo_url;
    }

    /**
     * Return Civil id front side url
     * @return string
     */
    public function getCivilIdFrontSidePhoto()
    {
        $photo_url = [];

        if ($this->identification_file_front_side) {

            $url = 'https://res.cloudinary.com/plugn/image/upload/restaurants/'
                . $this->restaurant_uuid . '/private_documents/'
                . $this->identification_file_front_side;
            $photo_url = $url;
        }

        return $photo_url;
    }

    /**
     * Return Civil id back side url
     * @return string
     */
    public function getCivilIdBackSidePhoto()
    {
        $photo_url = [];

        if ($this->identification_file_back_side) {

            $url = 'https://res.cloudinary.com/plugn/image/upload/restaurants/'
                . $this->restaurant_uuid . '/private_documents/'
                . $this->identification_file_back_side;
            $photo_url = $url;
        }

        return $photo_url;
    }

    public function onMyFatoorahCreated()
    {
        $paymentGateway = 'MyFatoorah';

        $subject = 'Your ' . $paymentGateway . ' account data has been created';

        $mailer = Yii::$app->mailer->compose([
            'html' => 'payment-gateway-created',
        ], [
            'store' => $this,
            'paymentGateway' => 'MyFatoorah',
        ])
            ->setFrom([Yii::$app->params['noReplyEmail'] => 'Plugn'])
            ->setTo([$this->restaurant_email])
            ->setSubject($subject);

        try {
            $mailer->send();
        } catch (Swift_TransportException $e) {
            Yii::error($e->getMessage(), "email");
        }
    }

    /**
     * Create an account for vendor on tap
     */
    public function createTapAccount($forceCreate = false)
    {
        //Upload documents file on our server before we create an account on tap we gonaa delete them

        $response = $this->uploadDocumentsToTap();

        if ($response['operation'] == "error") {

            Yii::error('Error while uploading doc for Business [' . $this->name . '] ' . json_encode($response));

            return $response;
        }

        //Create a business for a vendor on Tap if not already exists

        if ($forceCreate || (!$this->business_id || !$this->business_entity_id || !$this->developer_id)) {

            $response = $this->createBusiness();

            if($response["operation"] == 'error') {
                return $response;
            }
        }

        //Create a merchant on Tap if not already added

        if ($forceCreate || (!$this->merchant_id || !$this->wallet_id)) {

            $response = $this->createMerchant();

            if($response["operation"] == 'error') {
                return $response;
            }
        }

        //Create an Operator

        if ($this->developer_id) {

            $response = $this->createAnOperator();

            if($response["operation"] == 'error') {
                return $response;
            }
        } else {
            //if store logo not available, operator, developer id etc,... would be blank, so notify vendor +
            // fetch api keys from merchant detail api from cron jobs
            $this->onTapCreated();
        }

        Yii::info($this->name . " has just created TAP account", __METHOD__);

        return [
            "operation" => 'success',
            "message" => "Account created successfully!"
        ];
    }

    public function createBusiness()
    {
        $businessApiResponse = Yii::$app->tapPayments->createBussiness($this);

        //&& isset($businessApiResponse->data['entity']['operator'])
        if ($businessApiResponse->isOk) {

            //Yii::info('Create Business [' . $this->name . '] ' . json_encode($businessApiResponse->data));

            $this->business_id = $businessApiResponse->data['id'];
            $this->business_entity_id = $businessApiResponse->data['entity']['id'];

            if (isset($businessApiResponse->data['entity']['operator']))
                $this->developer_id = $businessApiResponse->data['entity']['operator']['developer_id'];

            if($businessApiResponse->data['status'] === 'Active') {
                $this->is_tap_business_active = 1;
            } else {
                $this->is_tap_business_active = 0;
            }

            self::updateAll([
                'business_id' => $this->business_id,
                'business_entity_id' => $this->business_entity_id,
                'developer_id' => $this->developer_id,
                'is_tap_business_active' => $this->is_tap_business_active
            ], [
                'restaurant_uuid' => $this->restaurant_uuid
            ]);

            return [
                "operation" => 'success',
                "message" => "Business created successfully!"
            ];

        } else {

            Yii::error('Error while create Business [' . $this->name . '] ' . json_encode($businessApiResponse->data));

            if (isset(Yii::$app->session->id)) {
                Yii::$app->session->setFlash('errorResponse', json_encode($businessApiResponse->data));
            }

            $this->addError('business_id', json_encode($businessApiResponse->data));

            return [
                "operation" => 'error',
                "message" => $businessApiResponse->data
            ];
        }
    }

    /**
     * @return array|string[]
     */
    public function createMerchant()
    {
        $merchantApiResponse = Yii::$app->tapPayments->createMerchantAccount(
            $this->company_name . '-' . $this->business_id,
            $this->currency->code,
            $this->business_id,
            $this->business_entity_id,
            $this->iban,
            $this
        );

        if ($merchantApiResponse->isOk) {

            $this->merchant_id = $merchantApiResponse->data['id'];
            $this->wallet_id = $merchantApiResponse->data['wallets']['id'];

            //todo: check status on merchant create api, should be "New Pending Approval"

            /*if($this->country && $this->country->iso == "SA") {
                if (
                    $merchantApiResponse->data['is_acceptance_allowed'] &&
                    $merchantApiResponse->data['is_payout_allowed']
                ) {
                    $this->tap_merchant_status = $merchantApiResponse->data['status'];
                } else if(!$merchantApiResponse->data['is_acceptance_allowed']) {
                    $this->tap_merchant_status = "Acceptance not allowed";
                } else {
                    $this->tap_merchant_status = "Payout not allowed";
                }
            } else {
                $this->tap_merchant_status = $merchantApiResponse->data['status'];
            }*/

            // setting manually as detail vs create api showing different status,
            // create showing Active but detail showing "New Pending Approval"
            $this->tap_merchant_status = "New Pending Approval";

            self::updateAll([
                'merchant_id' => $this->merchant_id,
                'wallet_id' => $this->wallet_id,
                'tap_merchant_status' => $this->tap_merchant_status
            ], [
                'restaurant_uuid' => $this->restaurant_uuid
            ]);

            return [
                "operation" => 'success',
                "message" => "Merchant created successfully!"
            ];

        } else {

            Yii::error('Error while create Merchant [' . $this->name . '] ' . json_encode($merchantApiResponse->data));

            if (isset(Yii::$app->session->id))
                Yii::$app->session->setFlash('errorResponse', json_encode($merchantApiResponse->data));

            $this->addError('merchant_id', json_encode($merchantApiResponse->data));

            return [
                "operation" => 'error',
                "message" => $merchantApiResponse->data
            ];
        }
    }

    /**
     * fetch merchant details and set api keys
     * @return array|string[]
     */
    public function fetchMerchant($notifyVendor = true) {

        $merchantApiResponse = Yii::$app->tapPayments->fetchMerchant(
            $this->merchant_id
        );

        if ($merchantApiResponse->isOk && isset($merchantApiResponse->data['operator'])) {

            if($this->country && $this->country->iso == "SA") {
                if (
                    $merchantApiResponse->data['is_acceptance_allowed'] &&
                    $merchantApiResponse->data['is_payout_allowed']
                ) {
                    $this->tap_merchant_status = $merchantApiResponse->data['status'];
                } else if(!$merchantApiResponse->data['is_acceptance_allowed']) {
                    $this->tap_merchant_status = "Acceptance not allowed";
                } else {
                    $this->tap_merchant_status = "Payout not allowed";
                }
            } else {
                $this->tap_merchant_status = $merchantApiResponse->data['status'];
            }

            //todo: notify vendor + show to admin + slack if status not active

            if(!$this->wallet_id)
                $this->wallet_id = $merchantApiResponse->data['operator']['wallet_id'];

            $this->developer_id = $merchantApiResponse->data['operator']['developer_id'];

            $this->operator_id = $merchantApiResponse->data['operator']['id'];
            $this->test_api_key = $merchantApiResponse->data['operator']['api_credentials']['test']['secret'];
            $this->test_public_key = $merchantApiResponse->data['operator']['api_credentials']['test']['public'];

            if (array_key_exists('live', $merchantApiResponse->data['operator']['api_credentials'])) {
                $this->live_api_key = $merchantApiResponse->data['operator']['api_credentials']['live']['secret'];
                $this->live_public_key = $merchantApiResponse->data['operator']['api_credentials']['live']['public'];
            }

            //sandbox mode will give only test api keys

            if ($this->live_api_key || $this->test_api_key) {
                //$this->is_tap_enable = 1;
                $this->is_tap_created = 1;
                $this->is_myfatoorah_enable = 0;
            } else {
                $this->is_tap_created = 0;
                //$this->is_tap_enable = 0;
            }

            if (
                $this->is_tap_created &&
                $this->is_tap_business_active &&
                $this->tap_merchant_status == "Active"
            ) {
                $this->is_tap_enable = 1;
            } else {
                $this->is_tap_enable = 0;
            }

            self::updateAll([
                'tap_merchant_status' => $this->tap_merchant_status,
                'business_id' => $this->business_id,
                'business_entity_id' => $this->business_entity_id,
                'developer_id' => $this->developer_id,
                'merchant_id' => $this->merchant_id,
                'wallet_id' => $this->wallet_id,
                'operator_id' => $this->operator_id,
                'test_api_key' => $this->test_api_key,
                'test_public_key' => $this->test_public_key,
                'live_api_key' => $this->live_api_key,
                'live_public_key' => $this->live_public_key,
                'is_tap_enable' => $this->is_tap_enable,
                'is_tap_created' => $this->is_tap_created,
                'is_myfatoorah_enable' => $this->is_myfatoorah_enable
            ], [
                'restaurant_uuid' => $this->restaurant_uuid
            ]);

            if ($this->is_tap_created) {

                if ($this->is_tap_enable) {
                    $this->onTapApproved($notifyVendor);
                } else {
                    //$this->onTapCreated();

                    //remove tap payment methods

                    $paymentMethods = $this->getRestaurantPaymentMethods()->all();

                    foreach ($paymentMethods as $paymentMethod) {
                        $paymentMethod->delete();
                    }
                }
            }

            return [
                "operation" => 'success',
                "tap_merchant_status" => $this->tap_merchant_status,
                "message" => "Merchant status is: " . $merchantApiResponse->data['status']
            ];

        } else {

            Yii::error('Error while Fetching Merchant  [' . $this->name . '] ' . json_encode($merchantApiResponse->data));

            if (isset(Yii::$app->session->id))
                Yii::$app->session->setFlash('errorResponse', json_encode($merchantApiResponse->data));

            $this->addError('operator_id', json_encode($merchantApiResponse->data));

            self::updateAll([
                'business_id' => $this->business_id,
                'business_entity_id' => $this->business_entity_id,
                'developer_id' => $this->developer_id,
                'merchant_id' => $this->merchant_id,
                'wallet_id' => $this->wallet_id,
                'operator_id' => $this->operator_id,
                'test_api_key' => $this->test_api_key,
                'test_public_key' => $this->test_public_key,
                'live_api_key' => $this->live_api_key,
                'live_public_key' => $this->live_public_key,
                'is_tap_enable' => $this->is_tap_enable,
                'is_myfatoorah_enable' => $this->is_myfatoorah_enable
            ], [
                'restaurant_uuid' => $this->restaurant_uuid
            ]);

            return [
                "operation" => 'error',
                "message" => $merchantApiResponse->data
            ];
        }
    }

    /**
     * @return array|string[]
     */
    public function createAnOperator()
    {
        $operatorApiResponse = Yii::$app->tapPayments->createAnOperator(
            $this->name,
            $this->wallet_id,
            $this->developer_id,
            $this
        );

        if ($operatorApiResponse->isOk) {

            $this->operator_id = $operatorApiResponse->data['id'];
            $this->test_api_key = $operatorApiResponse->data['api_credentials']['test']['secret'];
            $this->test_public_key = $operatorApiResponse->data['api_credentials']['test']['public'];

            if (array_key_exists('live', $operatorApiResponse->data['api_credentials'])) {
                $this->live_api_key = $operatorApiResponse->data['api_credentials']['live']['secret'];
                $this->live_public_key = $operatorApiResponse->data['api_credentials']['live']['public'];
            }

            //sandbox mode will give only test api keys

            if ($this->live_api_key || $this->test_api_key) {
                //$this->is_tap_enable = 1;
                $this->is_tap_created = 1;
                $this->is_myfatoorah_enable = 0;
            } else {
                $this->is_tap_created = 0;
                //$this->is_tap_enable = 0;
            }

            if ($this->is_tap_created && $this->is_tap_business_active && $this->tap_merchant_status == "Active") {
                $this->is_tap_enable = 1;
            }

            self::updateAll([
                'business_id' => $this->business_id,
                'business_entity_id' => $this->business_entity_id,
                'developer_id' => $this->developer_id,
                'merchant_id' => $this->merchant_id,
                'wallet_id' => $this->wallet_id,
                'operator_id' => $this->operator_id,
                'test_api_key' => $this->test_api_key,
                'test_public_key' => $this->test_public_key,
                'live_api_key' => $this->live_api_key,
                'live_public_key' => $this->live_public_key,
                'is_tap_enable' => $this->is_tap_enable,
                'is_tap_created' => $this->is_tap_created,
                'is_myfatoorah_enable' => $this->is_myfatoorah_enable
            ], [
                'restaurant_uuid' => $this->restaurant_uuid
            ]);

            if ($this->is_tap_created) {

                if ($this->is_tap_enable) {
                    $this->onTapApproved();
                } else {
                    $this->onTapCreated();
                }
            }

            return [
                "operation" => 'success',
                "message" => "Account created successfully!"
            ];

        } else {

            Yii::error('Error while create Operator  [' . $this->name . '] ' . json_encode($operatorApiResponse->data));

            if (isset(Yii::$app->session->id))
                Yii::$app->session->setFlash('errorResponse', json_encode($operatorApiResponse->data));

            $this->addError('operator_id', json_encode($operatorApiResponse->data));

            self::updateAll([
                'business_id' => $this->business_id,
                'business_entity_id' => $this->business_entity_id,
                'developer_id' => $this->developer_id,
                'merchant_id' => $this->merchant_id,
                'wallet_id' => $this->wallet_id,
                'operator_id' => $this->operator_id,
                'test_api_key' => $this->test_api_key,
                'test_public_key' => $this->test_public_key,
                'live_api_key' => $this->live_api_key,
                'live_public_key' => $this->live_public_key,
                'is_tap_enable' => $this->is_tap_enable,
                'is_myfatoorah_enable' => $this->is_myfatoorah_enable
            ], [
                'restaurant_uuid' => $this->restaurant_uuid
            ]);

            return [
                "operation" => 'error',
                "message" => $operatorApiResponse->data
            ];
        }
    }

    /**
     * move documents to tap when vendor upload
     * @return void
     */
    public function uploadDocumentsToTap()
    {
        if (!$this->authorized_signature_title) {
            $this->authorized_signature_title = 'Authorized Signature';
        }

        if (!$this->authorized_signature_file_purpose) {
            $this->authorized_signature_file_purpose = 'customer_signature';
        }

        if (!$this->commercial_license_title) {
            $this->commercial_license_title = 'Commercial License';
        }

        if (!$this->commercial_license_file_purpose) {
            $this->commercial_license_file_purpose = 'customer_signature';
        }

        if (!$this->identification_file_purpose) {
            $this->identification_file_purpose = 'identity_document';
        }

        if (!$this->identification_title) {
            $this->identification_title = "Owner's civil id";
        }

        if ($this->logo && !$this->logo_file_id) {

            $tmpFile = sys_get_temp_dir() . '/' . $this->logo;

            if (
                !file_put_contents(
                    $tmpFile,
                    file_get_contents($this->getRestaurantLogoUrl())
                )
            ) {
                //Yii::error ('Error reading authorized signature document: ');

                $this->addError('logo_file_id', 'Error reading logo');

                return [
                    "operation" => 'error',
                    "message" => 'Error reading logo'
                ];
            }

            $response = Yii::$app->tapPayments->uploadFileToTap(
                $tmpFile,
                "identity_document",
                "Business Logo"
            );

            @unlink($tmpFile);

            if ($response->isOk) {
                $this->logo_file_id = $response->data['id'];
            } else {
                try {
                    $error = is_object($response->data) || is_array($response->data) ? print_r($response->data, true) : $response->data;
                } catch (\Exception $e) {
                    $error = "500 error from server";
                }

                //Yii::error ('Error when uploading IBAN document: ' . $error);

                $this->addError('logo_file_id', 'Error reading logo' . $error);

                return [
                    "operation" => 'error',
                    "message" => 'Error reading logo' . $error
                ];
            }
        }

        //IBAN Certificate

        if (
            $this->iban_certificate_file && !$this->iban_certificate_file_id
        ) {
            $tmpFile = sys_get_temp_dir() . '/' . $this->iban_certificate_file;

            if (!file_put_contents($tmpFile, file_get_contents($this->getIBANCertificatePhoto()))) {
                //Yii::error ('Error reading authorized signature document: ');

                $this->addError('iban_certificate_file', 'Error reading IBAN certificate document');

                return [
                    "operation" => 'error',
                    "message" => 'Error reading IBAN certificate document'
                ];
            }

            $response = Yii::$app->tapPayments->uploadFileToTap(
                $tmpFile,
                "identity_document",
                "IBAN Certificate",
                [
                    "issuing_country" => $this->country->iso
                ]
            );

            @unlink($tmpFile);

            if ($response->isOk) {
                $this->iban_certificate_file_id = $response->data['id'];
            } else {
                try {
                    $error = is_object($response->data) || is_array($response->data) ? print_r($response->data, true) : $response->data;
                } catch (\Exception $e) {
                    $error = "500 error from server";
                }

                //Yii::error ('Error when uploading IBAN document: ' . $error);

                $this->addError('iban_certificate_file', 'Error reading IBAN document' . $error);

                return [
                    "operation" => 'error',
                    "message" => 'Error reading IBAN document' . $error
                ];
            }
        }

        //Upload Authorized Signature file

        if (
            $this->authorized_signature_file &&
            $this->authorized_signature_file_purpose &&
            $this->authorized_signature_title &&
            !$this->authorized_signature_file_id
        ) {
            $tmpFile = sys_get_temp_dir() . '/' . $this->authorized_signature_file;

            if (!file_put_contents($tmpFile, file_get_contents($this->getAuthorizedSignaturePhoto()))) {
                //Yii::error ('Error reading authorized signature document: ');

                $this->addError('authorized_signature_file', 'Error reading authorized signature document');

                return [
                    "operation" => 'error',
                    "message" => 'Error reading authorized signature document'
                ];
            }

            $response = Yii::$app->tapPayments->uploadFileToTap(
                $tmpFile,
                $this->authorized_signature_file_purpose,
                $this->authorized_signature_title
            );

            @unlink($tmpFile);

            if ($response->isOk) {
                $this->authorized_signature_file_id = $response->data['id'];
            } else {
                try {
                    $error = is_object($response->data) || is_array($response->data) ? print_r($response->data, true) : $response->data;
                } catch (\Exception $e) {
                    $error = "500 error from server";
                }

                //Yii::error ('Error when uploading authorized signature document: ' . $error);

                $this->addError('authorized_signature_file', 'Error reading authorized signature document' . $error);

                return [
                    "operation" => 'error',
                    "message" => 'Error reading authorized signature document' . $error
                ];
            }
        }

        //Upload commercial_license file

        if (
            $this->commercial_license_file &&
            $this->commercial_license_file_purpose &&
            $this->commercial_license_title &&
            !$this->commercial_license_file_id
        ) {

            $commercialLicenseTmpFile = sys_get_temp_dir() . '/' . $this->commercial_license_file;

            if (!file_put_contents($commercialLicenseTmpFile, file_get_contents($this->getCommercialLicensePhoto()))) {
                //Yii::error ('Error reading commercial license document: ');

                $this->addError('commercial_license_file', 'Error reading commercial license document');

                return [
                    "operation" => 'error',
                    "message" => 'Error when uploading commercial license document'
                ];
            }

            $response = Yii::$app->tapPayments->uploadFileToTap(
                $commercialLicenseTmpFile, $this->commercial_license_file_purpose, $this->commercial_license_title);

            @unlink($commercialLicenseTmpFile);

            if ($response->isOk) {
                $this->commercial_license_file_id = $response->data['id'];
            } else {
                try {
                    $error = is_object($response->data) || is_array($response->data) ? print_r($response->data, true) : $response->data;
                } catch (\Exception $e) {
                    $error = "500 error from server";
                }

                //Yii::error ('Error when uploading commercial license document: ' . print_r ($response->data, true));

                $this->addError('commercial_license_file', 'Error when uploading commercial license document: ' . $error);

                return [
                    "operation" => 'error',
                    "message" => 'Error when uploading commercial license document: ' . $error
                ];
            }
        }

        //Upload Owner civil id front side

        if ($this->identification_file_front_side &&
            $this->identification_file_purpose &&
            $this->identification_title &&
            !$this->identification_file_id_front_side
        ) {

            $civilIdFrontSideTmpFile = sys_get_temp_dir() . '/' . $this->identification_file_front_side;

            if (!file_put_contents($civilIdFrontSideTmpFile, file_get_contents($this->getCivilIdFrontSidePhoto()))) {
                //Yii::error ('Error reading civil id (front side): ');

                $this->addError('identification_file_front_side', 'Error reading civil id (front side)');

                return [
                    "operation" => 'error',
                    "message" => 'Error when uploading civil id (front side)'
                ];
            }

            $response = Yii::$app->tapPayments->uploadFileToTap(
                $civilIdFrontSideTmpFile, $this->identification_file_purpose, $this->identification_title);

            @unlink($civilIdFrontSideTmpFile);

            if ($response->isOk) {
                $this->identification_file_id_front_side = $response->data['id'];
            } else {
                try {
                    $error = is_object($response->data) || is_array($response->data) ? print_r($response->data, true) : $response->data;
                } catch (\Exception $e) {
                    $error = "500 error from server";
                }

                $this->addError('identification_file_front_side', 'Error when uploading civil id (front side): ' . $error);

                //Yii::error ('Error when uploading civil id (front side): ' . print_r($response->data, true));

                return [
                    "operation" => 'error',
                    "message" => 'Error when uploading civil id (front side): ' . $error
                ];
            }

        }

        //Upload Owner civil id back side

        if ($this->identification_file_back_side &&
            $this->identification_file_purpose &&
            $this->identification_title &&
            !$this->identification_file_id_back_side
        ) {

            $civilIdBackSideTmpFile = sys_get_temp_dir() . '/' . $this->identification_file_back_side;

            if (!file_put_contents($civilIdBackSideTmpFile, file_get_contents($this->getCivilIdBackSidePhoto()))) {
                //Yii::error ('Error reading civil id (back side): ');

                $this->addError('identification_file_back_side', 'Error reading civil id (back side)');

                return [
                    "operation" => 'error',
                    "message" => 'Error when uploading civil id (back side)'
                ];
            }

            $response = Yii::$app->tapPayments->uploadFileToTap(
                $civilIdBackSideTmpFile, $this->identification_file_purpose, $this->identification_title);

            @unlink($civilIdBackSideTmpFile);

            if ($response->isOk) {
                $this->identification_file_id_back_side = $response->data['id'];
            } else {
                try {
                    $error = is_object($response->data) || is_array($response->data) ? print_r($response->data, true) : $response->data;
                } catch (\Exception $e) {
                    $error = "500 error from server";
                }

                $this->addError('identification_file_back_side', 'Error when uploading civil id (back side): ' . $error);

                //Yii::error ('Error when uploading civil id (back side): ' . print_r ($response->data, true));

                return [
                    "operation" => 'error',
                    "message" => 'Error when uploading civil id (back side): ' . $error
                ];
            }
        }

        self::updateAll([
            'iban_certificate_file_id' => $this->iban_certificate_file_id,
            'authorized_signature_file_id' => $this->authorized_signature_file_id,
            'logo_file_id' => $this->logo_file_id,
            'commercial_license_file_id' => $this->commercial_license_file_id,
            'identification_file_id_front_side' => $this->identification_file_id_front_side,
            'identification_file_id_back_side' => $this->identification_file_id_back_side
        ], [
            'restaurant_uuid' => $this->restaurant_uuid
        ]);

        return [
            "operation" => 'success',
        ];
    }

    /**
     * Return Restaurant's logo url
     */
    public function getRestaurantLogoUrl()
    {
        if ($this->logo)
            return 'https://res.cloudinary.com/plugn/image/upload/c_scale,h_105,w_105/restaurants/' . $this->restaurant_uuid . "/logo/" . $this->logo;
        else
            return 'https://res.cloudinary.com/plugn/image/upload/c_scale,h_105,w_105/plugn-icon.png';
    }

    /**
     * Return authorized_signature_file url
     * @return string
     */
    public function getIBANCertificatePhoto()
    {
        if (!$this->iban_certificate_file) {
            return false;
        }

        return 'https://res.cloudinary.com/plugn/image/upload/restaurants/'
            . $this->restaurant_uuid . '/private_documents/'
            . $this->iban_certificate_file;
    }

    public function onTapApproved($notifyVendor = true)
    {
        if($notifyVendor) {
            $this->notifyTapApproved();
        }

        $this->enableTapGateways();
    }

    public function notifyTapApproved()
    {
        $paymentGateway = 'Tap Payments';

        $subject = 'Your ' . $paymentGateway . ' account data has been approved';

        $mailer = Yii::$app->mailer->compose([
            'html' => 'agent/tap-approved',
        ], [
            'store' => $this,
            'paymentGateway' => $paymentGateway,
        ])
            ->setFrom([Yii::$app->params['noReplyEmail'] => 'Plugn'])
            ->setTo([$this->restaurant_email])
            ->setSubject($subject);

        try {
            $mailer->send();
        } catch (Swift_TransportException $e) {
            Yii::error($e->getMessage(), "email");
        }
    }

    public function notifyTapRejected($status)
    {
        $paymentGateway = 'Tap Payment';

        $subject = 'Your ' . $paymentGateway . ' account status is ' . $status;

        $mailer = Yii::$app->mailer->compose([
            'html' => 'agent/tap-rejected',
        ], [
            'store' => $this,
            'status' => $status,
            'paymentGateway' => $paymentGateway,
        ])
            ->setFrom([Yii::$app->params['noReplyEmail'] => 'Plugn'])
            ->setTo([$this->restaurant_email])
            ->setCc([Yii::$app->params['supportEmail'] => 'Plugn'])
            ->setSubject($subject);

        try {
            $mailer->send();
        } catch (Swift_TransportException $e) {
            Yii::error($e->getMessage(), "email");
        }
    }

    /**
     * enable tap payment gateways from tap
     * @return void
     */
    public function enableTapGateways()
    {
        $subQuery = $this->getRestaurantPaymentMethods()
            ->select('payment_method_id');

        $paymentGateways = \common\models\PaymentMethod::find()
            ->andWhere(['LIKE', 'source_id', "src_"])
            ->andWhere(new Expression("source_id IS NOT NULL"))
            ->andWhere(['NOT IN', 'payment_method_id', $subQuery])
            ->all();

        foreach ($paymentGateways as $paymentGateway) {
            $model = new RestaurantPaymentMethod();
            $model->payment_method_id = $paymentGateway->payment_method_id;
            $model->restaurant_uuid = $this->restaurant_uuid;
            $model->status = RestaurantPaymentMethod::STATUS_ACTIVE;
            $model->save();
        }
    }

    /**
     * Gets query for [[RestaurantPaymentMethods]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantPaymentMethods($modelClass = "\common\models\RestaurantPaymentMethod")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->andWhere(['status' => RestaurantPaymentMethod::STATUS_ACTIVE]);
    }

    public function onTapCreated()
    {
        $subject = 'Your Tap account has been created';

        $mailer = Yii::$app->mailer->compose([
            'html' => 'payment-gateway-created',
        ], [
            'store' => $this,
            'paymentGateway' => 'Tap',
        ])
            ->setFrom([Yii::$app->params['noReplyEmail'] => 'Plugn'])
            ->setTo([$this->restaurant_email])
            ->setSubject($subject);

        try {
            $mailer->send();
        } catch (Swift_TransportException $e) {
            Yii::error($e->getMessage(), "email");
        }
    }

    /**
     * @return void
     */
    public function pollTapStatus()
    {
        if(!$this->is_tap_business_active) {
            $this->pollTapBusinessStatus();
        }

        if($this->tap_merchant_status != "Active") {
            $this->fetchMerchant();
        }
    }

    public function pollTapBusinessStatus()
    {
        $businessApiResponse = Yii::$app->tapPayments->getBussiness($this);

        if ($businessApiResponse->isOk && $businessApiResponse->data['status'] === 'Active') {

           $this->is_tap_business_active = 1;

            self::updateAll(['is_tap_business_active' => $this->is_tap_business_active], [
                'restaurant_uuid' => $this->restaurant_uuid
            ]);
        }

        return $businessApiResponse->data;
    }

    /**
     * save restaurant payment method
     */
    public function saveRestaurantPaymentMethod($payments_method = null)
    {

        if ($payments_method) {

            $sotred_restaurant_payment_method = RestaurantPaymentMethod::find()
                ->andWhere(['restaurant_payment_method.restaurant_uuid' => $this->restaurant_uuid])
                ->all();

            foreach ($sotred_restaurant_payment_method as $restaurant_payment_method) {
                if (!in_array($restaurant_payment_method->payment_method_id, $payments_method)) {
                    RestaurantPaymentMethod::deleteAll(['restaurant_uuid' => $this->restaurant_uuid, 'payment_method_id' => $restaurant_payment_method->payment_method_id]);
                }
            }

            foreach ($payments_method as $payment_method_id) {
                $payments_method = new RestaurantPaymentMethod();
                $payments_method->payment_method_id = $payment_method_id;
                $payments_method->restaurant_uuid = $this->restaurant_uuid;
                $payments_method->save();
            }
        } else {
            RestaurantPaymentMethod::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
        }
    }

    /**
     * Return Restaurant's thumbnail image url
     */
    public function getRestaurantThumbnailImageUrl()
    {
        return 'https://res.cloudinary.com/plugn/image/upload/c_scale,w_600/restaurants/' . $this->restaurant_uuid . "/thumbnail-image/" . $this->thumbnail_image;
    }

    /**
     * Upload restaurant's logo  to cloudinary
     * @param type $imageURL
     */
    public function uploadLogo($imageURL, $saveInDb = true)
    {
        if($imageURL && !str_contains($imageURL, "https://")) {
            $imageURL = Yii::$app->temporaryBucketResourceManager->getUrl($imageURL);
        }

        $filename = Yii::$app->security->generateRandomString();

        try {
            //Delete old store's logo

            if ($this->logo) {
                $this->deleteRestaurantLogo();
            }

            if (!$imageURL) {
                return [
                    "operation" => "error",
                    "message" => "Image not provided"
                ];
                //return true;
            }

            $result = Yii::$app->cloudinaryManager->upload(
                $imageURL,
                [
                    'public_id' => "restaurants/" . $this->restaurant_uuid . "/logo/" . $filename
                ]
            );

            if ($result || count($result) > 0) {
                //todo: refactor
                $this->logo = basename($result['url']);

                if($saveInDb) {
                    if(!$this->save()) {
                        Yii::error("Error when uploading logo photos to Cloudinry: " . json_encode($this->errors));

                        return [
                            "operation" => "error",
                            "message" => $this->getErrors()
                        ];
                    }
                }
            }

            if (!str_contains($imageURL, 'amazonaws.com'))
                unlink($imageURL);

            return [
                "operation" => "success",
            ];

        } catch (Error $err) {
            Yii::error("Error when uploading logo photos to Cloudinry: " . json_encode($err));

            return [
                "operation" => "error",
                "message" => $err
            ];
        }
    }

    /**
     * Delete Restaurant's logo
     */
    public function deleteRestaurantLogo($logo = null)
    {
        if (!$logo)
            $logo = $this->logo;

        $imageURL = "restaurants/" . $this->restaurant_uuid . "/logo/" . $logo;

        try {
            Yii::$app->cloudinaryManager->delete($imageURL);

            $this->logo = null;

        } catch (Error $err) {
            Yii::error('Error while deleting logo photos to Cloudinry: ' . json_encode($err));
        }
    }

    // public function isOpen($asap = null) {
    //
    //     $restaurant = self::findOne(['restaurant_uuid'=>$this->restaurant_uuid]);
    //     $opening_hour_model = OpeningHour::find()
    //                             ->where(['restaurant_uuid' => $this->restaurant_uuid, 'day_of_week' => date('w', strtotime("now"))])
    //                             ->andWhere(['<=','open_at', date("H:i:s", strtotime("now"))])
    //                             ->andWhere(['>=','close_at', date("H:i:s", strtotime("now"))])
    //                             ->orderBy(['open_at' => SORT_ASC])
    //                             ->one();
    //
    //       if ($opening_hour_model && (
    //                date("w", strtotime("now")) == $opening_hour_model->day_of_week &&
    //                strtotime("now") > strtotime(date('c', strtotime($opening_hour_model->open_at, strtotime("now") ))) &&
    //                strtotime("now") <  strtotime(date('c', strtotime($opening_hour_model->close_at, strtotime("now") )) )
    //               ) && $restaurant->restaurant_status == self::RESTAURANT_STATUS_OPEN
    //       ) {
    //           return true;
    //       }
    //     return false;
    // }

    /**
     * Upload thumbnailImage  to cloudinary
     * @param type $imageURL
     */
    public function uploadThumbnailImage($imageURL, $saveInDb = true)
    {
        $filename = Yii::$app->security->generateRandomString();

        if($imageURL && !str_contains($imageURL, "https://")) {
            $imageURL = Yii::$app->temporaryBucketResourceManager->getUrl($imageURL);
        }

        try {
            //Delete old store's ThumbnailImage

            if ($this->thumbnail_image) {
                $this->deleteRestaurantThumbnailImage();
            }

            if (!$imageURL) {
                return [
                    "operation" => "error",
                    "message" => "Image not provided"
                ];
            }

            $result = Yii::$app->cloudinaryManager->upload(
                $imageURL, [
                    'public_id' => "restaurants/" . $this->restaurant_uuid . "/thumbnail-image/" . $filename
                ]
            );

            if ($result || count($result) > 0) {
                //todo: refactor
                $this->thumbnail_image = basename($result['url']);

                if($saveInDb) {
                    if(!$this->save()) {
                        Yii::error("Error when uploading thumbnail to Cloudinry: " . json_encode($this->errors));

                        return [
                            "operation" => "error",
                            "message" => $this->getErrors()
                        ];
                    }
                }
            }

            if (!str_contains($imageURL, 'amazonaws.com'))
                unlink($imageURL);

            return [
                "operation" => "success",
            ];

        } catch (Error $err) {

            Yii::error("Error when uploading thumbnail photos to Cloudinry: " . json_encode($err));

            return [
                "operation" => "error",
                "message" => $err
            ];
        }
    }

    /**
     * Delete Restaurant's Thumbnail Image
     */
    public function deleteRestaurantThumbnailImage($thumbnail_image = null)
    {

        if (!$thumbnail_image)
            $thumbnail_image = $this->thumbnail_image;

        $imageURL = "restaurants/" . $this->restaurant_uuid . "/thumbnail-image/" . $thumbnail_image;

        try {
            Yii::$app->cloudinaryManager->delete($imageURL);

            $this->thumbnail_image = null;

        } catch (Error $err) {
            Yii::error('Error while deleting thumbnail image to Cloudinry: ' . json_encode($err));
        }
    }

    /**
     * @param $insert
     * @return bool|void
     */
    public function beforeSave($insert)
    {
        if(Yii::$app->request instanceof \yii\web\Request) {

            // Get initial IP address of requester
            $ip = Yii::$app->request->getRemoteIP();

            // Check if request is forwarded via load balancer or cloudfront on behalf of user
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'];

                // as "X-Forwarded-For" is usually a list of IP addresses that have routed
                $IParray = array_values(array_filter(explode(',', $forwardedFor)));

                // Get the first ip from forwarded array to get original requester
                $ip = $IParray[0];
            }

            $this->ip_address = $ip;

            if ($insert) {

                $count = self::find()
                    ->andWhere(['ip_address' => $this->ip_address])
                    ->andWhere("DATE(restaurant_created_at) = DATE('".date('Y-m-d')."')")
                    ->count();

                if ($count > 4) {

                    Yii::error("too many store registration from same ip");

                    //block ip

                    $biModel = new BlockedIp();
                    $biModel->ip_address = $this->ip_address;
                    $biModel->note = "Too many store registration from same ip";
                    $biModel->save(false);

                    return $this->addError('ip_address', "Too many store registration");
                }
            }
        }

        if ($insert && $this->referral_code != null) {
            if (!Partner::find()->where(['referral_code' => $this->referral_code])->exists())
                $this->referral_code = null;
        }

        if ($this->scenario == self::SCENARIO_CREATE_STORE_BY_AGENT && $insert) {

            $store_name = strtolower(str_replace(' ', '_', $this->name));
            $store_domain = strtolower(str_replace(' ', '_', $this->restaurant_domain));

            $this->app_id = 'store.plugn.' . $store_domain;

            /**
             * if we change this to use store domain name as git branch name,
             * it would be hard for us to keep track of branch and store relation,
             * in case someone keep changing store domain ?
             */
            $this->store_branch_name = $store_domain;// $store_name;

            $isBranchExists = Yii::$app->githubComponent->isBranchExists($this->store_branch_name);

            if ($isBranchExists) {
                return $this->addError('restaurant_domain', Yii::t('app', 'Another store is already using this domain'));
            }

            $isDomainExist = self::find()->where(['restaurant_domain' => $this->restaurant_domain])->exists();

            if ($isDomainExist)
                return $this->addError('restaurant_domain', Yii::t('app', 'Another store is already using this domain'));

            $this->restaurant_domain = 'https://' . $store_domain . '.plugn.site';
        }

        if ($this->scenario == self::SCENARIO_UPLOAD_STORE_DOCUMENT) {
            //delete tmp files
            $this->deleteTempFiles();
        }

        /*if($insert) {
            $this->enable_cod_fee = false;
        }*/

        return parent::beforeSave($insert);
    }

    /**
     * @return query\RestaurantQuery
     */
    public static function find()
    {
        return new query\RestaurantQuery(get_called_class());
    }

    /**
     * Deletes the files associated with this project
     */
    public function deleteTempFiles()
    {
        if ($this->authorized_signature_file && file_exists(Yii::getAlias('@privateDocuments') . "/uploads/" . $this->authorized_signature_file)) {
            $this->uploadFileToCloudinary(Yii::getAlias('@privateDocuments') . "/uploads/" . $this->authorized_signature_file, 'authorized_signature_file');
        }

        if ($this->commercial_license_file && file_exists(Yii::getAlias('@privateDocuments') . "/uploads/" . $this->commercial_license_file)) {
            $this->uploadFileToCloudinary(Yii::getAlias('@privateDocuments') . "/uploads/" . $this->commercial_license_file, 'commercial_license_file');
        }

        if ($this->identification_file_front_side && file_exists(Yii::getAlias('@privateDocuments') . "/uploads/" . $this->identification_file_front_side)) {
            $this->uploadFileToCloudinary(Yii::getAlias('@privateDocuments') . "/uploads/" . $this->identification_file_front_side, 'identification_file_front_side');
        }

        if ($this->identification_file_back_side && file_exists(Yii::getAlias('@privateDocuments') . "/uploads/" . $this->identification_file_back_side)) {
            $this->uploadFileToCloudinary(Yii::getAlias('@privateDocuments') . "/uploads/" . $this->identification_file_back_side, 'identification_file_back_side');
        }
    }

    /**
     * Upload a File to cloudinary
     * @param type $imageURL
     */
    public function uploadFileToCloudinary($file_path, $attribute)
    {
        $filename = Yii::$app->security->generateRandomString();

        try {

            $result = Yii::$app->cloudinaryManager->upload(
                $file_path, [
                    'public_id' => "restaurants/" . $this->restaurant_uuid . "/private_documents/" . $filename
                ]
            );

            if ($result || count($result) > 0) {

                //delete the file from temp folder
                unlink($file_path);
                $this[$attribute] = basename($result['url']);
            }
        } catch (Error $err) {
            //todo: notify vendor?
            //Yii::error('Error when uploading restaurant document to Cloudinary: ' . json_encode($err));
        }

    }

    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->scenario == self::SCENARIO_CREATE_STORE_BY_AGENT && $insert) {

            //Create a new record in queue table for netlify if old domain or custom domain

            if(!str_contains($this->restaurant_domain, ".plugn.site"))
            {
                $queue = new Queue();
                $queue->restaurant_uuid = $this->restaurant_uuid;
                $queue->queue_status = Queue::QUEUE_STATUS_PENDING;

                if (!$queue->save ())
                    Yii::error ('Queue Error:' . json_encode ($queue->errors));
            }
            else
            {
                Restaurant::updateAll([
                   'has_deployed' => 1,
                ], [
                    'restaurant_uuid' => $this->restaurant_uuid
                ]);
            }
        }

        if ($insert) {

            $freePlan = Plan::find()
                ->where(['valid_for' => 0])
                ->one();

            if (!$freePlan) {
                $freePlan = new Plan();
                $freePlan->description = "";
                $freePlan->name = "Free plan auto generated";
                $freePlan->price = 0;
                $freePlan->valid_for = 0;// 0 days
                $freePlan->platform_fee = 5;
                $freePlan->save(false);
            }

            $subscription = new Subscription();
            $subscription->restaurant_uuid = $this->restaurant_uuid;
            $subscription->plan_id = $freePlan->plan_id; //Free plan by default
            $subscription->subscription_status = Subscription::STATUS_ACTIVE; //Free plan by default
            $subscription->save();

            $restaurant_theme = new RestaurantTheme();
            $restaurant_theme->restaurant_uuid = $this->restaurant_uuid;
            $restaurant_theme->save();

            //Add opening hrs

            /*for ($i = 0; $i < 7; ++$i)
            {
                $opening_hour = new OpeningHour();
                $opening_hour->restaurant_uuid = $this->restaurant_uuid;
                $opening_hour->day_of_week = $i;
                $opening_hour->open_at = 0;
                $opening_hour->close_at = '23:59:59';
                $opening_hour->save ();
            }*/

            $currecy = new RestaurantCurrency();
            $currecy->restaurant_uuid = $this->restaurant_uuid;
            $currecy->currency_id = $this->currency_id;
            $currecy->save();
        }

        if (!$insert && isset($changedAttributes["restaurant_domain"])) {

            $old_domain = $changedAttributes["restaurant_domain"];
            //$this->getOldAttribute("restaurant_domain");

            //save old/original domain detail

            $isNotOriginalDomain = $this->getRestaurantDomainRequests()
                ->exists();

            if (!empty($old_domain) && !$isNotOriginalDomain) {
                $model = new RestaurantDomainRequest();
                $model->restaurant_uuid = $this->restaurant_uuid;
                $model->created_at = $this->restaurant_created_at;
                $model->domain = $old_domain;
                $model->status = RestaurantDomainRequest::STATUS_ASSIGNED;
                $model->save(false);
            }

            //update in netlify

            if ($this->site_id) {

                $domain = str_replace([
                    'http://',
                    'https://',
                    'www.',
                    'www2.',
                ], ['', '', '', ''], $this->restaurant_domain);

                //call api

                Yii::$app->netlifyComponent->updateSite($this->site_id, [
                    'domain_aliases' => $domain,
                    'ssl' => true,
                    'force_ssl' => true
                ]);
            }

            /*$model = new RestaurantDomainRequest();
            $model->restaurant_uuid = $this->restaurant_uuid;
            $model->created_at = date("Y-m-d");
            $model->domain = $this->restaurant_domain;
            $model->status = RestaurantDomainRequest::STATUS_ASSIGNED;
            $model->save(false);*/
        }
    }

    public function setupStore($agent)
    {
        $full_name = explode(' ', $agent->agent_name);
        $firstname = $full_name[0];
        $lastname = array_key_exists(1, $full_name) ? $full_name[1] : null;

        Yii::$app->eventManager->track('Store Created', [
            "restaurant_uuid" => $this->restaurant_uuid,
            'first_name' => trim($firstname),
            'last_name' => trim($lastname),
            'store_name' => $this->name,
            'phone_number' => $this->owner_phone_country_code . $this->owner_number,
            'email' => $agent->agent_email,
            'store_url' => $this->restaurant_domain,
            "country" => $this->country ? $this->country->country_name : null,
            "campaign" => $this->sourceCampaign ? $this->sourceCampaign->utm_campaign : null,
            "utm_medium" => $this->sourceCampaign ? $this->sourceCampaign->utm_medium : null,
            "currency" => $this->currency? $this->currency->code: null,
            "status" => "Open"
        ],
            null,
            $this->restaurant_uuid
        );

        //Create a catrgory for a store by default named "Products". so they can get started adding products without having to add category first

        $category = new Category();
        $category->restaurant_uuid = $this->restaurant_uuid;
        $category->title = 'Products';
        $category->title_ar = 'منتجات';

        if (!$category->save()) {
            return [
                "operation" => "error",
                "message" => $category->errors
            ];
        }

        //Create a business Location for a store by default named "Main Branch".
        $business_location = new BusinessLocation();
        $business_location->restaurant_uuid = $this->restaurant_uuid;
        $business_location->country_id = $this->country_id;
        $business_location->support_pick_up = 1;
        $business_location->business_location_name = 'Main Branch';
        $business_location->business_location_name_ar = 'الفرع الرئيسي';

        if (!$business_location->save()) {

            return [
                "operation" => "error",
                "message" => $business_location->errors
            ];
        }

        //Enable cash by default

        $paymentMethod = PaymentMethod::find()
            ->andWhere(['payment_method_code' => PaymentMethod::CODE_CASH])
            ->one();

        if (!$paymentMethod) {
            $paymentMethod = new PaymentMethod();
            $paymentMethod->payment_method_code = PaymentMethod::CODE_CASH;
            $paymentMethod->payment_method_name = "Cash on delivery";
            $paymentMethod->payment_method_name_ar = "الدفع عند الاستلام";
            $paymentMethod->vat = 0;
            $paymentMethod->save(false);
        }

        $payments_method = new RestaurantPaymentMethod();
        $payments_method->payment_method_id = $paymentMethod->payment_method_id; //Cash
        $payments_method->restaurant_uuid = $this->restaurant_uuid;

        if (!$payments_method->save()) {
            return [
                "operation" => "error",
                "message" => $payments_method->errors
            ];
        }

        $assignment_agent = new AgentAssignment();
        $assignment_agent->agent_id = $agent->agent_id;
        $assignment_agent->assignment_agent_email = $agent->agent_email;
        $assignment_agent->role = AgentAssignment::AGENT_ROLE_OWNER;
        $assignment_agent->restaurant_uuid = $this->restaurant_uuid;
        $assignment_agent->business_location_id = $business_location->business_location_id;

        if (!$assignment_agent->save()) {

            return [
                "operation" => "error",
                "message" => $assignment_agent->errors
            ];
        }

        Yii::info("[New Store Signup] " . $this->name . " has just joined Plugn", __METHOD__);

        // $this->restaurant_status

            /**
             * Yii::$app->eventManager->track('Agent Signup', [
                'first_name' => trim($firstname),
                'last_name' => trim($lastname),
                'phone_number' => $this->owner_phone_country_code . $this->owner_number,
                'email' => $agent->agent_email,
                'store_url' => $this->restaurant_domain,
                "country" => $this->country ? $this->country->country_name : null,
                "campaign" => $this->sourceCampaign ? $this->sourceCampaign->utm_campaign : null,
                "utm_medium" => $this->sourceCampaign ? $this->sourceCampaign->utm_medium : null,
            ],
                null,
                $agent->agent_id
            );*/

            if ($agent->tempPassword) {

                $param = [
                    'email' => $agent->agent_email,
                    'password' => $agent->tempPassword
                ];

                Yii::$app->auth0->createUser($param);
            }

            //Yii::$app->zapier->webhook("https://hooks.zapier.com/hooks/catch/oeap6qy/oeap6qy", $store->attributes + $agent->attributes);

            //Yii::$app->zapier->webhook("https://hooks.zapier.com/hooks/catch/3784096/366cqik",  $store->attributes + $agent->attributes);


        return [
            "operation" => "success",
        ];
    }

    /**
     * delete store + remove from netlify
     */
    public function deleteSite()
    {
        //remove from netlify

        if ($this->site_id)
            Yii::$app->netlifyComponent->deleteSite($this->site_id);

        //remove from local

        $this->setScenario(self::SCENARIO_DELETE);
        $this->site_id = null;
        $this->is_deleted = true;
        $this->restaurant_deleted_at = new Expression("NOW()");

        if (!$this->save()) {
            return [
                "operation" => "error",
                "message" => $this->errors
            ];
        }

        //todo: remove github branch

        //Yii::$app->githubComponent->

        Yii::info($this->name . ' Store deleted by user #' . $this->restaurant_uuid);

            Yii::$app->eventManager->track('Account deleted',  [
                    'store_name' => $this->name,
                    'phone_number' => $this->owner_phone_country_code . $this->owner_number,
                    'store_url' => $this->restaurant_domain,
                    "country" => $this->country ? $this->country->country_name : null,
                    "campaign" => $this->sourceCampaign ? $this->sourceCampaign->utm_campaign : null,
                    "utm_medium" => $this->sourceCampaign ? $this->sourceCampaign->utm_medium : null,
                ],
                null,
                $this->restaurant_uuid
            );

        return [
            'message' => Yii::t('agent', "Store deleted successfully"),
            "operation" => "success",
        ];
    }

    /**
     * Return Logo url to dispaly it on backend
     * @return string
     */
    public function getLogo()
    {
        $photo_url = [];

        if ($this->logo) {
            $restaurantName = str_replace(' ', '', $this->name);
            $url = 'https://res.cloudinary.com/agent/image/upload/v1579525808/restaurants/'
                . $restaurantName . '/logo/'
                . $this->logo;
            $photo_url = $url;
        }

        return $photo_url;
    }

    /**
     *  Return ThumbnailImage url to dispaly it on backend
     * @return string
     */
    public function getThumbnailImage()
    {
        $photo_url = [];

        if ($this->thumbnail_image) {
            $restaurantName = str_replace(' ', '', $this->name);
            $url = 'https://res.cloudinary.com/agent/image/upload/v1579525808/restaurants/'
                . $restaurantName . '/thumbnail-image/'
                . $this->thumbnail_image;
            $photo_url = $url;
        }

        return $photo_url;
    }

    public function isOpen($asap = null)
    {
        //always open

        if ($this->accept_order_247)
            return true;

        /*(TIME(open_at) < TIME(close_at)  AND (TIME(open_at) < NOW() AND TIME(close_at) > NOW()))
        OR
        (
            (TIME(open_at) > TIME(close_at))  AND
            (
                (TIME(open_at) < NOW() AND TIME(close_at) > TIME("23:59:59")) ||
                (TIME("00:00:00") < NOW() AND TIME(close_at) > NOW())
            )
        )

        (
            TIME(open_at) < TIME(close_at)  AND TIME(open_at) < NOW() AND TIME(close_at) > NOW()
        )
        OR
        (
            TIME(open_at) > TIME(close_at)  AND
            (
                TIME(open_at) < NOW() AND TIME(close_at) + interval '1 day' > NOW()
            )
        )*/

        $timeslots = OpeningHour::find()
            ->andWhere([
                'restaurant_uuid' => $this->restaurant_uuid,
                'day_of_week' => date('w', strtotime("now"))
            ])
            //->andWhere(new Expression("TIME(open_at) < NOW() AND TIME(close_at) > NOW()"))
            ->all();

        foreach ($timeslots as $timeslot) {
            if (strtotime($timeslot->open_at) < strtotime($timeslot->close_at)) {
                if (strtotime($timeslot->open_at) < time() && strtotime($timeslot->close_at) > time())
                    return true;

                //store open after 12:00 AM /  1 day = 60*60*24 = 86400
            } else if (strtotime($timeslot->open_at) < time() && strtotime($timeslot->close_at) + 86400 > time()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            'noOfItems',
            'categories',
            'restaurantPages',
            'paymentGatewayQueue',
            'storeBillingAddress',
            'restaurantShippingMethods',
            'restaurantDomainRequests',
            'openingHours' => function ($restaurant) {
                //if($this->accept_order_247)
                //    return null;

                return $restaurant->openingHours;
            },
            'storeSettings' => function ($restaurant) {

                $rows = $restaurant->getSettings()
                    ->andWhere(['code' => "Store"])
                    ->all();

                return ArrayHelper::map($rows, 'key', 'value');
            },
            'restaurantUploads',
            'isOpen' => function ($restaurant) {
                if ($this->accept_order_247)
                    return true;

                return $restaurant->isOpen();
            },
            'reopeningAt' => function ($restaurant) {
                if ($this->accept_order_247)
                    return null;

                return OpeningHour::getReopeningAt($restaurant);
            },
            'webLinks' => function ($restaurant) {
                return $restaurant->getWebLinks()->all();
            },
            'country' => function ($restaurant) {
                return $restaurant->getCountry()->one();
            },
            'storeTheme' => function ($restaurant) {
                return $restaurant->getRestaurantTheme()->one();
            },
            'currencies',
            'currency' => function ($restaurant) {
                return $restaurant->getCurrency()->one();
            },
            'supportDelivery' => function ($restaurant) {
                return $restaurant->getAreaDeliveryZones()->count() > 0 ? 1 : 0;
            },
            'supportPickup' => function ($restaurant) {
                return $restaurant->getPickupBusinessLocations()->count() > 0 ? 1 : 0;
            },
            //not using anymore
            'customerGained' => function ($store) {

                return [
                    'customerGainedThisMonth' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), 1)), date("Y-m-d H:i:s")),

                    'customerGainedLastMonth' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 1, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), 0))),

                    'customerGainedLastTwoMonth' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 2, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m") - 1, 0))),

                    'customerGainedLastThreeMonth' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 3, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m") - 2, 0))),

                    'customerGainedLastFourMonth' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 4, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m") - 3, 0))),

                    'customerGainedLastFiveMonth' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 5, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m") - 4, 0))),

                    'customerGainedLastSixDays' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 6)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 6))),

                    'customerGainedLastFiveDays' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 5)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 5))),

                    'customerGainedLastFourDays' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 4)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 4))),

                    'customerGainedLastThreeDays' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 3)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 3))),

                    'customerGainedLastTwoDays' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 2)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 2))),

                    'customerGainedYesterday' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 1))),

                    'customerGainedToday' => $store
                        ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d"))), date("Y-m-d H:i:s")),

                ];
            },
            'soldItems' => function ($model) {

                return [
                    'soldItemsLastSixDays' => $model->getOrderItems()->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 6 DAY) ')->sum('order_item.qty'),
                    'soldItemsLastFiveDays' => $model->getOrderItems()->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 5 DAY) ')->sum('order_item.qty'),
                    'soldItemsLastFourDays' => $model->getOrderItems()->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 4 DAY) ')->sum('order_item.qty'),
                    'soldItemsLastThreeDays' => $model->getOrderItems()->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 3 DAY) ')->sum('order_item.qty'),
                    'soldItemsLastTwoDays' => $model->getOrderItems()->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 2 DAY) ')->sum('order_item.qty'),
                    'soldItemsYesterday' => $model->getOrderItems()->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 1 DAY) ')->sum('order_item.qty'),
                    'soldItemsToday' => $model->getOrderItems()->andWhere(['DATE(order.order_created_at)' => new Expression('CURDATE()')])->sum('order_item.qty'),

                ];
            },
            'bestSeller',
            'revenueGenerated' => function ($store) {

                return [
                    'revenueGeneratedThisMonth' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), 1)), date("Y-m-d H:i:s")),

                    'revenueGeneratedLastMonth' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 1, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), 0))),

                    'revenueGeneratedLastTwoMonth' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 2, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m") - 1, 0))),

                    'revenueGeneratedLastThreeMonth' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 3, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m") - 2, 0))),

                    'revenueGeneratedLastFourMonth' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 4, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m") - 3, 0))),

                    'revenueGeneratedLastFiveMonth' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 5, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m") - 4, 0))),

                    'revenueGeneratedLastSixDays' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 6)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 6))),

                    'revenueGeneratedLastFiveDays' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 5)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 5))),

                    'revenueGeneratedLastFourDays' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 4)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 4))),

                    'revenueGeneratedLastThreeDays' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 3)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 3))),

                    'revenueGeneratedLastTwoDays' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 2)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 2))),

                    'revenueGeneratedYesterday' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 1))),

                    'revenueGeneratedToday' => $store
                        ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d"))), date("Y-m-d H:i:s"))
                ];
            },
            'orderReceived' => function ($store) {

                return [
                    'ordersRecivedThisMonth' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), 1)), date("Y-m-d H:i:s")),

                    'ordersRecivedLastMonth' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 1, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), 0))),

                    'ordersRecivedLastTwoMonth' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 2, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m") - 1, 0))),

                    'ordersRecivedLastThreeMonth' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 3, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m") - 2, 0))),

                    'ordersRecivedLastFourMonth' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 4, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m") - 3, 0))),

                    'ordersRecivedLastFiveMonth' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m") - 5, 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m") - 4, 0))),

                    'ordersRecivedLastSixDays' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 6)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 6))),

                    'ordersRecivedLastFiveDays' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 5)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 5))),

                    'ordersRecivedLastFourDays' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 4)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 4))),

                    'ordersRecivedLastThreeDays' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 3)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 3))),

                    'ordersRecivedLastTwoDays' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 2)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 2))),

                    'ordersRecivedYesterday' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 1)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 1))),

                    'ordersRecivedToday' => $store
                        ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d"))), date("Y-m-d H:i:s"))

                ];
            },
        ];
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function getBestseller()
    {
        return \common\models\Item::find()
            ->andWhere(['item.restaurant_uuid' => $this->restaurant_uuid])
            ->orderBy(['unit_sold' => SORT_DESC])
            ->limit(5)
            ->select(['item_name', 'item_name_ar', 'unit_sold'])
            ->all();
    }

    /**
     * Gets query for [[Items]].
     *
     * @return ActiveQuery
     */
    public function getItems($modelClass = "\common\models\Item")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    public function beforeDelete()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            Queue::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
            Category::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
            RestaurantTheme::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
            Subscription::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
            AreaDeliveryZone::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
            DeliveryZone::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
            AgentAssignment::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
            BusinessLocation::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
            RestaurantPaymentMethod::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
            OpeningHour::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
            RestaurantCurrency::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
            Restaurant::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);

            $this->deleteRestaurantThumbnailImage();
            $this->deleteRestaurantLogo();
            $transaction->commit();
            return parent::beforeDelete();
        } catch (Exception $e) {
            $transaction->rollBack();
            die($e->getMessage());
            return false;
        }
    }

    /**
     * Promotes current restaurant to busy restaurant while disabling rest
     */
    public function markAsBusy()
    {
        self::updateAll(['restaurant_status' => self::RESTAURANT_STATUS_BUSY], [
            'restaurant_uuid' => $this->restaurant_uuid
        ]);
    }

    /**
     * Promotes current restaurant to open restaurant while disabling rest
     */
    public function markAsOpen()
    {
        self::updateAll(['restaurant_status' => self::RESTAURANT_STATUS_OPEN], [
            'restaurant_uuid' => $this->restaurant_uuid
        ]);
    }

    /**
     * save restaurant delivery areas
     */
    public function saveRestaurantDeliveryArea($delivery_areas)
    {
        RestaurantDelivery::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);

        foreach ($delivery_areas as $area_id) {
            $delivery_area = new RestaurantDelivery();
            $delivery_area->area_id = $area_id;
            $delivery_area->restaurant_uuid = $this->restaurant_uuid;
            $delivery_area->save();
        }
    }

    public function getTotalCustomersByWeek()
    {
        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `customer` where restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $customer_data = [];

        $date_start = strtotime('-6 days');//date('w')

        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', $date_start + ($i * 86400));

            $customer_data[date('w', strtotime($date))] = array(
                'day' => date('D', strtotime($date)),
                'total' => 0
            );
        }

        $rows = Customer::getDb()->cache(function ($db) {

            return Customer::find()
                ->andWhere(['customer.restaurant_uuid' => $this->restaurant_uuid])
                ->select(new Expression('customer_created_at, COUNT(*) as total'))
                ->andWhere(new Expression("DATE(customer_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
                ->groupBy(new Expression('DAYNAME(customer_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $customer_data[date('w', strtotime($result['customer_created_at']))] = array(
                'day' => date('D', strtotime($result['customer_created_at'])),
                'total' => (int)$result['total']
            );
        }

        $number_of_all_customer_gained = Customer::getDb()->cache(function ($db) {

            return Customer::find()
                ->andWhere(['customer.restaurant_uuid' => $this->restaurant_uuid])
                ->andWhere(new Expression("date(customer_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
                ->count();

        }, $cacheDuration, $cacheDependency);

        return [
            'customer_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int)$number_of_all_customer_gained
        ];
    }

    /**
     * Gets query for [[Customers]].
     *
     * @return ActiveQuery
     */
    public function getCustomers($modelClass = "\common\models\Customer")
    {
        return $this->hasMany($modelClass::className(), ['customer_id' => 'customer_id'])
            ->via('orders');
    }

    public function getTotalRevenueByWeek()
    {
        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order` where restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $revenue_generated_chart_data = [];

        $date_start = strtotime('-6 days');//date('w')

        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', $date_start + ($i * 86400));

            $revenue_generated_chart_data[date('w', strtotime($date))] = array(
                'day' => date('D', strtotime($date)),
                'total' => 0
            );
        }

        $rows = Order::getDb()->cache(function ($db) {

            return Order::find()
                ->andWhere(['order.restaurant_uuid' => $this->restaurant_uuid])
                ->activeOrders($this->restaurant_uuid)
                ->select(new Expression('order.order_created_at, SUM(`total_price`) as total'))
                ->andWhere(new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
                ->groupBy(new Expression('DAY(order.order_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $revenue_generated_chart_data[date('w', strtotime($result['order_created_at']))] = array(
                'day' => date('D', strtotime($result['order_created_at'])),
                'total' => (float)$result['total']
            );
        }

        $number_of_all_revenue_generated = Order::getDb()->cache(function ($db) {

            return Order::find()
                ->andWhere(['order.restaurant_uuid' => $this->restaurant_uuid])
                ->activeOrders($this->restaurant_uuid)
                ->andWhere(new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
                ->sum('total_price');

        }, $cacheDuration, $cacheDependency);

        return [
            'revenue_generated_chart_data' => array_values($revenue_generated_chart_data),
            'number_of_all_revenue_generated' => (float)$number_of_all_revenue_generated
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return ActiveQuery
     */
    public function getOrders($modelClass = "\common\models\Order")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->andWhere(['order.is_deleted' => 0]);
    }

    public function getTotalOrdersByWeek()
    {
        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order` where restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $orders_received_chart_data = [];

        $date_start = strtotime('-6 days');//date('w')

        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', $date_start + ($i * 86400));

            $orders_received_chart_data[date('w', strtotime($date))] = array(
                'day' => date('D', strtotime($date)),
                'total' => 0
            );
        }

        $rows = Order::getDb()->cache(function ($db) {

            return Order::find()
                ->andWhere(['order.restaurant_uuid' => $this->restaurant_uuid])
                ->activeOrders($this->restaurant_uuid)
                ->select(new Expression('order_created_at, COUNT(*) as total'))
                ->andWhere(new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
                ->groupBy(new Expression('DAY(order.order_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $orders_received_chart_data[date('w', strtotime($result['order_created_at']))] = array(
                'day' => date('D', strtotime($result['order_created_at'])),
                'total' => (int)$result['total']
            );
        }

        $number_of_all_orders_received = Order::getDb()->cache(function ($db) {

            return Order::find()
                ->andWhere(['order.restaurant_uuid' => $this->restaurant_uuid])
                ->activeOrders($this->restaurant_uuid)
                ->andWhere(new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
                ->count();

        }, $cacheDuration, $cacheDependency);

        return [
            'orders_received_chart_data' => array_values($orders_received_chart_data),
            'number_of_all_orders_received' => (int)$number_of_all_orders_received
        ];
    }

    public function getTotalSoldItemsByWeek()
    {
        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order` where restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $sold_item_chart_data = [];

        $date_start = strtotime('-6 days');//date('w')

        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', $date_start + ($i * 86400));

            $sold_item_chart_data[date('w', strtotime($date))] = array(
                'day' => date('D', strtotime($date)),
                'total' => 0
            );
        }

        $rows = Order::getDb()->cache(function ($db) {

            return OrderItem::find()
                ->andWhere(['order_item.restaurant_uuid' => $this->restaurant_uuid])
                ->joinWith('order', false)
                ->activeOrders()
                ->select('order_item_created_at, SUM(order_item.qty) as total')
                ->andWhere(new Expression("DATE(order_item_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
                ->groupBy(new Expression('DAY(order_item_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $sold_item_chart_data[date('w', strtotime($result['order_item_created_at']))] = array(
                'day' => date('D', strtotime($result['order_item_created_at'])),
                'total' => (int)$result['total']
            );
        }

        $number_of_all_sold_item = Order::getDb()->cache(function ($db) {

            return OrderItem::find()
                ->andWhere(['order_item.restaurant_uuid' => $this->restaurant_uuid])
                ->joinWith('order', false)
                ->activeOrders()
                ->andWhere(new Expression("DATE(order_item_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
                ->sum('order_item.qty');

        }, $cacheDuration, $cacheDependency);

        return [
            'sold_item_chart_data' => array_values($sold_item_chart_data),
            'number_of_all_sold_item' => (int)$number_of_all_sold_item
        ];
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return ActiveQuery
     */
    public function getSoldOrderItems($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->joinWith('order', false)
            ->activeOrders();
    }

    /**
     * @return array
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public function getTotalCustomersByMonth()
    {
        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `customer` where restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $customer_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-1 month')) . '-1';

        for ($i = 1; $i <= date('t', strtotime($date_start)); $i++) {
            $customer_data[$i] = array(
                'day' => $i,
                'total' => 0
            );
        }

        $rows = Customer::getDb()->cache(function ($db) {

            return Customer::find()
                ->andWhere(['customer.restaurant_uuid' => $this->restaurant_uuid])
                ->select(new Expression('customer_created_at, COUNT(*) as total'))
                ->andWhere('`customer_created_at` >= (NOW() - INTERVAL 1 MONTH)')
                ->groupBy(new Expression('DAY(customer_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $customer_data[date('j', strtotime($result['customer_created_at']))] = array(
                'day' => (int)date('j', strtotime($result['customer_created_at'])),
                'total' => (int)$result['total']
            );
        }

        $number_of_all_customer_gained = Customer::getDb()->cache(function ($db) {

            return Customer::find()
                ->andWhere(['customer.restaurant_uuid' => $this->restaurant_uuid])
                ->andWhere('`customer_created_at` >= (NOW() - INTERVAL 1 MONTH)')
                ->count();

        }, $cacheDuration, $cacheDependency);

        return [
            'customer_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int)$number_of_all_customer_gained
        ];
    }

    public function getTotalRevenueByMonth()
    {
        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order` where restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $revenue_generated_chart_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-1 month')) . '-1';

        for ($i = 1; $i <= date('t', strtotime($date_start)); $i++) {
            $revenue_generated_chart_data[$i] = array(
                'day' => $i,
                'total' => 0
            );
        }

        $rows = Order::getDb()->cache(function ($db) {

            return Order::find()
                ->andWhere(['order.restaurant_uuid' => $this->restaurant_uuid])
                ->activeOrders($this->restaurant_uuid)
                ->select(new Expression('order.order_created_at, SUM(`total_price`) as total'))
                ->andWhere(new Expression("DATE(order.order_created_at) >= (NOW() - INTERVAL 1 MONTH)"))
                ->groupBy(new Expression('DAY(order.order_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $revenue_generated_chart_data[date('j', strtotime($result['order_created_at']))] = array(
                'day' => (int)date('j', strtotime($result['order_created_at'])),
                'total' => (float)$result['total']
            );
        }

        $number_of_all_revenue_generated = Order::getDb()->cache(function ($db) {

            return Order::find()
                ->andWhere(['order.restaurant_uuid' => $this->restaurant_uuid])
                ->activeOrders($this->restaurant_uuid)
                ->andWhere(new Expression("DATE(order.order_created_at) >= (NOW() - INTERVAL 1 MONTH)"))
                ->sum('total_price');

        }, $cacheDuration, $cacheDependency);

        return [
            'revenue_generated_chart_data' => array_values($revenue_generated_chart_data),
            'number_of_all_revenue_generated' => (float)$number_of_all_revenue_generated
        ];
    }

    public function getTotalOrdersByMonth()
    {
        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order` where restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $orders_received_chart_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-1 month')) . '-1';

        for ($i = 1; $i <= date('t', strtotime($date_start)); $i++) {
            $orders_received_chart_data[$i] = array(
                'day' => $i,
                'total' => 0
            );
        }

        $rows = Order::getDb()->cache(function ($db) {

            return Order::find()
                ->andWhere(['order.restaurant_uuid' => $this->restaurant_uuid])
                ->activeOrders($this->restaurant_uuid)
                ->select(new Expression('order_created_at, COUNT(*) as total'))
                ->andWhere(new Expression("DATE(order.order_created_at) >= (NOW() - INTERVAL 1 MONTH)"))
                ->groupBy(new Expression('DAY(order.order_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $orders_received_chart_data[date('j', strtotime($result['order_created_at']))] = array(
                'day' => (int)date('j', strtotime($result['order_created_at'])),
                'total' => (int)$result['total']
            );
        }

        $number_of_all_orders_received = Order::getDb()->cache(function ($db) {

            return Order::find()
                ->andWhere(['order.restaurant_uuid' => $this->restaurant_uuid])
                ->activeOrders($this->restaurant_uuid)
                ->andWhere(new Expression("DATE(order.order_created_at) >= (NOW() - INTERVAL 1 MONTH)"))
                ->count();

        }, $cacheDuration, $cacheDependency);

        return [
            'orders_received_chart_data' => array_values($orders_received_chart_data),
            'number_of_all_orders_received' => (int)$number_of_all_orders_received
        ];
    }

    public function getTotalSoldItemsByMonth()
    {
        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order` where restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $sold_item_chart_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-1 month')) . '-1';

        for ($i = 1; $i <= date('t', strtotime($date_start)); $i++) {
            $sold_item_chart_data[$i] = array(
                'day' => $i,
                'total' => 0
            );
        }

        $rows = Order::getDb()->cache(function ($db) {

            return OrderItem::find()
                ->andWhere(['order_item.restaurant_uuid' => $this->restaurant_uuid])
                ->joinWith('order', false)
                ->activeOrders()
                ->select('order_item_created_at, SUM(order_item.qty) as total')
                ->andWhere(new Expression("DATE(order_item_created_at) >= (NOW() - INTERVAL 1 MONTH)"))
                ->groupBy(new Expression('DATE(order_item_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $sold_item_chart_data[date('j', strtotime($result['order_item_created_at']))] = array(
                'day' => (int)date('j', strtotime($result['order_item_created_at'])),
                'total' => (int)$result['total']
            );
        }

        $number_of_all_sold_item = Order::getDb()->cache(function ($db) {

            return OrderItem::find()
                ->andWhere(['order_item.restaurant_uuid' => $this->restaurant_uuid])
                ->joinWith('order', false)
                ->activeOrders()
                ->andWhere(new Expression("DATE(order_item_created_at) >= (NOW() - INTERVAL 1 MONTH)"))
                ->sum('order_item.qty');

        }, $cacheDuration, $cacheDependency);

        return [
            'sold_item_chart_data' => array_values($sold_item_chart_data),
            'number_of_all_sold_item' => (int)$number_of_all_sold_item
        ];
    }

    public function getTotalCustomersByMonths($months)
    {
        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `customer` where restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $customer_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-' . $months . ' month')) . '-1';
        $date_end = date('Y-m-d', strtotime('last day of previous month'));
        //date('Y-m-d');//date('Y') . '-' . date('m') . '-1';

        for ($i = 0; $i <= $months; $i++) {

            $month = date('m', strtotime('-' . ($months - $i) . ' month'));

            $customer_data[$month] = array(
                'month' => date('F', strtotime('-' . ($months - $i) . ' month')),
                'total' => 0
            );
        }

        $rows = Customer::getDb()->cache(function ($db) use ($months) {

            return Customer::find()
                ->andWhere(['customer.restaurant_uuid' => $this->restaurant_uuid])
                ->select(new Expression('customer_created_at, COUNT(*) as total'))
                ->andWhere('`customer_created_at` >= (NOW() - INTERVAL ' . $months . ' MONTH)')
//            ->andWhere('DATE(`customer_created_at`) >= DATE("'.$date_start.'") AND DATE(`customer_created_at`) <= DATE("'.$date_end.'")')
                ->groupBy(new Expression('MONTH(customer_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $customer_data[date('m', strtotime($result['customer_created_at']))] = array(
                'month' => Yii::t('app', date('F', strtotime($result['customer_created_at']))),
                'total' => (int)$result['total']
            );
        }

        $number_of_all_customer_gained = Customer::getDb()->cache(function ($db) use ($months) {

            return Customer::find()
                ->andWhere(['customer.restaurant_uuid' => $this->restaurant_uuid])
                ->andWhere('`customer_created_at` >= (NOW() - INTERVAL ' . $months . ' MONTH)')
//            ->andWhere('DATE(`customer_created_at`) >= DATE("'.$date_start.'") AND DATE(`customer_created_at`) <= DATE("'.$date_end.'")')
                ->count();

        }, $cacheDuration, $cacheDependency);

        return [
            'customer_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int)$number_of_all_customer_gained
        ];
    }

    public function getTotalRevenueByMonths($months)
    {
        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order` where restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $revenue_generated_chart_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-' . $months . ' month')) . '-1';
        $date_end = date('Y-m-d', strtotime('last day of previous month'));
        //date('Y-m-d');//date('Y') . '-' . date('m') . '-1';

        for ($i = 0; $i <= $months; $i++) {

            $month = date('m', strtotime('-' . ($months - $i) . ' month'));

            $revenue_generated_chart_data[$month] = array(
                'month' => date('F', strtotime('-' . ($months - $i) . ' month')),
                'total' => 0
            );
        }

        $rows = Order::getDb()->cache(function ($db) use ($months) {

            return Order::find()
                ->andWhere(['order.restaurant_uuid' => $this->restaurant_uuid])
                ->activeOrders($this->restaurant_uuid)
                ->select(new Expression('order.order_created_at, SUM(`total_price`) as total'))
                ->andWhere(new Expression("DATE(order.order_created_at) >= (NOW() - INTERVAL " . $months . " MONTH)"))
                ->groupBy(new Expression('MONTH(order.order_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $revenue_generated_chart_data[date('m', strtotime($result['order_created_at']))] = array(
                'month' => Yii::t('app', date('F', strtotime($result['order_created_at']))),
                'total' => (float)$result['total']
            );
        }

        $number_of_all_revenue_generated = Order::getDb()->cache(function ($db) use ($months) {

            return Order::find()
                ->andWhere(['order.restaurant_uuid' => $this->restaurant_uuid])
                ->activeOrders($this->restaurant_uuid)
                ->andWhere(new Expression("DATE(order.order_created_at) >= (NOW() - INTERVAL " . $months . " MONTH)"))
                ->sum('total_price');

        }, $cacheDuration, $cacheDependency);

        return [
            'revenue_generated_chart_data' => array_values($revenue_generated_chart_data),
            'number_of_all_revenue_generated' => (float)$number_of_all_revenue_generated
        ];
    }

    public function getTotalOrdersByMonths($months)
    {
        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order` where restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $orders_received_chart_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-' . $months . ' month')) . '-1';
        $date_end = date('Y-m-d', strtotime('last day of previous month'));
        //date('Y') . '-' . date('m') . '-1';

        for ($i = 0; $i <= $months; $i++) {

            $month = date('m', strtotime('-' . ($months - $i) . ' month'));

            $orders_received_chart_data[$month] = array(
                'month' => date('F', strtotime('-' . ($months - $i) . ' month')),
                'total' => 0
            );
        }

        $rows = Order::getDb()->cache(function ($db) use ($months) {

            return Order::find()
                ->andWhere(['order.restaurant_uuid' => $this->restaurant_uuid])
                ->activeOrders($this->restaurant_uuid)
                ->select(new Expression('order_created_at, COUNT(*) as total'))
                ->andWhere(new Expression("DATE(order.order_created_at) >= (NOW() - INTERVAL " . $months . " MONTH)"))
                ->groupBy(new Expression('MONTH(order.order_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $orders_received_chart_data[date('m', strtotime($result['order_created_at']))] = array(
                'month' => Yii::t('app', date('F', strtotime($result['order_created_at']))),
                'total' => (int)$result['total']
            );
        }

        $number_of_all_orders_received = Order::getDb()->cache(function ($db) use ($months) {

            return Order::find()
                ->andWhere(['order.restaurant_uuid' => $this->restaurant_uuid])
                ->activeOrders($this->restaurant_uuid)
                ->andWhere(new Expression("DATE(order.order_created_at) >= (NOW() - INTERVAL " . $months . " MONTH)"))
                ->count();

        }, $cacheDuration, $cacheDependency);

        return [
            'orders_received_chart_data' => array_values($orders_received_chart_data),
            'number_of_all_orders_received' => (int)$number_of_all_orders_received
        ];
    }

    public function getTotalSoldItemsByMonths($months)
    {
        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order` where restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $sold_item_chart_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-' . $months . ' month')) . '-1';
        $date_end = date('Y-m-d', strtotime('last day of previous month'));
        //date('Y-m-d');//date('Y') . '-' . date('m') . '-1';

        for ($i = 0; $i <= $months; $i++) {

            $month = date('m', strtotime('-' . ($months - $i) . ' month'));

            $sold_item_chart_data[$month] = array(
                'month' => date('F', strtotime('-' . ($months - $i) . ' month')),
                'total' => 0
            );
        }

        $rows = OrderItem::getDb()->cache(function ($db) use ($months) {

            return OrderItem::find()
                ->andWhere(['order_item.restaurant_uuid' => $this->restaurant_uuid])
                ->joinWith('order', false)
                ->activeOrders()
                ->select('order_item_created_at, SUM(order_item.qty) as total')
                ->andWhere(new Expression("DATE(order_item_created_at) >= (NOW() - INTERVAL " . $months . " MONTH)"))
                ->groupBy(new Expression('MONTH(order_item_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $sold_item_chart_data[date('m', strtotime($result['order_item_created_at']))] = array(
                'month' => Yii::t('app', date('F', strtotime($result['order_item_created_at']))),
                'total' => (int)$result['total']
            );
        }

        $number_of_all_sold_item = OrderItem::getDb()->cache(function ($db) use ($months) {

            return OrderItem::find()
                ->andWhere(['order_item.restaurant_uuid' => $this->restaurant_uuid])
                ->joinWith('order', false)
                ->activeOrders()
                ->andWhere(new Expression("DATE(order_item_created_at) >= (NOW() - INTERVAL " . $months . " MONTH)"))
                ->sum('order_item.qty');

        }, $cacheDuration, $cacheDependency);

        return [
            'sold_item_chart_data' => array_values($sold_item_chart_data),
            'number_of_all_sold_item' => (int)$number_of_all_sold_item
        ];
    }

    /**
     * return top 5 most sold items
     * @param $months
     * @return array[]
     */
    public function getMostSoldItems()
    {
        $most_sold_item_chart_data = [];

        $rows = \common\models\Item::find()
            ->andWhere(['item.restaurant_uuid' => $this->restaurant_uuid])
            ->orderBy(['unit_sold' => SORT_DESC])
            ->limit(5)
            ->all();

        foreach ($rows as $item) {
            $most_sold_item_chart_data[] = [
                'item_name' => $item->item_name,
                'item_name_ar' => $item->item_name_ar,
                'total' => $item->unit_sold
            ];
        }

        return array_reverse($most_sold_item_chart_data);
    }

    /**
     * Gets query for [[NoOfItems]].
     *
     * @return ActiveQuery
     */
    public function getNoOfItems($modelClass = "\common\models\Item")
    {
        return Item::find()
            ->andWhere(['item.restaurant_uuid' => $this->restaurant_uuid])
            ->andWhere(['item_status' => Item::ITEM_STATUS_PUBLISH])
            ->count();
    }

    /**
     * send weekly store stats in email
     */
    public function sendWeeklyReport()
    {
        //Revenue generated
        $lastWeekRevenue = $this
            ->getStoreRevenue(
                date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 14)),
                date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 8))
            );

        $thisWeekRevenue = $this
            ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 7)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d"))));

        //Orders received
        $lastWeekOrdersReceived = $this
            ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 14)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 8)));

        $thisWeekOrdersReceived = $this
            ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 7)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d"))));

        //customer gained
        $lastWeekCustomerGained = $this
            ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 14)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 8)));

        $thisWeekCustomerGained = $this
            ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 7)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d"))));

        // Revenue Generated
        $revenuePercentage = 0;

        if ($lastWeekRevenue > 0) {
            if ($thisWeekRevenue > $lastWeekRevenue) { //inc
                if ($lastWeekRevenue > 0) {
                    $increase = $thisWeekRevenue - $lastWeekRevenue;

                    $revenuePercentage = $increase / $lastWeekRevenue * 100;
                } else {
                    $revenuePercentage = 100;
                }

            } else if ($thisWeekRevenue < $lastWeekRevenue) { //dec
                $decrease = $lastWeekRevenue - $thisWeekRevenue;
                $revenuePercentage = $decrease / $lastWeekRevenue * -100;
            }
        }

        // Orders received
        $ordersReceivedPercentage = 0;

        if ($thisWeekOrdersReceived > $lastWeekOrdersReceived) { //inc
            if ($lastWeekOrdersReceived > 0) {
                $increase = $thisWeekOrdersReceived - $lastWeekOrdersReceived;

                $ordersReceivedPercentage = $increase / $lastWeekOrdersReceived * 100;
            } else {
                $ordersReceivedPercentage = 100;
            }

        } else if ($thisWeekOrdersReceived < $lastWeekOrdersReceived) { //dec
            $decrease = $lastWeekOrdersReceived - $thisWeekOrdersReceived;
            $ordersReceivedPercentage = $decrease / $lastWeekOrdersReceived * -100;
        }


        //Customer gained
        $customerGainedPercentage = 0;

        if ($thisWeekCustomerGained > $lastWeekCustomerGained) { // inc
            if ($lastWeekCustomerGained > 0) {
                $increase = $thisWeekCustomerGained - $lastWeekCustomerGained;

                $customerGainedPercentage = $increase / $lastWeekCustomerGained * 100;
            } else {
                $customerGainedPercentage = 100;
            }

        } else if ($thisWeekCustomerGained < $lastWeekCustomerGained) { //dec
            $decrease = $lastWeekCustomerGained - $thisWeekCustomerGained;
            $customerGainedPercentage = $decrease / $lastWeekCustomerGained * -100;
        }

        if ($lastWeekOrdersReceived > 0 || $thisWeekOrdersReceived > 0) {

            $agentAssignments = $this->getAgentAssignments()
                ->andWhere([
                    'role' => AgentAssignment::AGENT_ROLE_OWNER,
                    'receive_weekly_stats' => 1
                ])
                ->all();

            foreach ($agentAssignments as $key => $agentAssignment) {

                if ($agentAssignment->receive_weekly_stats) {

                    $weeklyStoreSummaryEmail = Yii::$app->mailer->compose([
                        'html' => 'weekly-summary',
                    ], [
                        'store' => $this,
                        'agent_name' => $agentAssignment->agent->agent_name,
                        'revenuePercentage' => $revenuePercentage,
                        'ordersReceivedPercentage' => $ordersReceivedPercentage,
                        'customerGainedPercentage' => $customerGainedPercentage,
                        'thisWeekRevenue' => $thisWeekRevenue,
                        'thisWeekOrdersReceived' => $thisWeekOrdersReceived,
                        'thisWeekCustomerGained' => $thisWeekCustomerGained,
                    ])
                        ->setFrom([Yii::$app->params['noReplyEmail'] => 'Plugn'])
                        ->setTo([$agentAssignment->agent->agent_email])
                        ->setSubject('Weekly Store Summary');

                    if ($key == 0)
                        $weeklyStoreSummaryEmail->setBcc(Yii::$app->params['supportEmail']);

                    try {
                        $weeklyStoreSummaryEmail->send();
                    } catch (Swift_TransportException $e) {
                        Yii::error($e->getMessage(), "email");
                    }
                }
            }
        }
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return ActiveQuery
     */
    public function getStoreRevenue($start_date, $end_date, $modelClass = "\common\models\Order")
    {
        $cacheDuration = 60 * 60 * 24 * 7;// 7 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order` WHERE restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        return Order::getDb()->cache(function ($db) use ($modelClass, $start_date, $end_date) {

            return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
                ->activeOrders($this->restaurant_uuid)
                ->andWhere(['between', 'order.order_created_at', $start_date, $end_date])
                ->sum('total_price');

        }, $cacheDuration, $cacheDependency);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return ActiveQuery
     */
    public function getOrdersReceived($start_date, $end_date, $modelClass = "\common\models\Order")
    {
        $cacheDuration = 60 * 60 * 24 * 7;// 7 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order` WHERE restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        return Order::getDb()->cache(function ($db) use ($modelClass, $start_date, $end_date) {

            return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
                ->ordersReceived($this->restaurant_uuid, $start_date, $end_date);

        }, $cacheDuration, $cacheDependency);
    }

    /**
     * Gets query for [[Customers]].
     *
     * @return ActiveQuery
     */
    public function getCustomerGained($start_date, $end_date, $modelClass = "\common\models\Customer")
    {
        $cacheDuration = 60 * 60 * 24 * 7;// 7 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM customer WHERE restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        return Customer::getDb()->cache(function ($db) use ($modelClass, $start_date, $end_date) {
            return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
                ->customerGained($this->restaurant_uuid, $start_date, $end_date);
        }, $cacheDuration, $cacheDependency);
    }

    public function getCSV()
    {
        $cacheDuration = 60 * 60 * 24 * 7;// 7 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM payment WHERE restaurant_uuid="' . $this->restaurant_uuid . '"',
        ]);

        $model = $this;

        return Payment::getDb()->cache(function ($db) use ($model) {

            return Payment::find()
                ->andWhere(['payment.restaurant_uuid' => $this->restaurant_uuid])
                //$model->getPayments()
                ->select(new Expression("currency_code, SUM(payment_net_amount) as payment_net_amount, SUM(payment_gateway_fee) as payment_gateway_fees,
                            SUM(plugn_fee) as plugn_fees, SUM(partner_fee) as partner_fees"))
                ->join('left join', 'order', '`order`.order_uuid = payment.order_uuid')
                ->filterPaid()
                ->groupBy('order.currency_code')
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);
    }

    /**
     * generate invoices for stores to pay as order commission / plugn fee
     * @param $restaurant_uuid
     * @param $currency_code
     * @param $amount
     * @param $order_uuid
     * @param $payment_uuid
     * @return void
     */
    public static function processPlugnCommissionInvoice(
        $restaurant_uuid, $currency_code, $amount, $order_uuid, $payment_uuid = null) {

        $invoice = RestaurantInvoice::find()
            ->andWhere([
                'restaurant_uuid' => $restaurant_uuid,
                'currency_code' => $currency_code,
                'invoice_status' => RestaurantInvoice::STATUS_UNPAID
            ])->one();

        if(!$invoice) {
            $invoice = new RestaurantInvoice();
            $invoice->restaurant_uuid = $restaurant_uuid;
            $invoice->payment_uuid = $payment_uuid;
            $invoice->amount = $amount;
            $invoice->currency_code = $currency_code;
        }
        else {
            $invoice->amount += $amount;
        }

        if(!$invoice->save()) {
            Yii::error(print_r($invoice->errors, true));
        }

        $invoice_item = new InvoiceItem();
        $invoice_item->invoice_uuid = $invoice->invoice_uuid;
        $invoice_item->order_uuid = $order_uuid;

        //$invoice_item->comment = $payment->order_uuid;
        $invoice_item->total = $amount;

        if(!$invoice_item->save()) {
            Yii::error(print_r($invoice->errors, true));
        }
    }

    /**
     * Gets query for [[StoreBillingAddress]].
     *
     * @return ActiveQuery
     */
    public function getStoreBillingAddress($modelClass = "\common\models\RestaurantBillingAddress")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Payments]].
     *
     * @return ActiveQuery
     */
    public function getPayments($modelClass = "\common\models\Payment")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Get Agents assigned to this Restaurant
     * @return ActiveQuery
     */
    public function getAgents($modelClass = "\common\models\Agent")
    {
        return $this->hasMany($modelClass::className(), ['agent_id' => 'agent_id'])
            ->via('agentAssignments');
    }

    /**
     * Return owner of this store
     */
    public function getOwnerAgent($modelClass = "\common\models\Agent")
    {
        return $this->hasMany($modelClass::className(), ['agent_id' => 'agent_id'])
            ->via('agentAssignments', function ($query) {
                return $query->andWhere(['agent_assignment.role' => AgentAssignment::AGENT_ROLE_OWNER]);
            });
    }

    /**
     * Gets query for [[RestaurantBillingAddress]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantBillingAddress($modelClass = "\common\models\RestaurantBillingAddress")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return ActiveQuery
     */
    public function getSubscriptions($modelClass = "\common\models\Subscription")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return ActiveQuery
     */
    public function getActiveSubscription($modelClass = "\common\models\Subscription")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->andWhere([
                'AND',
                ['subscription_status' => Subscription::STATUS_ACTIVE],
                new Expression('subscription_end_at IS NULL || DATE(subscription_end_at) >= DATE(NOW())')
            ])
            ->orderBy('subscription_start_at DESC');
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return ActiveQuery
     */
    public function getPlan($modelClass = "\common\models\Plan")
    {
        return $this->hasOne($modelClass::className(), ['plan_id' => 'plan_id'])
            ->via('activeSubscription');
    }

    /**
     * Gets query for [[Setting]].
     *
     * @return ActiveQuery
     */
    public function getSettings($modelClass = "\common\models\Setting")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantDeliveryAreas]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantDeliveryAreas($modelClass = "\common\models\RestaurantDelivery")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return ActiveQuery
     */
    public function getAvailableAreas($modelClass = "\common\models\AreaDeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->andWhere(['is', 'area_delivery_zone.area_id', null]);
    }

    /**
     * Gets query for [[RestaurantBranches]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantBranches($modelClass = "\common\models\RestaurantBranch")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * @param $modelClass
     * @return ActiveQuery
     */
    public function getRestaurantShippingMethods($modelClass = "\common\models\RestaurantShippingMethod")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[restaurantPages]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantPages($modelClass = "\common\models\RestaurantPage")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Campaign]].
     *
     * @return ActiveQuery
     */
    public function getCampaigns($modelClass = "\common\models\Campaign")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[SourceCampaign]].
     *
     * @return ActiveQuery
     */
    public function getSourceCampaign($modelClass = "\common\models\Campaign")
    {
        return $this->hasOne($modelClass::className(), ['utm_uuid' => 'utm_uuid'])
            ->via('restaurantByCampaign');
    }

    /**
     * Gets query for [[ShippingMethods]].
     *
     * @return ActiveQuery
     */
    public function getShippingMethods($modelClass = "\common\models\ShippingMethod")
    {
        return $this->hasMany($modelClass::className(), ['shipping_method_id' => 'shipping_method_id'])
            ->viaTable('restaurant_shipping_method', ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantByCampaign]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantByCampaign($modelClass = "\common\models\RestaurantByCampaign")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[PaymentMethods]].
     *
     * @return ActiveQuery
     */
    public function getPaymentMethods($modelClass = "\common\models\PaymentMethod")
    {
        return $this->hasMany($modelClass::className(), ['payment_method_id' => 'payment_method_id'])
            ->viaTable(RestaurantPaymentMethod::tableName(), ['restaurant_uuid' => 'restaurant_uuid'],
                function ($query) {
                    $query->onCondition(['status' => RestaurantPaymentMethod::STATUS_ACTIVE]);
                });
    }

    /**
     * Gets query for [[OpeningHours]].
     *
     * @return ActiveQuery
     */
    public function getOpeningHours($modelClass = "\common\models\OpeningHour")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return ActiveQuery
     */
    public function getActiveOrders($modelClass = "\common\models\Order")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->activeOrders($this->restaurant_uuid);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return ActiveQuery
     */
    public function getTickets($modelClass = "\common\models\Ticket")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return ActiveQuery
     */
    public function getOrderItems($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->joinWith('order')
            ->activeOrders();
    }

    /**
     * Gets query for [[Vouchers]].
     *
     * @return ActiveQuery
     */
    public function getVouchers($modelClass = "\common\models\Voucher")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[StoreUpdates]].
     *
     * @return ActiveQuery
     */
    public function getStoreUpdates($modelClass = "\common\models\StoreUpdate")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Refunds]].
     *
     * @return ActiveQuery
     */
    public function getRefunds($modelClass = "\common\models\Refund")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Queues]].
     *
     * @return ActiveQuery
     */
    public function getQueues($modelClass = "\common\models\Queue")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    public function getQueue($modelClass = "\common\models\Queue")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])->orderBy('queue_id DESC');
    }

    /**
     * Gets query for [[RestaurantTheme]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantTheme($modelClass = "\common\models\RestaurantTheme")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[TapQueue]].
     *
     * @return ActiveQuery
     */
    public function getTapQueue($modelClass = "\common\models\TapQueue")
    {
        return $this->hasOne($modelClass::className(), ['tap_queue_id' => 'tap_queue_id']);
    }

    /**
     * Gets query for [[TapQueue]].
     *
     * @return ActiveQuery
     */
    public function getPaymentGatewayQueue()
    {
        return $this->hasOne(PaymentGatewayQueue::className(), ['payment_gateway_queue_id' => 'payment_gateway_queue_id']);
    }

    /**
     * Gets query for [[WebLinks]].
     *
     * @return ActiveQuery
     */
    public function getWebLinks($modelClass = "\common\models\WebLink")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[StorePages]].
     *
     * @return ActiveQuery
     */
    public function getStorePages($modelClass = "\common\models\RestaurantPage")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[StoreWebLinks]].
     *
     * @return ActiveQuery
     */
    public function getStoreWebLinks($modelClass = "\common\models\StoreWebLink")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return ActiveQuery
     */
    public function getCountryDeliveryZones($countryId, $modelClass = "\common\models\DeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->where(['country_id' => $countryId, 'delivery_zone.is_deleted' => 0]);
    }

    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return ActiveQuery
     */
    public function getBusinessLocations($modelClass = "\common\models\BusinessLocation")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->andWhere(['business_location.is_deleted' => 0]);
    }

    /**
     * @param $modelClass
     * @return ActiveQuery
     */
    public function getBusinessCategory($modelClass = "\common\models\BusinessCategory")
    {
        return $this->hasOne($modelClass::className(), ['business_category_uuid' => 'business_category_uuid'])
            ->via('restaurantType');
    }

    // /**
    //  * Gets query for [[BusinessLocations]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getDeliveryZonesForSpecificCountry($countryId)
    // {
    //   return $this->hasMany(DeliveryZone::className(), ['business_location_id' => 'business_location_id'])
    //       ->andWhere(['delivery_zone.is_deleted' => 0]);
    //       ->viaTable('business_location', ['restaurant_uuid' => 'restaurant_uuid']);
    // }

    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return ActiveQuery
     */
    public function getPickupBusinessLocations($modelClass = "\common\models\BusinessLocation")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->andWhere(['support_pick_up' => 1, 'business_location.is_deleted' => 0]);
    }

    /**
     * Gets query for [[DeliveryZones]].
     *
     * @return ActiveQuery
     */
    public function getDeliveryZones($modelClass = "\common\models\DeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->joinWith('businessLocation')
            ->andWhere(['delivery_zone.is_deleted' => 0, 'business_location.is_deleted' => 0]);
    }

    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return ActiveQuery
     */
    public function getAreaDeliveryZonesForSpecificCountry($countryId, $modelClass = "\common\models\AreaDeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['delivery_zone_id' => 'delivery_zone_id'])
            ->via('deliveryZones')
            ->andWhere(['delivery_zone.country_id' => $countryId])
            ->joinWith(['deliveryZone', 'city']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return ActiveQuery
     */
    public function getAreas($modelClass = "\common\models\Area")
    {
        return $this->hasMany($modelClass::className(), ['area_id' => 'area_id'])->via('areaDeliveryZones');
    }

    /**
     * Gets query for [[AreaDeliveryZones]].
     *
     * @return ActiveQuery
     */
    public function getAreaDeliveryZones($modelClass = "\common\models\AreaDeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Partner]].
     *
     * @return ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partner::className(), ['referral_code' => 'referral_code'])
            ->where(['partner_status' => Partner::STATUS_ACTIVE]);
    }

    /**
     * Gets query for [[RestaurantUploads]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantUploads($modelClass = "\common\models\RestaurantUpload")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQuery
     */
    public function getCategories($modelClass = "\common\models\Category")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->orderBy('sort_number, category_id');
    }

    /**
     * @param $modelClass
     * @return mixed|void|null
     */
    public function getCountryName($modelClass = "\common\models\Country")
    {
        $country = $this->getCountry($modelClass)->one();

        if ($country)
            return $country->country_name;
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQuery
     */
    public function getCountry($modelClass = "\common\models\Country")
    {
        return $this->hasOne($modelClass::className(), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return ActiveQuery
     */
    public function getCurrency($modelClass = "\common\models\Currency")
    {
        return $this->hasOne($modelClass::className(), ['currency_id' => 'currency_id']);
    }

    /**
     * Gets query for [[Currencies]].
     *
     * @return ActiveQuery
     */
    public function getCurrencies($modelClass = "\common\models\Currency")
    {
        return $this->hasMany($modelClass::className(), ['currency_id' => 'currency_id'])
            ->via('restaurantCurrencies');
    }

    /**
     * Gets query for [[BankDiscount]].
     *
     * @return ActiveQuery
     */
    public function getBankDiscounts($modelClass = "\common\models\BankDiscount")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantCurrencies]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantCurrencies($modelClass = "\common\models\RestaurantCurrency")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Invoices]].
     *
     * @return ActiveQuery
     */
    public function getInvoices($modelClass = "\common\models\RestaurantInvoice")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * @param $modelClass
     * @return ActiveQuery
     */
    public function getRestaurantItemTypes($modelClass = "\common\models\RestaurantItemType")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * @param $modelClass
     * @return ActiveQuery
     */
    public function getRestaurantType($modelClass = "\common\models\RestaurantType")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
