<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "item_variant_option".
 *
 * @property string $item_variant_option_uuid
 * @property string $item_variant_uuid
 * @property string $item_uuid
 * @property int $option_id
 * @property int $extra_option_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Item $itemUu
 * @property ItemVariant $itemVariantUu
 */
class ItemVariantOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_variant_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_variant_uuid', 'item_uuid', 'option_id', 'extra_option_id'], 'required'],
            [['option_id', 'extra_option_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_variant_option_uuid', 'item_variant_uuid'], 'string', 'max' => 60],
            [['item_uuid'], 'string', 'max' => 100],
            [['item_variant_option_uuid'], 'unique'],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
            [['item_variant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => ItemVariant::className(), 'targetAttribute' => ['item_variant_uuid' => 'item_variant_uuid']],
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
                'class' => AttributeBehavior::className (),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'item_variant_option_uuid',
                ],
                'value' => function () {
                    if (!$this->item_variant_option_uuid) {
                        $this->item_variant_option_uuid = 'item_variant_option_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();
                    }

                    return $this->item_variant_option_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className (),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_variant_option_uuid' => Yii::t('app', 'Item Variant Option Uuid'),
            'item_variant_uuid' => Yii::t('app', 'Item Variant Uuid'),
            'item_uuid' => Yii::t('app', 'Item Uuid'),
            'option_id' => Yii::t('app', 'Option ID'),
            'extra_option_id' => Yii::t('app', 'Extra Option ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemUu()
    {
        return $this->hasOne(Item::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[ItemVariantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemVariantUu()
    {
        return $this->hasOne(ItemVariant::className(), ['item_variant_uuid' => 'item_variant_uuid']);
    }
}
