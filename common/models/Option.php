<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "option".
 *
 * @property int $option_id
 * @property string $item_uuid
 * @property int|null $min_qty
 * @property int|null $max_qty
 * @property string|null $option_name
 * @property string|null $option_name_ar
 *
 * @property ExtraOption[] $extraOptions
 * @property Item $item
 */
class Option extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['item_uuid', 'min_qty', 'option_name', 'option_name_ar'], 'required'],
            ['max_qty', 'required', 'when' => function($model) {
                    return $model->min_qty != null;
                }
            ],
            [['min_qty'], 'integer', 'min' => 0],
            [['max_qty'], 'integer', 'min' => 1],
            // an inline validator defined as an anonymous function
            ['min_qty', function ($attribute, $params, $validator) {
                    if ($this->min_qty > $this->max_qty) {
                        $this->addError($attribute, 'Min quantity must be less than or equal to max quantity');
                    }
                }],
            [['item_uuid'], 'string', 'max' => 300],
            [['option_name', 'option_name_ar'], 'string', 'max' => 255],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'option_id' => 'Option ID',
            'item_uuid' => 'Item Uuid',
            'min_qty' => 'Min Qty',
            'max_qty' => 'Max Qty',
            'option_name' => 'Option Name',
            'option_name_ar' => 'Option Name Ar',
        ];
    }

    /**
     * Gets query for [[ExtraOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtraOptions() {
        return $this->hasMany(ExtraOption::className(), ['option_id' => 'option_id']);
    }

    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem() {
        return $this->hasOne(Item::className(), ['item_uuid' => 'item_uuid']);
    }

}
