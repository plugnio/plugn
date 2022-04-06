<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "web_link".
 *
 * @property int $web_link_id
 * @property string $restaurant_uuid
 * @property int $web_link_type
 * @property string $url
 * @property string $web_link_title
 * @property string $web_link_title_ar
 *
 * @property StoreWebLink[] $storeWebLinks
 * @property Restaurant $restaurantUu
 */
class WebLink extends \yii\db\ActiveRecord
{
      //Values for `web_link_type`
      const WEB_LINK_TYPE_WEBSITE_URL = 1;
      const WEB_LINK_TYPE_FACEBOOK = 2;
      const WEB_LINK_TYPE_INSTAGRAM = 3;
      const WEB_LINK_TYPE_TWITTER = 4;
      const WEB_LINK_TYPE_SNAPCHAT= 5;
      const WEB_LINK_TYPE_WHATSAPP = 6;
      const WEB_LINK_TYPE_EMAIL= 7;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'web_link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url','web_link_title','web_link_title_ar'], 'required'],
            ['web_link_type', 'in', 'range' => [self::WEB_LINK_TYPE_WEBSITE_URL, self::WEB_LINK_TYPE_FACEBOOK, self::WEB_LINK_TYPE_INSTAGRAM, self::WEB_LINK_TYPE_TWITTER, self::WEB_LINK_TYPE_SNAPCHAT, self::WEB_LINK_TYPE_WHATSAPP, self::WEB_LINK_TYPE_EMAIL]],
            [['restaurant_uuid'], 'string', 'max' => 60],
            ['url', 'url', 'when' => function($model) {
                return $model->web_link_type == self::WEB_LINK_TYPE_WEBSITE_URL;
            }, 'whenClient' => "function (attribute, value) {
                return $('#webLinkTypeInput').val() == 1;
            }"],
            [['url', 'web_link_title','web_link_title_ar'], 'string', 'max' => 255],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'web_link_id' => Yii::t('app','Web Link ID'),
            'restaurant_uuid' => Yii::t('app','Restaurant Uuid'),
            'web_link_type' => Yii::t('app','Web Link Type'),
            'url' => Yii::t('app','URL'),
            'web_link_title' => Yii::t('app','Title in English'),
            'web_link_title_ar' => Yii::t('app','Title in Arabic')
        ];
    }

    /**
     * Returns String value of web_link_type
     * @return string
     */
    public function getWebLinkType() {
        switch ($this->web_link_type) {
            case self::WEB_LINK_TYPE_WEBSITE_URL:
                return "Website Url";
                break;
            case self::WEB_LINK_TYPE_FACEBOOK:
                return "Facebook";
                break;
            case self::WEB_LINK_TYPE_INSTAGRAM:
                return "Instagram";
                break;
            case self::WEB_LINK_TYPE_TWITTER:
                return "Twitter";
                break;
            case self::WEB_LINK_TYPE_SNAPCHAT:
                return "Snapchat";
                break;
            case self::WEB_LINK_TYPE_WHATSAPP:
                return "WhatsApp";
                break;
            case self::WEB_LINK_TYPE_EMAIL:
                return "Email";
                break;
        }

        return "Couldnt find a web link type";
    }

    /**
    * @param type $insert
    * @param type $changedAttributes
    */
    public function afterSave($insert, $changedAttributes) {
      parent::afterSave($insert, $changedAttributes);

      $store_web_link_model = new StoreWebLink();
      $store_web_link_model->restaurant_uuid = $this->restaurant_uuid;
      $store_web_link_model->web_link_id = $this->web_link_id;
      $store_web_link_model->save(false);

      return  true;
    }

    /**
     * @inheritdoc
     */
    public function extraFields() {
        return [
          'webLinkType'
        ];
    }

    /**
     * Gets query for [[StoreWebLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStoreWebLinks($modelClass = "\common\models\StoreWebLink")
    {
        return $this->hasMany($modelClass::className(), ['web_link_id' => 'web_link_id']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
