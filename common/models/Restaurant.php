<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "restaurant".
 *
 * @property string $restaurant_uuid
 * @property string $name
 * @property string|null $name_ar
 * @property string|null $tagline
 * @property string|null $tagline_ar
 * @property string|null $restaurant_domain
*  @property int $app_id
 * @property int $restaurant_status
 * @property string $thumbnail_image
 * @property string $logo
 * @property int $support_delivery
 * @property int $support_pick_up
 * @property string|null $phone_number
 * @property string $restaurant_email
 * @property string|null $restaurant_created_at
 * @property string|null $restaurant_updated_at
 * @property boolean $restaurant_email_notification
 * @property boolean $show_opening_hours
 * @property boolean $armada_api_key
 * @property int $phone_number_display
 * @property int $store_branch_name
 * @property int $custom_css
 * @property int $store_layout
 * @property int $platform_fee
 * @property int $facebook_pixil_id
 * @property int $google_analytics_id
 * @property int $schedule_order
 * @property int $schedule_interval

 *
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
 * @property Agent[] $agents
 */
class Restaurant extends \yii\db\ActiveRecord {

    //Values for `restaurant_status`
    const RESTAURANT_STATUS_OPEN = 1;
    const RESTAURANT_STATUS_BUSY = 2;
    const RESTAURANT_STATUS_CLOSE = 3;

    //Values for `phone_number_display`
    const PHONE_NUMBER_DISPLAY_DONT_SHOW_PHONE_NUMBER  = 1;
    const PHONE_NUMBER_DISPLAY_ICON = 2;
    const PHONE_NUMBER_DISPLAY_SHOW_PHONE_NUMBER = 3;

    //Values for `store_layout`
    const STORE_LAYOUT_LIST  = 1;
    const STORE_LAYOUT_GRID  = 2;


    public $restaurant_delivery_area;
    public $restaurant_payments_method;
    public $restaurant_logo;
    public $restaurant_thumbnail_image;

    public $date_range_picker_with_time;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'restaurant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'support_delivery', 'support_pick_up', 'restaurant_payments_method', 'restaurant_domain', 'restaurant_email','store_branch_name','app_id'], 'required', 'on' => 'create'],
            [['restaurant_thumbnail_image', 'restaurant_logo'], 'file', 'extensions' => 'jpg, jpeg , png', 'maxFiles' => 1],
            [['restaurant_delivery_area', 'restaurant_payments_method'], 'safe'],
            [['restaurant_status', 'support_delivery', 'support_pick_up'], 'integer', 'min' => 0],
            ['restaurant_status', 'in', 'range' => [self::RESTAURANT_STATUS_OPEN, self::RESTAURANT_STATUS_BUSY, self::RESTAURANT_STATUS_CLOSE]],
            ['store_layout', 'in', 'range' => [self::STORE_LAYOUT_LIST, self::STORE_LAYOUT_GRID]],
            ['phone_number_display', 'in', 'range' => [self::PHONE_NUMBER_DISPLAY_ICON, self::PHONE_NUMBER_DISPLAY_SHOW_PHONE_NUMBER, self::PHONE_NUMBER_DISPLAY_DONT_SHOW_PHONE_NUMBER]],
            [['restaurant_created_at', 'restaurant_updated_at'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['custom_css'], 'string'],
            [['platform_fee'], 'number'],
            [['instagram_url'], 'url'],
            [['date_range_picker_with_time','google_analytics_id', 'facebook_pixil_id'], 'safe'],
            [['name', 'name_ar', 'tagline', 'tagline_ar', 'thumbnail_image', 'logo', 'restaurant_domain', 'app_id' ,'armada_api_key','store_branch_name'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'min' => 8, 'max' => 8],
            [['phone_number'], 'integer', 'min' => 0],
            [['restaurant_email_notification','schedule_order','schedule_interval','phone_number_display','store_layout','show_opening_hours'], 'integer'],
            ['restaurant_email', 'email'],
            [['restaurant_uuid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'restaurant_uuid' => 'Restaurant Uuid',
            'name' => 'Name',
            'name_ar' => 'Name in Arabic',
            'tagline' => 'Tagline',
            'tagline_ar' => 'Tagline in Arabic',
            'restaurant_domain' => 'Domain',
            'app_id' => 'App id',
            'restaurant_status' => 'Store Status',
            'thumbnail_image' => 'Header Image',
            'logo' => 'Logo',
            'restaurant_thumbnail_image' => 'Header Image',
            'restaurant_logo' => 'Logo',
            'support_delivery' => 'Support Delivery',
            'support_pick_up' => 'Support Pick Up',
            'restaurant_delivery_area' => 'Delivery Areas',
            'date_range_picker_with_time' => 'Export orders data in a specific date range',
            'phone_number' => 'Phone Number',
            'restaurant_email' => "Store's Email",
            'restaurant_created_at' => 'Store Created At',
            'restaurant_updated_at' => 'Store Updated At',
            'armada_api_key' => 'Armada Api Key',
            'restaurant_email_notification' => 'Email Notification',
            'show_opening_hours' => 'Show Opening hours',
            'phone_number_display' => 'Phone number display',
            'store_branch_name' => 'Branch name',
            'custom_css' => 'Custom css',
            'platform_fee' => 'Platform fee',
            'store_layout' => 'Store layout',
            'google_analytics_id' => 'Google Analytics ID',
            'facebook_pixil_id' => 'Facebook Pixil ID',
            'instagram_url' => 'Instagram Url',
            'schedule_order' => 'Schedule Order',
            'schedule_interval' => 'Schedule Interval',
        ];
    }

    /**
     *
     * @return type
     */
    public function behaviors() {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'restaurant_uuid',
                ],
                'value' => function() {
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
        ];
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getStatus() {
        switch ($this->restaurant_status) {
            case self::RESTAURANT_STATUS_OPEN:
                return "Open";
                break;
            case self::RESTAURANT_STATUS_BUSY:
                return "Busy";
                break;
            case self::RESTAURANT_STATUS_CLOSE:
                return "Closed";
                break;
        }

        return "Couldnt find a status";
    }

    /**
     * save restaurant payment method
     */
    public function saveRestaurantPaymentMethod($payments_method) {

        $sotred_restaurant_payment_method = RestaurantPaymentMethod::find()
                ->where(['restaurant_uuid' => $this->restaurant_uuid])
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
    }

    /**
     * Return Restaurant's logo url
     */
    public function getRestaurantLogoUrl() {
        return 'https://res.cloudinary.com/plugn/image/upload/c_scale,h_105,w_105/restaurants/' . $this->restaurant_uuid . "/logo/" . $this->logo;
    }

    /**
     * Return Restaurant's thumbnail image url
     */
    public function getRestaurantThumbnailImageUrl() {
        return 'https://res.cloudinary.com/plugn/image/upload/c_scale,w_600/restaurants/' . $this->restaurant_uuid . "/thumbnail-image/" . $this->thumbnail_image;
    }

    /**
     * Upload restaurant's logo  to cloudinary
     * @param type $imageURL
     */
    public function uploadLogo($imageURL) {

        $filename = Yii::$app->security->generateRandomString();

        try {
            $result = Yii::$app->cloudinaryManager->upload(
                    $imageURL, [
                'public_id' => "restaurants/" . $this->restaurant_uuid . "/logo/" . $filename
                    ]
            );

            //Delete old store's logo
            if ($this->logo) {
                $this->deleteRestaurantLogo();
            }


            if ($result || count($result) > 0) {
                $this->logo = basename($result['url']);
                $this->save();
            }
        } catch (\Cloudinary\Error $err) {
            Yii::error("Error when uploading logo photos to Cloudinry: " . json_encode($err));
        }
    }

    /**
     * Upload thumbnailImage  to cloudinary
     * @param type $imageURL
     */
    public function uploadThumbnailImage($imageURL) {

        $filename = Yii::$app->security->generateRandomString();

        try {
            $result = Yii::$app->cloudinaryManager->upload(
                    $imageURL, [
                'public_id' => "restaurants/" . $this->restaurant_uuid . "/thumbnail-image/" . $filename
                    ]
            );



            //Delete old store's ThumbnailImage
            if ($this->thumbnail_image) {
                $this->deleteRestaurantThumbnailImage();
            }

            if ($result || count($result) > 0) {
                $this->thumbnail_image = basename($result['url']);
                $this->save();
            }
        } catch (\Cloudinary\Error $err) {
            Yii::error("Error when uploading thumbnail photos to Cloudinry: " . json_encode($err));
        }
    }

    /**
     * @inheritdoc
     */
    public function fields() {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['vendor_sector']);
        unset($fields['business_id']);
        unset($fields['business_entity_id']);
        unset($fields['wallet_id']);
        unset($fields['merchant_id']);
        unset($fields['operator_id']);
        unset($fields['live_api_key']);
        unset($fields['test_api_key']);
        unset($fields['business_type']);
        unset($fields['restaurant_email']);
        unset($fields['license_number']);
        unset($fields['not_for_profit']);
        unset($fields['authorized_signature_issuing_country']);
        unset($fields['authorized_signature_issuing_date']);
        unset($fields['authorized_signature_issuing_date']);
        unset($fields['authorized_signature_expiry_date']);
        unset($fields['authorized_signature_title']);
        unset($fields['authorized_signature_file']);
        unset($fields['authorized_signature_file_id']);
        unset($fields['authorized_signature_file_purpose']);
        unset($fields['commercial_license_issuing_country']);
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
        unset($fields['identification_issuing_country']);
        unset($fields['identification_issuing_date']);
        unset($fields['identification_expiry_date']);
        unset($fields['identification_file']);
        unset($fields['identification_file_id']);
        unset($fields['identification_title']);
        unset($fields['identification_file_purpose']);
        unset($fields['restaurant_created_at']);
        unset($fields['restaurant_updated_at']);

        return $fields;
    }

    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert && isset($changedAttributes['thumbnail_image']) && $this->restaurant_thumbnail_image) {
            if ($changedAttributes['thumbnail_image']) {
                $this->deleteRestaurantThumbnailImage($changedAttributes['thumbnail_image']);
            }
        }

        if (!$insert && isset($changedAttributes['logo']) && $this->restaurant_logo) {
            if ($changedAttributes['logo']) {
                $this->deleteRestaurantLogo($changedAttributes['logo']);
            }
        }

        if ($insert) {
            $restaurant_theme = new RestaurantTheme();
            $restaurant_theme->restaurant_uuid = $this->restaurant_uuid;
            $restaurant_theme->save();


            //Add opening hrs
            for($i = 0; $i < 7; ++$i) {
                $opening_hour = new OpeningHour();
                $opening_hour->restaurant_uuid = $this->restaurant_uuid;
                $opening_hour->day_of_week = $i;
                $opening_hour->open_time = 0;
                $opening_hour->close_time = '23:59:59';
                $opening_hour->save();
            }

        }
    }

    /**
     * Return Logo url to dispaly it on backend
     * @return string
     */
    public function getLogo() {
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
    public function getThumbnailImage() {
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

    public function isOpen()
    {
        $opening_hours_model = OpeningHour::find()->where(['day_of_week' => date('w')])->one();

        if(!$opening_hours_model->is_closed && date('H:i:s') >= $opening_hours_model->open_time && date('H:i:s') <= $opening_hours_model->close_time)
          return true;

        return false;
    }


    /**
     * @inheritdoc
     */
    public function extraFields()
    {
      return [
          'isOpen' => function($restaurant){
              return $restaurant->isOpen();
          }
      ];
    }


    /**
     * Delete Restaurant's logo
     */
    public function deleteRestaurantLogo($logo = null) {

        if (!$logo)
            $logo = $this->logo;

        $imageURL = "restaurants/" . $this->restaurant_uuid . "/logo/" . $logo;

        try {
            Yii::$app->cloudinaryManager->delete($imageURL);
        } catch (\Cloudinary\Error $err) {
            Yii::error('Error while deleting logo photos to Cloudinry: ' . json_encode($err));
        }
    }

    /**
     * Delete Restaurant's Thumbnail Image
     */
    public function deleteRestaurantThumbnailImage($thumbnail_image = null) {

        if (!$thumbnail_image)
            $thumbnail_image = $this->thumbnail_image;

        $imageURL = "restaurants/" . $this->restaurant_uuid . "/thumbnail-image/" . $thumbnail_image;

        try {
            Yii::$app->cloudinaryManager->delete($imageURL);
        } catch (\Cloudinary\Error $err) {
            Yii::error('Error while deleting thumbnail image to Cloudinry: ' . json_encode($err));
        }
    }

    public function beforeDelete() {

        $this->deleteRestaurantThumbnailImage();
        $this->deleteRestaurantLogo();

        return parent::beforeDelete();
    }

    /**
     * Promotes current restaurant to open restaurant while disabling rest
     */
    public function promoteToOpenRestaurant() {
        $this->restaurant_status = Restaurant::RESTAURANT_STATUS_OPEN;
        $this->save(false);
    }

    /**
     * Promotes current restaurant to close restaurant while disabling rest
     */
    public function promoteToCloseRestaurant() {
        $this->restaurant_status = Restaurant::RESTAURANT_STATUS_CLOSE;
        $this->save(false);
    }

    /**
     * Promotes current restaurant to busy restaurant while disabling rest
     */
    public function promoteToBusyRestaurant() {
        $this->restaurant_status = Restaurant::RESTAURANT_STATUS_BUSY;
        $this->save(false);
    }

    /**
     * save restaurant delivery areas
     */
    public function saveRestaurantDeliveryArea($delivery_areas) {

        RestaurantDelivery::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);

        foreach ($delivery_areas as $area_id) {
            $delivery_area = new RestaurantDelivery();
            $delivery_area->area_id = $area_id;
            $delivery_area->restaurant_uuid = $this->restaurant_uuid;
            $delivery_area->save();
        }
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems() {
        return $this->hasMany(Item::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Get Agent Assignment Records
     * @return \yii\db\ActiveQuery
     */
    public function getAgentAssignments() {
        return $this->hasMany(AgentAssignment::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Get Agents assigned to this Restaurant
     * @return \yii\db\ActiveQuery
     */
    public function getAgents() {
        return $this->hasMany(Agent::className(), ['agent_id' => 'agent_id'])
                        ->via('agentAssignments');
    }

    /**
     * Gets query for [[RestaurantDeliveryAreas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDeliveryAreas() {
        return $this->hasMany(RestaurantDelivery::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }


    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Area::className(), ['area_id' => 'area_id'])->viaTable('restaurant_delivery', ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantBranches]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantBranches() {
        return $this->hasMany(RestaurantBranch::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }



    /**
     * Gets query for [[RestaurantPaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantPaymentMethods() {
        return $this->hasMany(RestaurantPaymentMethod::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[PaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethods() {
        return $this->hasMany(PaymentMethod::className(), ['payment_method_id' => 'payment_method_id'])->viaTable('restaurant_payment_method', ['restaurant_uuid' => 'restaurant_uuid']);
    }




    /**
     * Gets query for [[OpeningHours]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpeningHours()
    {
        return $this->hasMany(OpeningHour::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders() {
        return $this->hasMany(Order::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Customers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers() {
        return $this->hasMany(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * Gets query for [[Refunds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefunds() {
        return $this->hasMany(Refund::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantTheme]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantTheme() {
        return $this->hasOne(RestaurantTheme::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

}
