<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "restaurant_inventory".
 *
 * @property string $inventory_uuid
 * @property string $restaurant_uuid
 * @property string|null $ingredient_uuid
 * @property string|null $item_variant_uuid
 * @property string|null $item_uuid
 * @property int|null $stock_quantity Total stock of the item available
 * @property string|null $restocked_at when the stock was last replenished
 * @property string $created_at
 * @property string $updated_at
 *
 * @property RestaurantIngredient $ingredient
 * @property Item $item
 * @property ItemVariant $itemVariant
 * @property Restaurant $restaurant
 * @property RestockHistory[] $restockHistories
 */
class RestaurantInventory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_inventory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid'], 'required'],//'inventory_uuid', 'created_at', 'updated_at'
            [['stock_quantity'], 'integer'],
            [['restocked_at', 'created_at', 'updated_at'], 'safe'],
            [['inventory_uuid', 'restaurant_uuid', 'ingredient_uuid', 'item_variant_uuid', 'item_uuid'], 'string', 'max' => 60],
            [['inventory_uuid'], 'unique'],
            [['ingredient_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => RestaurantIngredient::className(), 'targetAttribute' => ['ingredient_uuid' => 'ingredient_uuid']],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
            [['item_variant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => ItemVariant::className(), 'targetAttribute' => ['item_variant_uuid' => 'item_variant_uuid']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['item_uuid', 'item_variant_uuid', 'ingredient_uuid'], 'validateAtLeastOne'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validateAtLeastOne($attribute, $params)
    {
        if (empty($this->item_uuid) && empty($this->item_variant_uuid) && empty($this->ingredient_uuid)) {
            $this->addError($attribute, 'At least one of the item detail must be filled.');
        }
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert && isset($changedAttributes['stock_quantity'])) {
            $model = new RestockHistory;
            $model->restaurant_uuid = $this->restaurant_uuid;
            $model->inventory_uuid = $this->inventory_uuid;
            $model->restocked_quantity = $this->stock_quantity - $changedAttributes['stock_quantity'];
            $model->restocked_at = date("Y-m-d");

            if (!$model->save()) {
                Yii::error(print_r($model->errors, true));
            }
        }
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className (),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'inventory_uuid',
                ],
                'value' => function () {
                    if (!$this->inventory_uuid) {
                        $this->inventory_uuid = 'inventory_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();
                    }

                    return $this->inventory_uuid;
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
            'inventory_uuid' => Yii::t('app', 'Inventory Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'ingredient_uuid' => Yii::t('app', 'Ingredient Uuid'),
            'item_variant_uuid' => Yii::t('app', 'Item Variant Uuid'),
            'item_uuid' => Yii::t('app', 'Item Uuid'),
            'stock_quantity' => Yii::t('app', 'Stock Quantity'),
            'restocked_at' => Yii::t('app', 'Restocked At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Ingredient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIngredient($model = 'common\models\RestaurantIngredient')
    {
        return $this->hasOne($model::className(), ['ingredient_uuid' => 'ingredient_uuid']);
    }

    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($model = 'common\models\Item')
    {
        return $this->hasOne($model::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[ItemVariantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemVariant($model = 'common\models\ItemVariant')
    {
        return $this->hasOne($model::className(), ['item_variant_uuid' => 'item_variant_uuid']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($model = 'common\models\Restaurant')
    {
        return $this->hasOne($model::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestockHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestockHistories($model = 'common\models\RestockHistory')
    {
        return $this->hasMany($model::className(), ['inventory_uuid' => 'inventory_uuid']);
    }
}
