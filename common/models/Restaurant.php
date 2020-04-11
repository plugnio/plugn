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
 * @property int $agent_id
 * @property string $name
 * @property string|null $name_ar
 * @property string|null $tagline
 * @property string|null $tagline_ar
 * @property int $restaurant_status
 * @property string $thumbnail_image
 * @property string $logo
 * @property int $support_delivery
 * @property int $support_pick_up
 * @property string|null $phone_number
 * @property string|null $restaurant_created_at
 * @property string|null $restaurant_updated_at
 *
 * @property AgentAssignment[] $agentAssignments
 * @property Agent[] $agents
 * @property Agent $agent
 * @property Order[] $orders
 * @property Item[] $items
 * @property RestaurantDelivery[] $restaurantDeliveryAreas
 * @property RestaurantBranch[] $restaurantBranches
 * @property Area[] $areas
 * @property RestaurantPaymentMethod[] $restaurantPaymentMethods
 * @property RestaurantTheme $restaurantTheme
 * @property PaymentMethod[] $paymentMethods
 * @property Agent[] $agents 
 * @property WorkingHours[] $workingHours
 * @property WorkingDay[] $workingDays
 */
class Restaurant extends \yii\db\ActiveRecord {

    //Values for `restaurant_status`
    const RESTAURANT_STATUS_OPEN = 1;
    const RESTAURANT_STATUS_BUSY = 2;
    const RESTAURANT_STATUS_CLOSE = 3;

    public $restaurant_delivery_area;
    public $restaurant_payments_method;
    public $restaurant_logo;
    public $restaurant_thumbnail_image;

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
            [['name', 'support_delivery', 'support_pick_up', 'restaurant_payments_method'], 'required'],
            [['restaurant_thumbnail_image', 'restaurant_logo'], 'file', 'extensions' => 'jpg, jpeg , png', 'maxFiles' => 1],
            [['restaurant_delivery_area', 'restaurant_payments_method'], 'safe'],
            [['agent_id', 'restaurant_status', 'support_delivery', 'support_pick_up'], 'integer', 'min' => 0],
            [['restaurant_created_at', 'restaurant_updated_at'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['name', 'name_ar', 'tagline', 'tagline_ar', 'thumbnail_image', 'logo'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'min' => 8, 'max' => 8],
            [['phone_number'], 'integer', 'min' => 0],
            [['restaurant_uuid'], 'unique'],
            [['agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['agent_id' => 'agent_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'restaurant_uuid' => 'Restaurant Uuid',
            'agent_id' => 'Agent ID',
            'name' => 'Name',
            'name_ar' => 'Name Ar',
            'tagline' => 'Tagline',
            'tagline_ar' => 'Tagline Ar',
            'restaurant_status' => 'Restaurant Status',
            'thumbnail_image' => 'Thumbnail Image',
            'logo' => 'Logo',
            'restaurant_thumbnail_image' => 'Thumbnail Image',
            'restaurant_logo' => 'Logo',
            'support_delivery' => 'Support Delivery',
            'support_pick_up' => 'Support Pick Up',
            'restaurant_delivery_area' => 'Delivery Areas',
            'phone_number' => 'Phone Number',
            'restaurant_created_at' => 'Restaurant Created At',
            'restaurant_updated_at' => 'Restaurant Updated At',
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
    public function getRestaurantLogoUrl(){
        return 'https://res.cloudinary.com/plugn/image/upload/c_scale,h_105,w_105/restaurants/' . $this->restaurant_uuid . "/logo/" . $this->logo;
    }
    
    /**
     * Return Restaurant's thumbnail image url 
     */
    public function getRestaurantThumbnailImageUrl(){
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

            if ($result || count($result) > 0) {
                $this->logo = basename($result['url']);
                $this->save();
            }
        } catch (\Cloudinary\Error $err) {
            Yii::error('Error when uploading venue photos to Cloudinry: ' . json_encode($err));
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

            if ($result || count($result) > 0) {
                $this->thumbnail_image = basename($result['url']);
                $this->save();
            }
        } catch (\Cloudinary\Error $err) {
            Yii::error('Error when uploading venue photos to Cloudinry: ' . json_encode($err));
        }
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
        
        if($insert){
            $restaurant_theme = new RestaurantTheme();
            $restaurant_theme->restaurant_uuid = $this->restaurant_uuid;
            $restaurant_theme->save();
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

    /**
     * Delete Restaurant's logo
     */
    public function deleteRestaurantLogo($logo = null) {

        if (!$logo)
            $logo = $this->logo;

        $restaurantName = str_replace(' ', '', $this->name);
        $imageURL = "restaurants/" . $restaurantName . "/logo/" . $logo;

        try {
            Yii::$app->cloudinaryManager->delete($imageURL);
        } catch (\Cloudinary\Error $err) {
            Yii::error('Error when uploading logo photos to Cloudinry: ' . json_encode($err));
        }
    }

    /**
     * Delete Restaurant's Thumbnail Image
     */
    public function deleteRestaurantThumbnailImage($thumbnail_image = null) {

        if (!$thumbnail_image)
            $thumbnail_image = $this->thumbnail_image;

        $restaurantName = str_replace(' ', '', $this->name);
        $imageURL = "restaurants/" . $restaurantName . "/thumbnail-image/" . $thumbnail_image;

        try {
            Yii::$app->cloudinaryManager->delete($imageURL);
        } catch (\Cloudinary\Error $err) {
            Yii::error('Error when uploading thumbnail image to Cloudinry: ' . json_encode($err));
        }
    }

    public function beforeDelete() {


        if (!parent::beforeDelete()) {
            return false;
        }

        $this->deleteRestaurantThumbnailImage();
        $this->deleteRestaurantLogo();

        return true;
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
     * The Agent owning this account
     * @return \yii\db\ActiveQuery
     */
    public function getAgent() {
        return $this->hasOne(Agent::className(), ['agent_id' => 'agent_id']);
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
     * Gets query for [[RestaurantBranches]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantBranches() {
        return $this->hasMany(RestaurantBranch::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas() {
        return $this->hasMany(Area::className(), ['area_id' => 'area_id'])->viaTable('restaurant_delivery', ['restaurant_uuid' => 'restaurant_uuid']);
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
     * Gets query for [[WorkingHours]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkingHours() {
        return $this->hasMany(WorkingHours::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[WorkingDays]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkingDays() {
        return $this->hasMany(WorkingDay::className(), ['working_day_id' => 'working_day_id'])->viaTable('working_hours', ['restaurant_uuid' => 'restaurant_uuid']);
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
     * Gets query for [[RestaurantTheme]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantTheme()
    {
        return $this->hasOne(RestaurantTheme::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
