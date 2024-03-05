<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_theme".
 *
 * @property string $restaurant_uuid
 * @property string|null $primary
 * @property string|null $secondary
 * @property string|null $tertiary
 * @property string|null $light
 * @property string|null $medium
 * @property string|null $dark
 * @property string|null $show_description_in_list
 *
 * @property Restaurant $restaurant
 */
class RestaurantTheme extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'restaurant_theme';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['restaurant_uuid'], 'required'],
            [['restaurant_uuid', 'primary', 'secondary', 'tertiary', 'light', 'medium', 'dark'], 'string', 'max' => 60],
            [['primary'], 'validateColorFormat'],//, 'secondary', 'tertiary', 'light', 'medium', 'dark'
            [['restaurant_uuid'], 'unique'],
            [['show_description_in_list'], 'boolean'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     * @return void
     */
    public function validateColorFormat($attribute, $params, $validator) {
        if($this->$attribute[0] != '#')
            $this->addError($attribute, Yii::t('app', 'Invalid color format'));
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'restaurant_uuid' => Yii::t('app','Restaurant Uuid'),
            'primary' => Yii::t('app','Primary'),
            'secondary' => Yii::t('app','Secondary'),
            'tertiary' => Yii::t('app','Tertiary'),
            'light' => Yii::t('app','Light'),
            'medium' => Yii::t('app','Medium'),
            'dark' => Yii::t('app','Dark'),
            'show_description_in_list' => Yii::t('app','Show Description in List'),
        ];
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant") {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
