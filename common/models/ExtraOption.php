<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "extra_option".
 *
 * @property int $extra_option_id
 * @property int|null $option_id
 * @property string|null $extra_option_name
 * @property string|null $extra_option_name_ar
 * @property float|null $extra_option_price
 *
 * @property Option $option
 * @property Item $item
 * @property OrderItemExtraOption[] $orderItemExtraOptions 
 */
class ExtraOption extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'extra_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['option_id'], 'integer'],
            [['extra_option_price'], 'number' , 'min'=> 0],
            [['extra_option_name', 'extra_option_name_ar'], 'string', 'max' => 255],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => Option::className(), 'targetAttribute' => ['option_id' => 'option_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'extra_option_id' => 'Extra Option ID',
            'option_id' => 'Option ID',
            'extra_option_name' => 'Extra Option Name',
            'extra_option_name_ar' => 'Extra Option Name Ar',
            'extra_option_price' => 'Extra Option Price',
        ];
    }

    /**
     * Gets query for [[Option]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOption() {
        return $this->hasOne(Option::className(), ['option_id' => 'option_id']);
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem() {
        return $this->hasOne(Item::className(), ['item_uuid' => 'item_uuid'])->via('option');
    }

    /**
     * Gets query for [[OrderItemExtraOption]]. 
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getOrderItemExtraOptions() {
        return $this->hasMany(OrderItemExtraOption::className(), ['extra_option_id' => 'extra_option_id']);
    }

}
