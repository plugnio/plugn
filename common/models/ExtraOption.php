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

 * @property int|null $stock_qty

 * @property Option $option
 * @property Item $item
 * @property OrderItemExtraOption[] $orderItemExtraOptions
 */
class ExtraOption extends \yii\db\ActiveRecord {

    /**
     * these are flags that are used by the form to dictate how the loop will handle each item
     */
    const UPDATE_TYPE_CREATE = 'create';
    const UPDATE_TYPE_UPDATE = 'update';
    const UPDATE_TYPE_DELETE = 'delete';
    const SCENARIO_BATCH_UPDATE = 'batchUpdate';

    private $_updateType;

    public function getUpdateType() {
        if (empty($this->_updateType)) {
            if ($this->isNewRecord) {
                $this->_updateType = self::UPDATE_TYPE_CREATE;
            } else {
                $this->_updateType = self::UPDATE_TYPE_UPDATE;
            }
        }

        return $this->_updateType;
    }

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
            ['updateType', 'required', 'on' => self::SCENARIO_BATCH_UPDATE],
            ['updateType',
                'in',
                'range' => [self::UPDATE_TYPE_CREATE, self::UPDATE_TYPE_UPDATE, self::UPDATE_TYPE_DELETE],
                'on' => self::SCENARIO_BATCH_UPDATE
            ],
            [['extra_option_name', 'extra_option_name_ar'], 'required'],
            [['option_id'], 'integer'],
            [['extra_option_price', 'stock_qty'], 'number', 'min' => 0],
            [['extra_option_price'], 'default', 'value' => 0],
            [['stock_qty'], 'default', 'value' => null],
            [['extra_option_name', 'extra_option_name_ar'], 'string', 'max' => 255],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => Option::className(), 'targetAttribute' => ['option_id' => 'option_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'extra_option_id' => Yii::t('app', 'Extra Option ID'),
            'option_id' => Yii::t('app', 'Option ID'),
            'extra_option_name' => Yii::t('app', 'Extra Option Name'),
            'extra_option_name_ar' => Yii::t('app', 'Extra Option Name in Arabic'),
            'extra_option_price' => Yii::t('app', 'Extra Option Price'),
            'stock_qty' => Yii::t('app', 'Stock Quantity')
        ];
    }

    /**
     * increase stock_qty
     * @param number $qty
     */
    public function increaseStockQty($qty)
    {
        if($this->option && $this->option->item && (
                !$this->option->item->track_quantity ||
                $this->option->item->item_type == Item::TYPE_CONFIGURABLE
            )
        ) {
            return true;
        }

        if($this->stock_qty !== null && $this->stock_qty >= 0 ){
          $this->stock_qty += $qty;

          self::updateAll(['stock_qty' => $this->stock_qty], [
              'extra_option_id' => $this->extra_option_id
          ]);
        }
    }

    /**
     * decrease stock_qty
     * @param number $qty
     */
    public function decreaseStockQty($qty)
    {
        if($this->option && $this->option->item && (
            !$this->option->item->track_quantity ||
            $this->option->item->item_type == Item::TYPE_CONFIGURABLE
            )
        ) {
            return true;
        }

        if($this->stock_qty !== null && $this->stock_qty > 0 ){
            $this->stock_qty -= $qty;

            self::updateAll(['stock_qty' => $this->stock_qty], [
                'extra_option_id' => $this->extra_option_id
            ]);
        }
    }

    public function beforeSave($insert) {
        if ($this->extra_option_price == null) {
          $this->extra_option_price = 0;
        }
        return parent::beforeSave($insert);
    }

    public function extraFields()
    {
        return [
            'orderItemExtraOptions'
        ];
    }

    /**
     * Gets query for [[Option]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOption($modelClass = "\common\models\Option") {
        return $this->hasOne($modelClass::className(), ['option_id' => 'option_id']);
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($modelClass = "\common\models\Item") {
        return $this->hasOne($modelClass::className(), ['item_uuid' => 'item_uuid'])->via('option');
    }

    /**
     * Gets query for [[OrderItemExtraOption]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemExtraOptions($modelClass = "\common\models\OrderItemExtraOption") {
        return $this->hasMany($modelClass::className(), ['extra_option_id' => 'extra_option_id']);
    }
}
