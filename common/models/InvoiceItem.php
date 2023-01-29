<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "invoice_item".
 *
 * @property string $invoice_item_uuid
 * @property string|null $restaurant_uuid
 * @property string|null $invoice_uuid
 * @property int|null $plan_id
 * @property string|null $addon_uuid
 * @property string|null $order_uuid
 * @property string|null $comment
 * @property float $total
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Addon $addon
 * @property RestaurantInvoice $invoice
 * @property Order $order
 * @property Plan $plan
 * @property Restaurant $restaurant
 */
class InvoiceItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total'], 'required'],
            [['plan_id'], 'integer'],
            [['total'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['invoice_item_uuid', 'restaurant_uuid', 'invoice_uuid', 'addon_uuid'], 'string', 'max' => 60],
            [['order_uuid'], 'string', 'max' => 40],
            [['comment'], 'string', 'max' => 255],
            [['invoice_item_uuid'], 'unique'],
            [['addon_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Addon::className(), 'targetAttribute' => ['addon_uuid' => 'addon_uuid']],
            [['invoice_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => RestaurantInvoice::className(), 'targetAttribute' => ['invoice_uuid' => 'invoice_uuid']],
            [['order_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_uuid' => 'order_uuid']],
            [['plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Plan::className(), 'targetAttribute' => ['plan_id' => 'plan_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'invoice_item_uuid',
                ],
                'value' => function () {
                    if (!$this->invoice_item_uuid) {
                        // Get a unique uuid
                        $this->invoice_item_uuid = 'invoice_item_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();
                    }

                    return $this->invoice_item_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className()
            ]
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'invoice_item_uuid' => Yii::t('app', 'Invoice Item Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'invoice_uuid' => Yii::t('app', 'Invoice Uuid'),
            'plan_id' => Yii::t('app', 'Plan ID'),
            'addon_uuid' => Yii::t('app', 'Addon Uuid'),
            'order_uuid' => Yii::t('app', 'Order Uuid'),
            'comment' => Yii::t('app', 'Comment'),
            'total' => Yii::t('app', 'Total'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Addon]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddon($modelClass = "\common\models\Addon")
    {
        return $this->hasOne($modelClass::className(), ['addon_uuid' => 'addon_uuid']);
    }

    /**
     * Gets query for [[Invoice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice($modelClass = "\common\models\RestaurantInvoice")
    {
        return $this->hasOne($modelClass::className(), ['invoice_uuid' => 'invoice_uuid']);
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\common\models\Order")
    {
        return $this->hasOne($modelClass::className(), ['order_uuid' => 'order_uuid']);
    }

    /**
     * Gets query for [[Plan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlan($modelClass = "\common\models\Plan")
    {
        return $this->hasOne($modelClass::className(), ['plan_id' => 'plan_id']);
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
