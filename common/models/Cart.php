<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "cart".
 *
 * @property string $cart_uuid
 * @property int|null $customer_id
 * @property string|null $session_id
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property CartItem[] $cartItems
 * @property Customer $customer
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // [['cart_uuid', 'created_at'], 'required'],
            [['customer_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['cart_uuid'], 'string', 'max' => 60],
            [['session_id'], 'string', 'max' => 255],
            [['cart_uuid'], 'unique'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'customer_id']],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'cart_uuid',
                ],
                'value' => function () {
                    if (!$this->cart_uuid) {
                        $this->cart_uuid = 'cart_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

                    return $this->cart_uuid;
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
            'cart_uuid' => Yii::t('app', 'Cart Uuid'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'session_id' => Yii::t('app', 'Session ID'),
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
            "cartItems",
            //"customer",
        ];
    }

    /**
     * Gets query for [[CartItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCartItems()
    {
        return $this->hasMany(CartItem::class, ['cart_uuid' => 'cart_uuid']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['customer_id' => 'customer_id']);
    }
}
