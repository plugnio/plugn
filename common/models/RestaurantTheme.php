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
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

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
            'dark' => Yii::t('app','Dark')
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
