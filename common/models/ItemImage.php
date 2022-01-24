<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "item_image".
 *
 * @property int $item_image_id
 * @property string|null $item_uuid
 * @property string|null $product_file_name
 *
 * @property Item $item
 */
class ItemImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_uuid', 'product_file_name'], 'required'],
            [['item_uuid'], 'string', 'max' => 300],
            [['product_file_name'], 'unique'],
            [['product_file_name'], 'string', 'max' => 255],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_image_id' => 'Item Image ID',
            'item_uuid' => 'Item Uuid',
            'product_file_name' => 'Product File Name',
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
            Yii::error('Error while deleting item image to Cloudinry: ' . json_encode($err));
        }
    }

    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($modelClass = "\common\models\Item")
    {
        return $this->hasOne($modelClass::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->via('item');
    }
}
