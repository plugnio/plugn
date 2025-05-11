<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "cart_item_option".
 *
 * @property string $cart_item_option_uuid
 * @property string $cart_item_uuid
 * @property int $option_id
 * @property int $value_id
 * @property int $value
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property CartItem $cartItem
 * @property Option $option
 * @property ExtraOption $optionValue
 */
class CartItemOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cart_item_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        //, 'created_at'
        return [
            [['cart_item_option_uuid', 'cart_item_uuid', 'option_id', 'value_id'], 'required'],
            [['option_id', 'value_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['value'], 'string', 'max' => 255],
            [['cart_item_uuid'], 'string', 'max' => 60],
            [['cart_item_option_uuid', 'cart_item_uuid'], 'string', 'max' => 60],
            [['cart_item_option_uuid'], 'unique'],
            [['cart_item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => CartItem::class, 'targetAttribute' => ['cart_item_uuid' => 'cart_item_uuid']],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => Option::class, 'targetAttribute' => ['option_id' => 'option_id']],
            [['value_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExtraOption::class, 'targetAttribute' => ['value_id' => 'extra_option_id']],
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
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'cart_item_option_uuid',
                ],
                'value' => function () {
                    if (!$this->cart_item_option_uuid) {
                        $this->cart_item_option_uuid = 'cart_item_option_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

                    return $this->cart_item_option_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cart_item_option_uuid' => Yii::t('app', 'Cart Item Option Uuid'),
            'cart_item_uuid' => Yii::t('app', 'Cart Item Uuid'),
            'option_id' => Yii::t('app', 'Option ID'),
            'value_id' => Yii::t('app', 'Value ID'),
            'value' => Yii::t('app', 'Value'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return array the active query used by this AR class.
     */
    private function extraFields() {
        return [
            //"cartItem",
            "option",
            "optionValue",
        ];
    }

    /**
     * Gets query for [[CartItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCartItem()
    {
        return $this->hasOne(CartItem::class, ['cart_item_uuid' => 'cart_item_uuid']);
    }

    /**
     * Gets query for [[Option]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(Option::class, ['option_id' => 'option_id']);
    }

    /**
     * Gets query for [[OptionValue]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOptionValue()
    {
        return $this->hasOne(ExtraOption::class, ['extra_option_id' => 'value_id']);
    }
}
