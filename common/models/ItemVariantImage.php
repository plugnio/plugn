<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "item_variant_image".
 *
 * @property string $item_variant_image_uuid
 * @property string $item_variant_uuid
 * @property string $item_uuid
 * @property string $product_file_name
 * @property int $sort_number
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Item $itemUu
 * @property ItemVariant $itemVariantUu
 */
class ItemVariantImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_variant_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_variant_uuid', 'item_uuid', 'product_file_name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_variant_image_uuid', 'item_variant_uuid'], 'string', 'max' => 60],
            [['item_uuid'], 'string', 'max' => 100],
            [['product_file_name'], 'string', 'max' => 255],
            [['sort_number'], 'number'],
            [['item_variant_image_uuid'], 'unique'],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
            [['item_variant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => ItemVariant::className(), 'targetAttribute' => ['item_variant_uuid' => 'item_variant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_variant_image_uuid' => Yii::t('app', 'Item Variant Image Uuid'),
            'item_variant_uuid' => Yii::t('app', 'Item Variant Uuid'),
            'item_uuid' => Yii::t('app', 'Item Uuid'),
            'product_file_name' => Yii::t('app', 'Product File Name'),
            'sort_number' => Yii::t('app','Sort number'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'item_variant_image_uuid',
                ],
                'value' => function () {
                    if (!$this->item_variant_image_uuid) {
                        $this->item_variant_image_uuid = 'item_variant_image_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

                    return $this->item_variant_image_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function beforeDelete()
    {
        $this->deleteItemImageFromCloudinary();

        return parent::beforeDelete();
    }

    /**
     * Delete Item's image
     */
    public function deleteItemImageFromCloudinary()
    {
        $imageURL = "restaurants/" . $this->restaurant->restaurant_uuid . "/items/" . $this->product_file_name;

        try {
            Yii::$app->cloudinaryManager->delete($imageURL);
        } catch (\Cloudinary\Error $err) {
            //Yii::error('Error while deleting item image to Cloudinry: ' . json_encode($err));
        }
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($model = 'common\models\Restaurant')
    {
        return $this->hasOne($model::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->via('item');
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
}
