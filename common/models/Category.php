<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $category_id
 * @property int $restaurant_uuid
 * @property string|null $title
 * @property string|null $title_ar
 * @property string|null $subtitle
 * @property string|null $subtitle_ar
 * @property string $category_image
 * @property int|null $sort_number
 *
 * @property CategoryItem[] $categoryItems
 * @property Item[] $itemUus
 */
class Category extends \yii\db\ActiveRecord {

    public $image;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['title', 'title_ar'], 'required'],
            [['image'], 'file', 'extensions' => 'jpg, jpeg , png', 'maxFiles' => 1],
            [['sort_number'], 'integer', 'min' => 0],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['title', 'title_ar', 'subtitle', 'subtitle_ar'], 'string', 'max' => 255],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'category_id' => 'Category ID',
            'title' => 'Title',
            'title_ar' => 'Title in Arabic',
            'subtitle' => 'Subtitle',
            'image' => 'Category Image',
            'subtitle_ar' => 'Subtitle in Arabic',
            'sort_number' => 'Sort Number',
            'restaurant_uuid' => 'Restaurant UUID',
        ];
    }

    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert && isset($changedAttributes['category_image']) && $this->image) {
            if ($changedAttributes['category_image']) {
                $this->deleteCategoryImage($changedAttributes['category_image']);
            }
        }
    }

    /**
     * Delete Category Image
     */
    public function deleteCategoryImage($category_image = null) {
        if (!$category_image)
          $category_image = $this->category_image;

        $imageURL = "restaurants/" . $this->restaurant_uuid . "/category/" . $category_image;

        try {
            Yii::$app->cloudinaryManager->delete($imageURL);
        } catch (\Cloudinary\Error $err) {
            Yii::error('Error while deleting thumbnail image to Cloudinry: ' . json_encode($err));
        }
    }

    public function beforeDelete() {
      if($this->category_image)
        $this->deleteCategoryImage();
        return parent::beforeDelete();
    }



        /**
         * Return Category Image url
         */
        public function getCategoryImage() {
            return 'https://res.cloudinary.com/plugn/image/upload/c_scale,w_600/restaurants/' . $this->restaurant_uuid . "/category/" . $this->category_image;
        }



    /**
     * Upload category image  to cloudinary
     * @param type $imageURL
     */
    public function uploadCategoryImage($imageURL) {

        $filename = Yii::$app->security->generateRandomString();

        try {
            $result = Yii::$app->cloudinaryManager->upload(
                    $imageURL, [
                'public_id' => "restaurants/" . $this->restaurant_uuid . "/category/" . $filename
                    ]
            );

            //Delete old store's image
            if ($this->category_image) {
                $this->deleteCategoryImage();
            }


            if ($result || count($result) > 0) {
                $this->category_image = basename($result['url']);
                $this->save();
            }
        } catch (\Cloudinary\Error $err) {
            Yii::error("Error when uploading category image to Cloudinry: " . json_encode($err));
            Yii::error("Error when uploading category image to Cloudinry: ImageUrl Value " . json_encode($imageURL));
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryItems() {
        return $this->hasMany(CategoryItem::className(), ['category_id' => 'category_id']);
    }

    /**
     * Gets query for [[ItemUus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems() {
        return $this->hasMany(Item::className(), ['item_uuid' => 'item_uuid'])
                        ->viaTable('category_item', ['category_id' => 'category_id'])
                        ->orderBy(['sort_number' => SORT_ASC]);
    }

}
