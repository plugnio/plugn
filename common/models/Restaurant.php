<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;
use common\models\WebLink;
use borales\extensions\phoneInput\PhoneInputValidator;

/**
 * This is the model class for table "restaurant".
 *
 * /**
 * This is the model class for table "restaurant".
 *
 * @property string $restaurant_uuid
 * @property int $country_id
 * @property int $currency_id
 * @property string $name
 * @property string|null $name_ar
 * @property string|null $tagline
 * @property string|null $tagline_ar
 * @property string|null $restaurant_domain
 * @property string|null $app_id
 * @property int $restaurant_status
 * @property string|null $thumbnail_image
 * @property string|null $logo
 * @property int|null $support_delivery
 * @property int|null $version
 * @property int|null $sitemap_require_update
 * @property int|null $support_pick_up
 * @property int|null $hide_request_driver_button // hide requerst driver button if the order is scheduled
 * @property string|null $phone_number
 * @property string|null $phone_number_country_code
 * @property string $restaurant_email
 * @property string|null $restaurant_created_at
 * @property string|null $restaurant_updated_at
 * @property string|null $business_id
 * @property string|null $business_entity_id
 * @property string|null $wallet_id
 * @property string|null $merchant_id
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
 * @property string|null $iban
 * @property string|null $owner_first_name
 * @property string|null $owner_last_name
 * @property string|null $owner_email
 * @property string|null $owner_number
 * @property string|null $owner_phone_country_code
 * @property string|null $identification_issuing_date
 * @property string|null $identification_expiry_date
 * @property string|null $identification_file_front_side
 * @property string|null $identification_file_id_front_side
 * @property string|null $identification_title
 * @property string|null $identification_file_purpose
 * @property int|null $restaurant_email_notification
 * @property int $retention_email_sent
 * @property int $enable_gift_message
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
 * @property string|null $google_analytics_id
 * @property string|null $facebook_pixil_id
 * @property string|null $snapchat_pixil_id
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
 * @property int|null $has_deployed
 * @property int|null $tap_queue_id
 * @property string|null $identification_file_back_side
 * @property string|null $identification_file_id_back_side
 * @property string|null $default_language
 *
 * @property AgentAssignment[] $agentAssignments
 * @property Agent[] $agents
 * @property Agent $agent
 * @property Order[] $orders
 * @property Refund[] $refunds
 * @property Item[] $items
 * @property OpeningHour[] $openingHours
 * @property RestaurantDelivery[] $restaurantDeliveryAreas
 * @property RestaurantBranch[] $restaurantBranches
 * @property Area[] $areas
 * @property RestaurantPaymentMethod[] $restaurantPaymentMethods
 * @property RestaurantTheme $restaurantTheme
 * @property PaymentMethod[] $paymentMethods
 * @property WebLink[] $webLinks
 * @property StoreWebLink[] $storeWebLinks
 * @property Voucher[] $vouchers
 * @property Queue[] $queues
 * @property Subscription[] $subscriptions
 * @property BusinessLocation[] $businessLocations
 * @property DeliveryZones[] $deliveryZones
 */
class Restaurant extends \yii\db\ActiveRecord
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


    const SCENARIO_CREATE_STORE_BY_AGENT = 'create-by-agent';
    const SCENARIO_CREATE_TAP_ACCOUNT = 'tap_account';
    const SCENARIO_UPLOAD_STORE_DOCUMENT = 'upload';

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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['owner_first_name', 'owner_last_name', 'owner_email', 'owner_number'], 'required', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],


            [
                [
                    'vendor_sector', 'iban', 'company_name', 'business_type'
                ],
                'required', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT
            ],
            [
                [
                    'identification_file_front_side', 'identification_file_back_side'
                ],
                'required', 'on' => self::SCENARIO_UPLOAD_STORE_DOCUMENT
            ],


            [['commercial_license_file', 'authorized_signature_file'], 'required', 'on' => self::SCENARIO_UPLOAD_STORE_DOCUMENT, 'when' => function ($model) {
                return $model->business_type == 'corp';
            }],

            [['owner_first_name', 'owner_last_name'], 'string', 'min' => 3, 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],
            [['identification_file_id_back_side', 'identification_file_id_front_side', 'authorized_signature_file_id', 'commercial_license_file_id'], 'safe', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],
            [['not_for_profit'], 'number'],
            [['authorized_signature_issuing_date', 'authorized_signature_expiry_date', 'commercial_license_issuing_date', 'commercial_license_expiry_date', 'identification_issuing_date', 'identification_expiry_date'], 'safe', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],
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
                    'identification_issuing_date', 'identification_title',
                    'identification_expiry_date', 'identification_file_back_side', 'identification_file_front_side', 'identification_file_purpose',
                    'business_id', 'business_entity_id', 'wallet_id', 'merchant_id', 'operator_id',
                    'live_api_key', 'test_api_key', 'developer_id', 'live_public_key', 'test_public_key'
                ],
                'string', 'max' => 255
            ],
            ['iban', 'string', 'min' => 10, 'max' => 34, 'message' => 'The IBAN must be at least 10 characters long.', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],

            ['iban', 'match', 'pattern' => '/^[a-zA-Z0-9-]+$/', 'message' => 'Please check the IBAN, we might not support transfering to this bank.', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],
            [['restaurant_commercial_license_file', 'owner_identification_file_front_side', 'owner_identification_file_back_side'], 'file', 'skipOnEmpty' => true, 'on' => self::SCENARIO_UPLOAD_STORE_DOCUMENT],
            [['restaurant_authorized_signature_file', 'owner_identification_file_front_side', 'owner_identification_file_back_side'], 'file', 'skipOnEmpty' => true, 'on' => self::SCENARIO_UPLOAD_STORE_DOCUMENT],
            [['name', 'name_ar', 'support_delivery', 'support_pick_up', 'restaurant_payments_method', 'restaurant_domain', 'restaurant_email', 'store_branch_name', 'app_id'], 'required', 'on' => 'create'],
            [['name', 'name_ar'], 'required', 'on' => 'default'],
            [['name', 'owner_number', 'restaurant_domain', 'currency_id', 'country_id'], 'required', 'on' => self::SCENARIO_CREATE_STORE_BY_AGENT],
            ['name', 'match', 'pattern' => '/^[a-zA-Z0-9-\s]+$/', 'message' => 'Your store name can only contain alphanumeric characters', 'on' => self::SCENARIO_CREATE_STORE_BY_AGENT],
            ['restaurant_domain', 'match', 'pattern' => '/^[a-zA-Z0-9-]+$/', 'message' => 'Your store url can only contain alphanumeric characters', 'on' => self::SCENARIO_CREATE_STORE_BY_AGENT],
            [['restaurant_domain'], 'url', 'except' => self::SCENARIO_CREATE_STORE_BY_AGENT],
            [['restaurant_domain'], 'string', 'min' => 3, 'max' => 20, 'on' => self::SCENARIO_CREATE_STORE_BY_AGENT],
            [['restaurant_thumbnail_image', 'restaurant_logo'], 'file', 'extensions' => 'jpg, jpeg , png, pdf', 'maxFiles' => 1],
            [['restaurant_delivery_area', 'restaurant_payments_method'], 'safe'],
            [['restaurant_status', 'support_delivery', 'support_pick_up', 'hide_request_driver_button', 'sitemap_require_update', 'version'], 'integer', 'min' => 0],
            [['schedule_interval'], 'integer', 'min' => 5],
            ['restaurant_status', 'in', 'range' => [self::RESTAURANT_STATUS_OPEN, self::RESTAURANT_STATUS_BUSY, self::RESTAURANT_STATUS_CLOSED]],
            ['store_layout', 'in', 'range' => [self::STORE_LAYOUT_LIST_FULLWIDTH, self::STORE_LAYOUT_GRID_FULLWIDTH, self::STORE_LAYOUT_CATEGORY_FULLWIDTH, self::STORE_LAYOUT_LIST_HALFWIDTH, self::STORE_LAYOUT_GRID_HALFWIDTH, self::STORE_LAYOUT_CATEGORY_HALFWIDTH]],
            ['phone_number_display', 'in', 'range' => [self::PHONE_NUMBER_DISPLAY_ICON, self::PHONE_NUMBER_DISPLAY_SHOW_PHONE_NUMBER, self::PHONE_NUMBER_DISPLAY_DONT_SHOW_PHONE_NUMBER]],
            [['restaurant_created_at', 'restaurant_updated_at', 'has_deployed', 'tap_queue_id'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['default_language'], 'string', 'max' => 2],
            [['custom_css'], 'string'],
            [['platform_fee', 'warehouse_fee'], 'number'],
            [['instagram_url'], 'url'],
            [['export_orders_data_in_specific_date_range', 'export_sold_items_data_in_specific_date_range', 'google_analytics_id', 'facebook_pixil_id', 'snapchat_pixil_id', 'site_id'], 'safe'],
            [['name', 'name_ar', 'tagline', 'tagline_ar', 'thumbnail_image', 'logo', 'app_id', 'armada_api_key', 'mashkor_branch_id', 'store_branch_name', 'live_public_key', 'test_public_key', 'company_name'], 'string', 'max' => 255],

            [['live_public_key', 'test_public_key'], 'default', 'value' => null],
            [['country_id', 'currency_id', 'owner_phone_country_code', 'phone_number_country_code', 'retention_email_sent', 'enable_gift_message'], 'integer'],

            [['phone_number', 'owner_number'], 'string', 'min' => 6, 'max' => 20],
            [['phone_number', 'owner_number'], 'number'],

            [['owner_number'], PhoneInputValidator::className (), 'message' => 'Please insert a valid phone number', 'on' => [self::SCENARIO_CREATE_TAP_ACCOUNT, self::SCENARIO_CREATE_STORE_BY_AGENT]],
            [['phone_number'], PhoneInputValidator::className (), 'message' => 'Please insert a valid phone number'],


            //  ['currency_id', function ($attribute, $params, $validator) {
            //     if ($this->getOrders()->exists())
            //         $this->addError($attribute, "You've made your first sale, so you need to contact support if you want to change your currency.");
            // }],

            [['schedule_interval'], 'required', 'when' => function ($model) {
                return $model->schedule_order;
            }
            ],
            [['restaurant_email_notification', 'schedule_order', 'phone_number_display', 'store_layout', 'show_opening_hours', 'is_tap_enable'], 'integer'],
            ['restaurant_email', 'email'],
            [['restaurant_uuid', 'restaurant_domain', 'name'], 'unique'],
            [['tap_queue_id'], 'exist', 'skipOnError' => true, 'targetClass' => TapQueue::className (), 'targetAttribute' => ['tap_queue_id' => 'tap_queue_id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className (), 'targetAttribute' => ['country_id' => 'country_id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className (), 'targetAttribute' => ['currency_id' => 'currency_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'restaurant_uuid' => 'Restaurant Uuid',
            'country_id' => 'Country',
            'currency_id' => 'Store currency',
            'name' => 'Store name in English',
            'name_ar' => 'Store name in Arabic',
            'tagline' => 'Tagline in English',
            'tagline_ar' => 'Tagline in Arabic',
            'restaurant_domain' => 'Store Url',
            'app_id' => 'App id',
            'restaurant_payments_method' => 'Payment method',
            'restaurant_status' => 'Store Status',
            'thumbnail_image' => 'Header Image',
            'logo' => 'Logo',
            'restaurant_thumbnail_image' => 'Header Image',
            'restaurant_logo' => 'Logo',
            'support_delivery' => 'Support Delivery',
            'support_pick_up' => 'Support Pick Up',
            'hide_request_driver_button' => 'Hide request driver button',
            'restaurant_delivery_area' => 'Delivery Areas',
            'export_orders_data_in_specific_date_range' => 'Export orders data in a specific date range',
            'export_sold_items_data_in_specific_date_range' => 'Export sold items data in a specific date range',
            'phone_number' => "Store's phone number",
            'restaurant_email' => "Store's Email",
            'restaurant_created_at' => 'Store Created At',
            'restaurant_updated_at' => 'Store Updated At',
            'armada_api_key' => 'Armada Api Key',
            'armada_branch_id' => 'Mashkor Branch ID',
            'restaurant_email_notification' => 'Email notification',
            'show_opening_hours' => 'Show Opening hours',
            'phone_number_display' => 'Phone number display',
            'store_branch_name' => 'Branch name',
            'custom_css' => 'Custom css',
            'platform_fee' => 'Platform fee',
            'warehouse_fee' => 'Warehouse fee',
            'company_name' => 'Company name',
            'store_layout' => 'Store layout',
            'google_analytics_id' => 'Google Analytics ID',
            'facebook_pixil_id' => 'Facebook Pixil ID',
            'instagram_url' => 'Instagram url',
            'schedule_order' => 'Schedule order',
            'schedule_interval' => 'Schedule interval',
            'business_type' => 'Account type',
            'vendor_sector' => 'Vendor sector',
            'license_number' => 'License number',
            'owner_identification_file_front_side' => 'Civil ID Front side',
            'owner_identification_file_back_side' => 'Civil ID Back side',


            'authorized_signature_issuing_date' => 'Authorized Signature Issuing Date',
            'authorized_signature_expiry_date' => 'Authorized Signature Expiry Date',
            'authorized_signature_file' => 'Authorized signature',
            'restaurant_authorized_signature_file' => 'Authorized Signature',
            'authorized_signature_title' => 'Authorized Signature Title',
            'authorized_signature_file_purpose' => 'Authorized Signature File Purpose',
            'commercial_license_issuing_date' => 'Commercial License Issuing Date',
            'commercial_license_expiry_date' => 'Commercial License Expiry Date',
            'commercial_license_file' => 'License copy',
            'restaurant_commercial_license_file' => 'License copy ',
            'commercial_license_title' => 'Commercial License Title',
            'commercial_license_file_purpose' => 'Commercial License File Purpose',
            'iban' => 'IBAN',
            'owner_first_name' => 'First Name',
            'owner_last_name' => 'Last Name',
            'owner_email' => 'Email Address',
            'owner_number' => 'Owner Phone Number',
            'identification_issuing_date' => 'Identification Issuing Date',
            'identification_expiry_date' => 'Identification Expiry Date',
            'identification_file_front_side' => ' National ID File front side',
            'identification_file_back_side' => ' National ID File back side',
            'identification_title' => 'Identification Title',
            'identification_file_purpose' => 'Identification File Purpose',

            'live_api_key' => 'Live secret key',
            'test_api_key' => 'Test secret key',
            'default_language' => 'Default Language',

        ];
    }

    /**
     *
     * @return type
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className (),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'restaurant_uuid',
                ],
                'value' => function () {
                    if (!$this->restaurant_uuid)
                        $this->restaurant_uuid = 'rest_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();

                    return $this->restaurant_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className (),
                'createdAtAttribute' => 'restaurant_created_at',
                'updatedAtAttribute' => 'restaurant_updated_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => \borales\extensions\phoneInput\PhoneInputBehavior::className (),
                // 'attributes' => [
                //           ActiveRecord::EVENT_BEFORE_INSERT => ['owner_phone_country_code', 'owner_number'],
                //       ],
                'countryCodeAttribute' => 'owner_phone_country_code',
                'phoneAttribute' => 'owner_number',
            ],
            [
                'class' => \borales\extensions\phoneInput\PhoneInputBehavior::className (),
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
     * Upload a File to cloudinary
     * @param type $imageURL
     */
    public function uploadFileToCloudinary($file_path, $attribute)
    {

        $filename = Yii::$app->security->generateRandomString ();

        try {

            $result = Yii::$app->cloudinaryManager->upload (
                $file_path, [
                    'public_id' => "restaurants/" . $this->restaurant_uuid . "/private_documents/" . $filename
                ]
            );

            if ($result || count ($result) > 0) {
                //delete the file from temp folder
                unlink ($file_path);
                $this[$attribute] = basename ($result['url']);
            }
        } catch (\Cloudinary\Error $err) {
            Yii::error ('Error when uploading restaurant document to Cloudinary: ' . json_encode ($err));
        }

    }

    public function uploadDocumentsToTap()
    {


        //Upload Authorized Signature file
        if ($this->authorized_signature_file && $this->authorized_signature_file_purpose && $this->authorized_signature_title) {

            $tmpFile = sys_get_temp_dir () . '/' . $this->authorized_signature_file;

            if (!file_put_contents ($tmpFile, file_get_contents ($this->getAuthorizedSignaturePhoto ())))
                return Yii::error ('Error reading authorized signature document: ');

            $response = Yii::$app->tapPayments->uploadFileToTap ($tmpFile, $this->authorized_signature_file_purpose, $this->authorized_signature_title);

            @unlink ($tmpFile);


            if ($response->isOk)
                $this->authorized_signature_file_id = $response->data['id'];

            else
                return Yii::error ('Error when uploading authorized signature document: ' . json_encode ($response->data));
        }

        //Upload commercial_license file
        if ($this->commercial_license_file && $this->commercial_license_file_purpose && $this->commercial_license_title) {

            $commercialLicenseTmpFile = sys_get_temp_dir () . '/' . $this->commercial_license_file;

            if (!file_put_contents ($commercialLicenseTmpFile, file_get_contents ($this->getCommercialLicensePhoto ())))
                return Yii::error ('Error reading commercial license document: ');


            $response = Yii::$app->tapPayments->uploadFileToTap (
                $commercialLicenseTmpFile, $this->commercial_license_file_purpose, $this->commercial_license_title);

            @unlink ($commercialLicenseTmpFile);

            if ($response->isOk)
                $this->commercial_license_file_id = $response->data['id'];

            else
                return Yii::error ('Error when uploading commercial license document: ' . json_encode ($response->data));
        }

        //Upload Owner civil id front side
        if ($this->identification_file_front_side && $this->identification_file_purpose && $this->identification_title) {

            $civilIdFrontSideTmpFile = sys_get_temp_dir () . '/' . $this->identification_file_front_side;

            if (!file_put_contents ($civilIdFrontSideTmpFile, file_get_contents ($this->getCivilIdFrontSidePhoto ())))
                return Yii::error ('Error reading civil id (front side): ');


            $response = Yii::$app->tapPayments->uploadFileToTap (
                $civilIdFrontSideTmpFile, $this->identification_file_purpose, $this->identification_title);

            @unlink ($civilIdFrontSideTmpFile);


            if ($response->isOk)
                $this->identification_file_id_front_side = $response->data['id'];
            else
                return Yii::error ('Error when uploading civil id (front side): ' . json_encode ($response->data));

        }

        //Upload Owner civil id back side
        if ($this->identification_file_back_side && $this->identification_file_purpose && $this->identification_title) {

            $civilIdBackSideTmpFile = sys_get_temp_dir () . '/' . $this->identification_file_back_side;

            if (!file_put_contents ($civilIdBackSideTmpFile, file_get_contents ($this->getCivilIdBackSidePhoto ())))
                return Yii::error ('Error reading civil id (back side): ');


            $response = Yii::$app->tapPayments->uploadFileToTap (
                $civilIdBackSideTmpFile, $this->identification_file_purpose, $this->identification_title);

            @unlink ($civilIdBackSideTmpFile);

            if ($response->isOk)
                $this->identification_file_id_back_side = $response->data['id'];
            else
                return Yii::error ('Error when uploading civil id (back side): ' . json_encode ($response->data));
        }

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
     * Return authorized_signature_file
     * @return string
     */
    public function getAuthorizedSignaturePhoto()
    {
        $photo_url = [];

        if ($this->authorized_signature_file) {

            $url = 'https://res.cloudinary.com/plugn/image/upload/restaurants/'
                . $this->restaurant_uuid . '/private_documents/'
                . $this->authorized_signature_file;
            $photo_url = $url;
        }

        return $photo_url;
    }

    /**
     * Create an account for vendor on tap
     */
    public function createAnAccountOnTap()
    {


        //Upload documents file on our server before we create an account on tap we gonaa delete them
        $this->uploadDocumentsToTap ();


        //Create a business for a vendor on Tap
        $businessApiResponse = Yii::$app->tapPayments->createBussiness ($this);

        if ($businessApiResponse->isOk) {

            $this->business_id = $businessApiResponse->data['id'];
            $this->business_entity_id = $businessApiResponse->data['entity']['id'];
            $this->developer_id = $businessApiResponse->data['entity']['operator']['developer_id'];
        } else {
            Yii::error ('Error while create Business [' . $this->name . '] ' . json_encode ($businessApiResponse->data));
            return false;
        }

        //Create a merchant on Tap
        $merchantApiResponse = Yii::$app->tapPayments->createMergentAccount ($this->company_name, $this->currency->code, $this->business_id, $this->business_entity_id, $this->iban);


        if ($merchantApiResponse->isOk) {
            $this->merchant_id = $merchantApiResponse->data['id'];
            $this->wallet_id = $merchantApiResponse->data['wallets']['id'];
        } else {
            Yii::error ('Error while create Merchant #1 ' . json_encode ($merchantApiResponse->data));
            if ($merchantApiResponse->data['message'] == 'Profile Name already exists') {
                $merchantApiResponse = Yii::$app->tapPayments->createMergentAccount ($this->company_name . '-' . $this->country->iso, $this->currency->code, $this->business_id, $this->business_entity_id, $this->iban);

                if ($merchantApiResponse->isOk) {
                    $this->merchant_id = $merchantApiResponse->data['id'];
                    $this->wallet_id = $merchantApiResponse->data['wallets']['id'];
                } else
                    return Yii::error ('Error while create Merchant [ ' . $this->name . '] ' . json_encode ($merchantApiResponse->data));
            }
        }

        //Create an Operator
        $operatorApiResponse = Yii::$app->tapPayments->createAnOperator ($this->name, $this->wallet_id, $this->developer_id);

        if ($operatorApiResponse->isOk) {
            $this->operator_id = $operatorApiResponse->data['id'];
            $this->test_api_key = $operatorApiResponse->data['api_credentials']['test']['secret'];
            $this->test_public_key = $operatorApiResponse->data['api_credentials']['test']['public'];

            if (array_key_exists ('live', $operatorApiResponse->data['api_credentials'])) {
                $this->live_api_key = $operatorApiResponse->data['api_credentials']['live']['secret'];
                $this->live_public_key = $operatorApiResponse->data['api_credentials']['live']['public'];
            }

            \Yii::info ($this->name . " has just created TAP account", __METHOD__);
            $this->save ();
            return true;
        } else {
            Yii::error ('Error while create Operator  [' . $this->name . '] ' . json_encode ($operatorApiResponse->data));
            return false;
        }
    }

    /**
     * save restaurant payment method
     */
    public function saveRestaurantPaymentMethod($payments_method = null)
    {

        if ($payments_method) {

            $sotred_restaurant_payment_method = RestaurantPaymentMethod::find ()
                ->where (['restaurant_uuid' => $this->restaurant_uuid])
                ->all ();


            foreach ($sotred_restaurant_payment_method as $restaurant_payment_method) {
                if (!in_array ($restaurant_payment_method->payment_method_id, $payments_method)) {
                    RestaurantPaymentMethod::deleteAll (['restaurant_uuid' => $this->restaurant_uuid, 'payment_method_id' => $restaurant_payment_method->payment_method_id]);
                }
            }

            foreach ($payments_method as $payment_method_id) {
                $payments_method = new RestaurantPaymentMethod();
                $payments_method->payment_method_id = $payment_method_id;
                $payments_method->restaurant_uuid = $this->restaurant_uuid;
                $payments_method->save ();
            }
        } else {
            RestaurantPaymentMethod::deleteAll (['restaurant_uuid' => $this->restaurant_uuid]);
        }
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
    public function uploadLogo($imageURL)
    {

        $filename = Yii::$app->security->generateRandomString ();

        try {
            $result = Yii::$app->cloudinaryManager->upload (
                $imageURL, [
                    'public_id' => "restaurants/" . $this->restaurant_uuid . "/logo/" . $filename
                ]
            );

            //Delete old store's logo
            if ($this->logo) {
                $this->deleteRestaurantLogo ();
            }


            if ($result || count ($result) > 0) {
                $this->logo = basename ($result['url']);
                $this->save ();
            }

            unlink ($imageURL);


        } catch (\Cloudinary\Error $err) {
            Yii::error ("Error when uploading logo photos to Cloudinry: " . json_encode ($err));
        }
    }

    /**
     * Upload thumbnailImage  to cloudinary
     * @param type $imageURL
     */
    public function uploadThumbnailImage($imageURL)
    {

        $filename = Yii::$app->security->generateRandomString ();

        try {
            $result = Yii::$app->cloudinaryManager->upload (
                $imageURL, [
                    'public_id' => "restaurants/" . $this->restaurant_uuid . "/thumbnail-image/" . $filename
                ]
            );


            //Delete old store's ThumbnailImage
            if ($this->thumbnail_image) {
                $this->deleteRestaurantThumbnailImage ();
            }

            if ($result || count ($result) > 0) {
                $this->thumbnail_image = basename ($result['url']);
                $this->save ();
            }

            unlink ($imageURL);


        } catch (\Cloudinary\Error $err) {
            Yii::error ("Error when uploading thumbnail photos to Cloudinry: " . json_encode ($err));
        }
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields ();

        // remove fields that contain sensitive information
        unset($fields['restaurant_email_notification']);
        unset($fields['developer_id']);
        unset($fields['site_id']);
        unset($fields['retention_email_sent']);
        unset($fields['hide_request_driver_button']);
        unset($fields['platform_fee']);
        unset($fields['warehouse_fee']);
        unset($fields['store_branch_name']);
        unset($fields['armada_api_key']);
        unset($fields['mashkor_branch_id']);
        unset($fields['app_id']);
        unset($fields['restaurant_status']);
        unset($fields['vendor_sector']);
        unset($fields['business_id']);
        unset($fields['business_entity_id']);
        unset($fields['wallet_id']);
        unset($fields['merchant_id']);
        unset($fields['operator_id']);
        unset($fields['live_api_key']);
        unset($fields['test_api_key']);
        // unset($fields['live_public_key']);
        unset($fields['test_public_key']);
        unset($fields['sitemap_require_update']);
        unset($fields['business_type']);
        unset($fields['restaurant_email']);
        unset($fields['license_number']);
        unset($fields['not_for_profit']);
        unset($fields['authorized_signature_issuing_date']);
        unset($fields['authorized_signature_issuing_date']);
        unset($fields['authorized_signature_expiry_date']);
        unset($fields['authorized_signature_title']);
        unset($fields['authorized_signature_file']);
        unset($fields['authorized_signature_file_id']);
        unset($fields['authorized_signature_file_purpose']);
        unset($fields['commercial_license_issuing_date']);
        unset($fields['commercial_license_issuing_date']);
        unset($fields['commercial_license_expiry_date']);
        unset($fields['commercial_license_title']);
        unset($fields['commercial_license_file']);
        unset($fields['commercial_license_file_id']);
        unset($fields['commercial_license_file_purpose']);
        unset($fields['iban']);
        unset($fields['owner_first_name']);
        unset($fields['owner_last_name']);
        unset($fields['owner_email']);
        unset($fields['owner_number']);
        unset($fields['has_deployed']);
        unset($fields['tap_queue_id']);
        unset($fields['is_tap_enable']);
        unset($fields['company_name']);
        unset($fields['owner_phone_country_code']);
        unset($fields['identification_issuing_date']);
        unset($fields['identification_expiry_date']);
        unset($fields['identification_file_front_side']);
        unset($fields['identification_file_back_side']);
        unset($fields['identification_file_id_front_side']);
        unset($fields['identification_file_id_back_side']);
        unset($fields['identification_title']);
        unset($fields['identification_file_purpose']);
        unset($fields['restaurant_created_at']);
        unset($fields['restaurant_updated_at']);

        return $fields;
    }

    public function beforeSave($insert)
    {

        if ($this->scenario == self::SCENARIO_CREATE_STORE_BY_AGENT && $insert) {

            $store_name = strtolower (str_replace (' ', '_', $this->name));
            $store_domain = strtolower (str_replace (' ', '_', $this->restaurant_domain));
            $this->app_id = 'store.plugn.' . $store_domain;
            $this->restaurant_domain = 'https://' . $store_domain . '.plugn.store';
            $this->store_branch_name = $store_name;

            $isDomainExist = self::find ()->where (['restaurant_domain' => $this->restaurant_domain])->exists ();

            if ($isDomainExist)
                return $this->addError ('restaurant_domain', 'Another store is already using this domain');


        }


        if ($this->live_api_key && $this->test_api_key)
            $this->is_tap_enable = 1;
        else
            $this->is_tap_enable = 0;


        if ($this->scenario == self::SCENARIO_UPLOAD_STORE_DOCUMENT) {
            //delete tmp files
            $this->deleteTempFiles ();
        }


        return parent::beforeSave ($insert);
    }

    /**
     * Deletes the files associated with this project
     */
    public function deleteTempFiles()
    {
        if ($this->authorized_signature_file && file_exists (Yii::getAlias ('@privateDocuments') . "/uploads/" . $this->authorized_signature_file)) {
            $this->uploadFileToCloudinary (Yii::getAlias ('@privateDocuments') . "/uploads/" . $this->authorized_signature_file, 'authorized_signature_file');
        }

        if ($this->commercial_license_file && file_exists (Yii::getAlias ('@privateDocuments') . "/uploads/" . $this->commercial_license_file)) {
            $this->uploadFileToCloudinary (Yii::getAlias ('@privateDocuments') . "/uploads/" . $this->commercial_license_file, 'commercial_license_file');
        }

        if ($this->identification_file_front_side && file_exists (Yii::getAlias ('@privateDocuments') . "/uploads/" . $this->identification_file_front_side)) {
            $this->uploadFileToCloudinary (Yii::getAlias ('@privateDocuments') . "/uploads/" . $this->identification_file_front_side, 'identification_file_front_side');
        }

        if ($this->identification_file_back_side && file_exists (Yii::getAlias ('@privateDocuments') . "/uploads/" . $this->identification_file_back_side)) {
            $this->uploadFileToCloudinary (Yii::getAlias ('@privateDocuments') . "/uploads/" . $this->identification_file_back_side, 'identification_file_back_side');
        }
    }

    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave ($insert, $changedAttributes);


        if ($this->scenario == self::SCENARIO_CREATE_STORE_BY_AGENT && $insert) {

            //Create a new record in queue table
            $queue_model = new Queue();
            $queue_model->restaurant_uuid = $this->restaurant_uuid;
            $queue_model->queue_status = Queue::QUEUE_STATUS_PENDING;

            if (!$queue_model->save ())
                Yii::error ('Queue Error:' . json_encode ($queue_model->errors));

        }


        if ($insert) {

            $freePlan = Plan::find ()->where (['valid_for' => 0])->one ();

            $subscription = new Subscription();
            $subscription->restaurant_uuid = $this->restaurant_uuid;
            $subscription->plan_id = $freePlan->plan_id; //Free plan by default
            $subscription->subscription_status = Subscription::STATUS_ACTIVE; //Free plan by default
            $subscription->save ();


            $restaurant_theme = new RestaurantTheme();
            $restaurant_theme->restaurant_uuid = $this->restaurant_uuid;
            $restaurant_theme->save ();


            //Add opening hrs
            for ($i = 0; $i < 7; ++$i) {
                $opening_hour = new OpeningHour();
                $opening_hour->restaurant_uuid = $this->restaurant_uuid;
                $opening_hour->day_of_week = $i;
                $opening_hour->open_at = 0;
                $opening_hour->close_at = '23:59:59';
                $opening_hour->save ();
            }
        }
    }

    /**
     * Return Logo url to dispaly it on backend
     * @return string
     */
    public function getLogo()
    {
        $photo_url = [];

        if ($this->logo) {
            $restaurantName = str_replace (' ', '', $this->name);
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
            $restaurantName = str_replace (' ', '', $this->name);
            $url = 'https://res.cloudinary.com/agent/image/upload/v1579525808/restaurants/'
                . $restaurantName . '/thumbnail-image/'
                . $this->thumbnail_image;
            $photo_url = $url;
        }

        return $photo_url;
    }

    public function isOpen()
    {
        $opening_hour_model = OpeningHour::find ()
            ->where (['restaurant_uuid' => $this->restaurant_uuid, 'day_of_week' => date ('w', strtotime ("now"))])
            ->andWhere (['<=', 'open_at', date ("H:i:s", strtotime ("now"))])
            ->andWhere (['>=', 'close_at', date ("H:i:s", strtotime ("now"))])
            ->orderBy (['open_at' => SORT_ASC])
            ->one ();


        if ($opening_hour_model) {
            if (!$opening_hour_model->is_closed &&
                date ("w", strtotime ("now")) == $opening_hour_model->day_of_week &&
                strtotime ("now") > strtotime ($opening_hour_model->open_at) &&
                strtotime ("now") < strtotime ($opening_hour_model->close_at)
            )
                return true;
        }


        return false;
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            'isOpen' => function ($restaurant) {
                return $restaurant->isOpen ();
            },
            'webLinks' => function ($restaurant) {
                return $restaurant->getWebLinks ()->all ();
            },
            'country' => function ($restaurant) {
                return $restaurant->getCountry ()->one ();
            },
            'currency' => function ($restaurant) {
                return $restaurant->getCurrency ()->one ();
            },
            'supportDelivery' => function ($restaurant) {
                return $restaurant->getAreaDeliveryZones ()->count () > 0 ? 1 : 0;
            },
            'supportPickup' => function ($restaurant) {
                return $restaurant->getPickupBusinessLocations ()->count () > 0 ? 1 : 0;
            },
            'customerGained' => function ($store) {

                return [
                    'customerGainedThisMonth' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), 1)), date ("Y-m-d H:i:s")),

                    'customerGainedLastMonth' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 1, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), 0))),

                    'customerGainedLastTwoMonth' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 2, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m") - 1, 0))),

                    'customerGainedLastThreeMonth' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 3, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m") - 2, 0))),

                    'customerGainedLastFourMonth' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 4, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m") - 3, 0))),

                    'customerGainedLastFiveMonth' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 5, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m") - 4, 0))),

                    'customerGainedLastSixDays' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 6)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 6))),

                    'customerGainedLastFiveDays' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 5)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 5))),

                    'customerGainedLastFourDays' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 4)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 4))),

                    'customerGainedLastThreeDays' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 3)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 3))),

                    'customerGainedLastTwoDays' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 2)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 2))),

                    'customerGainedYesterday' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 1))),

                    'customerGainedToday' => $store
                        ->getCustomerGained (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d"))), date ("Y-m-d H:i:s")),

                ];
            },
            'soldItems' => function ($model) {

                return [
                    'soldItemsLastSixDays' => $model->getOrderItems ()->andWhere (' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 6 DAY) ')->sum ('order_item.qty'),
                    'soldItemsLastFiveDays' => $model->getOrderItems ()->andWhere (' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 5 DAY) ')->sum ('order_item.qty'),
                    'soldItemsLastFourDays' => $model->getOrderItems ()->andWhere (' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 4 DAY) ')->sum ('order_item.qty'),
                    'soldItemsLastThreeDays' => $model->getOrderItems ()->andWhere (' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 3 DAY) ')->sum ('order_item.qty'),
                    'soldItemsLastTwoDays' => $model->getOrderItems ()->andWhere (' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 2 DAY) ')->sum ('order_item.qty'),
                    'soldItemsYesterday' => $model->getOrderItems ()->andWhere (' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 1 DAY) ')->sum ('order_item.qty'),
                    'soldItemsToday' => $model->getOrderItems ()->andWhere (['DATE(order.order_created_at)' => new Expression('CURDATE()')])->sum ('order_item.qty'),

                ];
            },
            'bestSeller' => function ($model) {
                return \common\models\Item::find ()
                    ->where (['restaurant_uuid' => $model->restaurant_uuid])
                    ->orderBy (['unit_sold' => SORT_DESC])
                    ->limit (5)
                    ->select (['item_name', 'item_name_ar', 'unit_sold'])
                    ->all ();
            },
            'revenueGenerated' => function ($store) {

                return [
                    'revenueGeneratedThisMonth' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), 1)), date ("Y-m-d H:i:s")),

                    'revenueGeneratedLastMonth' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 1, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), 0))),

                    'revenueGeneratedLastTwoMonth' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 2, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m") - 1, 0))),

                    'revenueGeneratedLastThreeMonth' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 3, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m") - 2, 0))),

                    'revenueGeneratedLastFourMonth' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 4, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m") - 3, 0))),

                    'revenueGeneratedLastFiveMonth' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 5, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m") - 4, 0))),

                    'revenueGeneratedLastSixDays' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 6)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 6))),

                    'revenueGeneratedLastFiveDays' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 5)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 5))),

                    'revenueGeneratedLastFourDays' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 4)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 4))),

                    'revenueGeneratedLastThreeDays' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 3)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 3))),

                    'revenueGeneratedLastTwoDays' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 2)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 2))),

                    'revenueGeneratedYesterday' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 1))),

                    'revenueGeneratedToday' => $store
                        ->getStoreRevenue (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d"))), date ("Y-m-d H:i:s"))
                ];
            },
            'orderReceived' => function ($store) {

                return [
                    'ordersRecivedThisMonth' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), 1)), date ("Y-m-d H:i:s")),

                    'ordersRecivedLastMonth' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 1, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), 0))),

                    'ordersRecivedLastTwoMonth' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 2, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m") - 1, 0))),

                    'ordersRecivedLastThreeMonth' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 3, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m") - 2, 0))),

                    'ordersRecivedLastFourMonth' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 4, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m") - 3, 0))),

                    'ordersRecivedLastFiveMonth' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m") - 5, 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m") - 4, 0))),

                    'ordersRecivedLastSixDays' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 6)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 6))),

                    'ordersRecivedLastFiveDays' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 5)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 5))),

                    'ordersRecivedLastFourDays' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 4)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 4))),

                    'ordersRecivedLastThreeDays' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 3)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 3))),

                    'ordersRecivedLastTwoDays' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 2)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 2))),

                    'ordersRecivedYesterday' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d") - 1)), date ("Y-m-d H:i:s", mktime (23, 59, 59, date ("m"), date ("d") - 1))),

                    'ordersRecivedToday' => $store
                        ->getOrdersReceived (date ("Y-m-d H:i:s", mktime (00, 00, 0, date ("m"), date ("d"))), date ("Y-m-d H:i:s"))

                ];
            },
        ];
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
            Yii::$app->cloudinaryManager->delete ($imageURL);
        } catch (\Cloudinary\Error $err) {
            Yii::error ('Error while deleting logo photos to Cloudinry: ' . json_encode ($err));
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
            Yii::$app->cloudinaryManager->delete ($imageURL);
        } catch (\Cloudinary\Error $err) {
            Yii::error ('Error while deleting thumbnail image to Cloudinry: ' . json_encode ($err));
        }
    }

    public function beforeDelete()
    {

        $this->deleteRestaurantThumbnailImage ();
        $this->deleteRestaurantLogo ();

        return parent::beforeDelete ();
    }

    /**
     * Promotes current restaurant to busy restaurant while disabling rest
     */
    public function markAsBusy()
    {
        $this->restaurant_status = Restaurant::RESTAURANT_STATUS_BUSY;
        $this->save (false);
    }

    /**
     * Promotes current restaurant to open restaurant while disabling rest
     */
    public function markAsOpen()
    {
        $this->restaurant_status = Restaurant::RESTAURANT_STATUS_OPEN;
        $this->save (false);
    }

    /**
     * save restaurant delivery areas
     */
    public function saveRestaurantDeliveryArea($delivery_areas)
    {

        RestaurantDelivery::deleteAll (['restaurant_uuid' => $this->restaurant_uuid]);

        foreach ($delivery_areas as $area_id) {
            $delivery_area = new RestaurantDelivery();
            $delivery_area->area_id = $area_id;
            $delivery_area->restaurant_uuid = $this->restaurant_uuid;
            $delivery_area->save ();
        }
    }

    public function getTotalCustomersByWeek()
    {
        $customer_data = [];

        $date_start = strtotime ('-6 days');//date('w')

        for ($i = 0; $i < 7; $i++) {
            $date = date ('Y-m-d', $date_start + ($i * 86400));

            $customer_data[date ('w', strtotime ($date))] = array(
                'day' => date ('D', strtotime ($date)),
                'total' => 0
            );
        }

        $rows = $this->getCustomers()
            ->select(new Expression('customer_created_at, COUNT(*) as total'))
            ->andWhere(new Expression("DATE(customer_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->groupBy(new Expression('DAYNAME(customer_created_at)'))
            ->asArray()
            ->all();

        foreach ($rows as $result) {
            $customer_data[date ('w', strtotime ($result['customer_created_at']))] = array(
                'day' => date ('D', strtotime ($result['customer_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_customer_gained = $this->getCustomers()
            ->andWhere(new Expression("date(customer_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->count();

        return [
            'customer_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int) $number_of_all_customer_gained
        ];
    }

    public function getTotalRevenueByWeek()
    {
        $revenue_generated_chart_data = [];

        $date_start = strtotime ('-6 days');//date('w')

        for ($i = 0; $i < 7; $i++) {
            $date = date ('Y-m-d', $date_start + ($i * 86400));

            $revenue_generated_chart_data[date ('w', strtotime ($date))] = array(
                'day' => date ('D', strtotime ($date)),
                'total' => 0
            );
        }

        $rows = $this->getOrders ()
            ->activeOrders ($this->restaurant_uuid)
            ->select (new Expression('order.order_created_at, SUM(`total_price`) as total'))
            ->andWhere (new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->groupBy (new Expression('DAY(order.order_created_at)'))
            ->asArray ()
            ->all ();

        foreach ($rows as $result) {
            $revenue_generated_chart_data[date ('w', strtotime ($result['order_created_at']))] = array(
                'day' => date ('D', strtotime ($result['order_created_at'])),
                'total' => (float) $result['total']
            );
        }

        $number_of_all_revenue_generated = $this->getOrders()
            ->activeOrders($this->restaurant_uuid)
            ->andWhere(new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->sum('total_price');

        return [
            'revenue_generated_chart_data' => array_values($revenue_generated_chart_data),
            'number_of_all_revenue_generated' => (int) $number_of_all_revenue_generated
        ];
    }

    public function getTotalOrdersByWeek()
    {
        $orders_received_chart_data = [];

        $date_start = strtotime ('-6 days');//date('w')

        for ($i = 0; $i < 7; $i++) {
            $date = date ('Y-m-d', $date_start + ($i * 86400));

            $orders_received_chart_data[date ('w', strtotime ($date))] = array(
                'day' => date ('D', strtotime ($date)),
                'total' => 0
            );
        }

        $rows = $this->getOrders ()
            ->activeOrders ($this->restaurant_uuid)
            ->select (new Expression('order_created_at, COUNT(*) as total'))
            ->andWhere (new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->groupBy (new Expression('DAY(order.order_created_at)'))
            ->asArray ()
            ->all ();

        foreach ($rows as $result) {
            $orders_received_chart_data[date ('w', strtotime ($result['order_created_at']))] = array(
                'day' => date ('D', strtotime ($result['order_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_orders_received = $this->getOrders()
            ->activeOrders($this->restaurant_uuid)
            ->andWhere(new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->count();

        return [
            'orders_received_chart_data' => array_values ($orders_received_chart_data),
            'number_of_all_orders_received' => (int) $number_of_all_orders_received
        ];
    }

    public function getTotalSoldItemsByWeek()
    {
        $sold_item_chart_data = [];

        $date_start = strtotime ('-6 days');//date('w')

        for ($i = 0; $i < 7; $i++) {
            $date = date ('Y-m-d', $date_start + ($i * 86400));

            $sold_item_chart_data[date ('w', strtotime ($date))] = array(
                'day' => date ('D', strtotime ($date)),
                'total' => 0
            );
        }

        $rows = $this->getSoldOrderItems ()
            ->select ('order_item_created_at, SUM(order_item.qty) as total')
            ->andWhere (new Expression("DATE(order_item_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->groupBy (new Expression('DAY(order_item_created_at)'))
            ->asArray ()
            ->all ();

        foreach ($rows as $result) {
            $sold_item_chart_data[date ('w', strtotime ($result['order_item_created_at']))] = array(
                'day' => date ('D', strtotime ($result['order_item_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_sold_item = $this->getSoldOrderItems()
            ->andWhere(new Expression("DATE(order_item_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->sum('order_item.qty');

        return [
            'sold_item_chart_data' => array_values($sold_item_chart_data),
            'number_of_all_sold_item' => (int) $number_of_all_sold_item
        ];
    }

    public function getTotalCustomersByMonth()
    {
        $customer_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-1 month')) . '-1';

        for ($i = 1; $i <= date('t', strtotime($date_start)); $i++) {
            $customer_data[$i] = array(
                'day'   => $i,
                'total' => 0
            );
        }

        $rows = $this->getCustomers()
            ->select(new Expression('customer_created_at, COUNT(*) as total'))
            ->andWhere('
                YEAR(customer_created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND 
                MONTH(customer_created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->groupBy(new Expression('DAY(customer_created_at)'))
            ->asArray()
            ->all();

        foreach ($rows as $result) {
            $customer_data[date ('j', strtotime ($result['customer_created_at']))] = array(
                'day' => (int) date ('j', strtotime ($result['customer_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_customer_gained = $this->getCustomers()
            ->andWhere('
                YEAR(customer_created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND 
                MONTH(customer_created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->count();

        return [
            'customer_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int) $number_of_all_customer_gained
        ];
    }

    public function getTotalRevenueByMonth()
    {
        $revenue_generated_chart_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-1 month')) . '-1';

        for ($i = 1; $i <= date('t', strtotime($date_start)); $i++) {
            $revenue_generated_chart_data[$i] = array(
                'day'   => $i,
                'total' => 0
            );
        }

        $rows = $this->getOrders ()
            ->activeOrders ($this->restaurant_uuid)
            ->select (new Expression('order.order_created_at, SUM(`total_price`) as total'))
            ->andWhere('
                YEAR(order.order_created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND 
                MONTH(order.order_created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->groupBy (new Expression('DAY(order.order_created_at)'))
            ->asArray ()
            ->all ();

        foreach ($rows as $result) {
            $revenue_generated_chart_data[date ('j', strtotime ($result['order_created_at']))] = array(
                'day' => (int) date ('j', strtotime ($result['order_created_at'])),
                'total' => (float) $result['total']
            );
        }

        $number_of_all_revenue_generated = $this->getOrders()
            ->activeOrders($this->restaurant_uuid)
            ->andWhere('
                YEAR(order.order_created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND 
                MONTH(order.order_created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->sum('total_price');

        return [
            'revenue_generated_chart_data' => array_values($revenue_generated_chart_data),
            'number_of_all_revenue_generated' => (int) $number_of_all_revenue_generated
        ];
    }

    public function getTotalOrdersByMonth()
    {
        $orders_received_chart_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-1 month')) . '-1';

        for ($i = 1; $i <= date('t', strtotime($date_start)); $i++) {
            $orders_received_chart_data[$i] = array(
                'day'   => $i,
                'total' => 0
            );
        }

        $rows = $this->getOrders ()
            ->activeOrders ($this->restaurant_uuid)
            ->select (new Expression('order_created_at, COUNT(*) as total'))
            ->andWhere('
                YEAR(order.order_created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND 
                MONTH(order.order_created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->groupBy (new Expression('DAY(order.order_created_at)'))
            ->asArray ()
            ->all ();

        foreach ($rows as $result) {
            $orders_received_chart_data[date ('j', strtotime ($result['order_created_at']))] = array(
                'day' => (int) date ('j', strtotime ($result['order_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_orders_received = $this->getOrders()
            ->activeOrders($this->restaurant_uuid)
            ->andWhere('
                YEAR(order.order_created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND 
                MONTH(order.order_created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->count();

        return [
            'orders_received_chart_data' => array_values ($orders_received_chart_data),
            'number_of_all_orders_received' => (int) $number_of_all_orders_received
        ];
    }

    public function getTotalSoldItemsByMonth()
    {
        $sold_item_chart_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-1 month')) . '-1';

        for ($i = 1; $i <= date('t', strtotime($date_start)); $i++) {
            $sold_item_chart_data[$i] = array(
                'day'   => $i,
                'total' => 0
            );
        }

        $rows = $this->getSoldOrderItems ()
            ->select ('order_item_created_at, SUM(order_item.qty) as total')
            ->andWhere('
                YEAR(`order_item_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND 
                MONTH(`order_item_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->groupBy (new Expression('DATE(order_item_created_at)'))
            ->asArray ()
            ->all ();

        foreach ($rows as $result) {
            $sold_item_chart_data[date ('j', strtotime ($result['order_item_created_at']))] = array(
                'day' => (int) date ('j', strtotime ($result['order_item_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_sold_item = $this->getSoldOrderItems()
            ->andWhere('
                YEAR(`order_item_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND 
                MONTH(`order_item_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->sum('order_item.qty');

        return [
            'sold_item_chart_data' => array_values($sold_item_chart_data),
            'number_of_all_sold_item' => (int) $number_of_all_sold_item
        ];
    }

    public function getTotalCustomersByMonths($months)
    {
        $customer_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-'.$months.' month')) . '-1';
        $date_end = date('Y') . '-' . date('m') . '-1';

        for ($i = 0; $i < $months; $i++) {

            $month = date('m', strtotime('-'.($months - $i).' month'));

            $customer_data[$month] = array(
                'month'   => (int) $month,
                'total' => 0
            );
        }

        $rows = $this->getCustomers()
            ->select(new Expression('customer_created_at, COUNT(*) as total'))
            ->andWhere('DATE(`customer_created_at`) >= DATE("'.$date_start.'") AND DATE(`customer_created_at`) < DATE("'.$date_end.'")')
            ->groupBy(new Expression('MONTH(customer_created_at)'))
            ->asArray()
            ->all();

        foreach ($rows as $result) {
            $customer_data[date ('m', strtotime ($result['customer_created_at']))] = array(
                'month' => (int) date ('m', strtotime ($result['customer_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_customer_gained = $this->getCustomers()
            ->andWhere('DATE(`customer_created_at`) >= DATE("'.$date_start.'") AND DATE(`customer_created_at`) < DATE("'.$date_end.'")')
            ->count();

        return [
            'customer_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int) $number_of_all_customer_gained
        ];
    }

    public function getTotalRevenueByMonths($months)
    {
        $revenue_generated_chart_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-'.$months.' month')) . '-1';
        $date_end = date('Y') . '-' . date('m') . '-1';

        for ($i = 0; $i < $months; $i++) {

            $month = date('m', strtotime('-'.($months - $i).' month'));

            $revenue_generated_chart_data[$month] = array(
                'month'   => (int) $month,
                'total' => 0
            );
        }

        $rows = $this->getOrders ()
            ->activeOrders ($this->restaurant_uuid)
            ->select (new Expression('order.order_created_at, SUM(`total_price`) as total'))
            ->andWhere('DATE(`order_created_at`) >= DATE("'.$date_start.'") AND DATE(`order_created_at`) < DATE("'.$date_end.'")')
            ->groupBy (new Expression('MONTH(order.order_created_at)'))
            ->asArray ()
            ->all ();

        foreach ($rows as $result) {
            $revenue_generated_chart_data[date ('m', strtotime ($result['order_created_at']))] = array(
                'month' => (int) date ('m', strtotime ($result['order_created_at'])),
                'total' => (float) $result['total']
            );
        }

        $number_of_all_revenue_generated = $this->getOrders()
            ->activeOrders($this->restaurant_uuid)
            ->andWhere('DATE(`order_created_at`) >= DATE("'.$date_start.'") AND DATE(`order_created_at`) < DATE("'.$date_end.'")')
            ->sum('total_price');

        return [
            'revenue_generated_chart_data' => array_values($revenue_generated_chart_data),
            'number_of_all_revenue_generated' => (int) $number_of_all_revenue_generated
        ];
    }

    public function getTotalOrdersByMonths($months)
    {
        $orders_received_chart_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-'.$months.' month')) . '-1';
        $date_end = date('Y') . '-' . date('m') . '-1';

        for ($i = 0; $i < $months; $i++) {

            $month = date('m', strtotime('-'.($months - $i).' month'));

            $orders_received_chart_data[$month] = array(
                'month'   => (int) $month,
                'total' => 0
            );
        }

        $rows = $this->getOrders ()
            ->activeOrders ($this->restaurant_uuid)
            ->select (new Expression('order_created_at, COUNT(*) as total'))
            ->andWhere('DATE(`order_created_at`) >= DATE("'.$date_start.'") AND DATE(`order_created_at`) < DATE("'.$date_end.'")')
            ->groupBy (new Expression('MONTH(order.order_created_at)'))
            ->asArray ()
            ->all ();

        foreach ($rows as $result) {
            $orders_received_chart_data[date ('m', strtotime ($result['order_created_at']))] = array(
                'month' => (int) date ('m', strtotime ($result['order_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_orders_received = $this->getOrders()
            ->activeOrders($this->restaurant_uuid)
            ->andWhere('DATE(`order_created_at`) >= DATE("'.$date_start.'") AND DATE(`order_created_at`) < DATE("'.$date_end.'")')
            ->count();

        return [
            'orders_received_chart_data' => array_values ($orders_received_chart_data),
            'number_of_all_orders_received' => (int) $number_of_all_orders_received
        ];
    }

    public function getTotalSoldItemsByMonths($months)
    {
        $sold_item_chart_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-'.$months.' month')) . '-1';
        $date_end = date('Y') . '-' . date('m') . '-1';

        for ($i = 0; $i < $months; $i++) {

            $month = date('m', strtotime('-'.($months - $i).' month'));

            $sold_item_chart_data[$month] = array(
                'month'   => (int) $month,
                'total' => 0
            );
        }

        $rows = $this->getSoldOrderItems ()
            ->select ('order_item_created_at, SUM(order_item.qty) as total')
            ->andWhere('DATE(`order_item_created_at`) >= DATE("'.$date_start.'") AND DATE(`order_item_created_at`) < DATE("'.$date_end.'")')
            ->groupBy (new Expression('MONTH(order_item_created_at)'))
            ->asArray ()
            ->all ();

        foreach ($rows as $result) {
            $sold_item_chart_data[date ('m', strtotime ($result['order_item_created_at']))] = array(
                'month' => (int) date ('m', strtotime ($result['order_item_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_sold_item = $this->getSoldOrderItems()
            ->andWhere('DATE(`order_item_created_at`) >= DATE("'.$date_start.'") AND DATE(`order_item_created_at`) < DATE("'.$date_end.'")')
            ->sum('order_item.qty');

        return [
            'sold_item_chart_data' => array_values($sold_item_chart_data),
            'number_of_all_sold_item' => (int) $number_of_all_sold_item
        ];
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany (Item::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Get Agent Assignment Records
     * @return \yii\db\ActiveQuery
     */
    public function getAgentAssignments()
    {
        return $this->hasMany (AgentAssignment::className (), ['restaurant_uuid' => 'restaurant_uuid'])->with ('agent');
    }

    /**
     * Get Agents assigned to this Restaurant
     * @return \yii\db\ActiveQuery
     */
    public function getAgents()
    {
        return $this->hasMany (Agent::className (), ['agent_id' => 'agent_id'])
            ->via ('agentAssignments');
    }

    /**
     * Return owner of this store
     */
    public function getOwnerAgent()
    {
        return $this->hasMany (Agent::className (), ['agent_id' => 'agent_id'])
            ->via ('agentAssignments', function ($query) {
                return $query->andWhere (['agent_assignment.role' => AgentAssignment::AGENT_ROLE_OWNER]);
            });
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany (Subscription::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActiveSubscription()
    {
        return $this->hasOne (Subscription::className (), ['restaurant_uuid' => 'restaurant_uuid'])->where (['subscription_status' => Subscription::STATUS_ACTIVE]);
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlan()
    {

        return $this->hasOne (Plan::className (), ['plan_id' => 'plan_id'])
            ->via ('subscriptions', function ($query) {
                return $query->andWhere (['subscription.subscription_status' => Subscription::STATUS_ACTIVE]);
            });
    }

    /**
     * Gets query for [[RestaurantDeliveryAreas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDeliveryAreas()
    {
        return $this->hasMany (RestaurantDelivery::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvailableAreas()
    {

        return $this->hasMany (AreaDeliveryZone::className (), ['restaurant_uuid' => 'restaurant_uuid'])
            ->where (['is', 'area_delivery_zone.area_id', null]);
    }

    /**
     * Gets query for [[RestaurantBranches]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantBranches()
    {
        return $this->hasMany (RestaurantBranch::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantPaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantPaymentMethods()
    {
        return $this->hasMany (RestaurantPaymentMethod::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }


    /**
     * Gets query for [[PaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethods()
    {
        return $this->hasMany (PaymentMethod::className (), ['payment_method_id' => 'payment_method_id'])->viaTable ('restaurant_payment_method', ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[OpeningHours]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpeningHours()
    {
        return $this->hasMany (OpeningHour::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany (Order::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActiveOrders()
    {
        return $this->hasMany (Order::className (), ['restaurant_uuid' => 'restaurant_uuid'])
            ->activeOrders ($this->restaurant_uuid);;
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany (OrderItem::className (), ['order_uuid' => 'order_uuid'])
            //->via('orders');
            ->via ('activeOrders')
            ->joinWith ('order');
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSoldOrderItems()
    {
        return $this->hasMany (OrderItem::className (), ['order_uuid' => 'order_uuid'])
            //->via('orders');
            ->via ('activeOrders');
    }


    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStoreRevenue($start_date, $end_date)
    {

        return $this->hasMany (Order::className (), ['restaurant_uuid' => 'restaurant_uuid'])
            ->activeOrders ($this->restaurant_uuid)
            ->andWhere (['between', 'order.order_created_at', $start_date, $end_date])
            ->sum ('total_price');
    }


    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersReceived($start_date, $end_date)
    {

        return $this->hasMany (Order::className (), ['restaurant_uuid' => 'restaurant_uuid'])
            ->ordersReceived ($this->restaurant_uuid, $start_date, $end_date);
    }


    /**
     * Gets query for [[Vouchers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVouchers()
    {
        return $this->hasMany (Voucher::className (), ['restaurant_uuid' => 'restaurant_uuid'])->with ('bank');
    }

    /**
     * Gets query for [[Customers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany (Customer::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }


    /**
     * Gets query for [[Customers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerGained($start_date, $end_date)
    {

        return $this->hasMany (Customer::className (), ['restaurant_uuid' => 'restaurant_uuid'])
            ->customerGained ($this->restaurant_uuid, $start_date, $end_date);
    }

    /**
     * Gets query for [[Refunds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefunds()
    {
        return $this->hasMany (Refund::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Queues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQueues()
    {
        return $this->hasMany (Queue::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantTheme]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantTheme()
    {
        return $this->hasOne (RestaurantTheme::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }


    /**
     * Gets query for [[TapQueue]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTapQueue()
    {
        return $this->hasOne (TapQueue::className (), ['tap_queue_id' => 'tap_queue_id']);
    }


    /**
     * Gets query for [[WebLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWebLinks()
    {
        return $this->hasMany (WebLink::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[StoreWebLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStoreWebLinks()
    {
        return $this->hasMany (StoreWebLink::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }


    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountryDeliveryZones($countryId)
    {
        return $this->hasMany (DeliveryZone::className (), ['restaurant_uuid' => 'restaurant_uuid'])->where (['country_id' => $countryId]);
    }


    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocations()
    {
        return $this->hasMany (BusinessLocation::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }


    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPickupBusinessLocations()
    {
        return $this->hasMany (BusinessLocation::className (), ['restaurant_uuid' => 'restaurant_uuid'])->where (['support_pick_up' => 1]);
    }

    /**
     * Gets query for [[DeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZones()
    {
        return $this->hasMany (DeliveryZone::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }


    // /**
    //  * Gets query for [[BusinessLocations]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getDeliveryZonesForSpecificCountry($countryId)
    // {
    //   return $this->hasMany(DeliveryZone::className(), ['business_location_id' => 'business_location_id'])
    //       ->viaTable('business_location', ['restaurant_uuid' => 'restaurant_uuid']);
    // }


    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZonesForSpecificCountry($countryId)
    {
        return $this->hasMany (AreaDeliveryZone::className (), ['delivery_zone_id' => 'delivery_zone_id'])->via ('deliveryZones')
            ->where (['delivery_zone.country_id' => $countryId])->joinWith (['deliveryZone', 'city']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany (Area::className (), ['area_id' => 'area_id'])->via ('areaDeliveryZones');
    }


    /**
     * Gets query for [[AreaDeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZones()
    {
        return $this->hasMany (AreaDeliveryZone::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }


    /**
     * list of all the countries around the world that store can ship orders to
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getShippingCountries()
    // {
    //     return $this->hasMany(Country::className(), ['country_id' => 'country_id'])
    //     ->joinWith([
    //         'deliveryZones' => function ($query) {
    //             $query->onCondition(['delivery_zone.restaurant_uuid' => 'rest_00f54a5e-7c35-11ea-997e-4a682ca4b290']);
    //         },
    //     ]);
    // }


    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne (Country::className (), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne (Currency::className (), ['currency_id' => 'currency_id']);
    }


}
