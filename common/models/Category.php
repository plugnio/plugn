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
class Category extends \yii\db\ActiveRecord
{

    public $image;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'title_ar'], 'required'],
            //Upload Category Image
            // ['category_image', 'file', 'extensions' => 'jpg, png, gif', 'maxSize' => 10000000,
            //     'wrongExtension' => Yii::t('app', 'Only files with these extensions are allowed for your Image: {extensions}')
            // ],
            [['image'], 'file', 'extensions' => 'jpg, jpeg , png', 'maxFiles' => 1],
            [['sort_number'], 'integer', 'min' => 0],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['title', 'title_ar', 'subtitle', 'subtitle_ar'], 'string', 'max' => 255],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className (), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
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
     * Scenarios for validation and massive assignment
     */
    // public function scenarios() {
    //     $scenarios = parent::scenarios();
    //
    //     $scenarios['updateImage'] = ['employer_logo'];
    //
    //     return $scenarios;
    // }

    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave ($insert, $changedAttributes);

        if (!$insert && isset($changedAttributes['category_image']) && $this->image) {
            if ($changedAttributes['category_image']) {
                $this->deleteCategoryImage ($changedAttributes['category_image']);
            }
        }
    }

    /**
     * Delete Category Image
     */
    public function deleteCategoryImage($category_image = null)
    {
        if (!$category_image)
            $category_image = $this->category_image;

        $imageURL = "restaurants/" . $this->restaurant_uuid . "/category/" . $category_image;

        try {
            Yii::$app->cloudinaryManager->delete ($imageURL);

            $this->category_image = null;

        } catch (\Cloudinary\Error $err) {
            Yii::error ('Error while deleting thumbnail image to Cloudinry: ' . json_encode ($err));
        }
    }

    public function beforeDelete()
    {
        if ($this->category_image)
            $this->deleteCategoryImage ();

        return parent::beforeDelete ();
    }


    /**
     * Return Category Image url
     */
    public function getCategoryImage()
    {
        return 'https://res.cloudinary.com/plugn/image/upload/c_scale,w_600/restaurants/' . $this->restaurant_uuid . "/category/" . $this->category_image;
    }


    /**
     * Upload category image to cloudinary
     * @param type $imageURL
     */
    public function uploadCategoryImage($imageURL)
    {
        $filename = Yii::$app->security->generateRandomString ();

        try {
            $result = Yii::$app->cloudinaryManager->upload (
                $imageURL, [
                    'public_id' => "restaurants/" . $this->restaurant_uuid . "/category/" . $filename
                ]
            );

            //Delete old store's image
            if ($this->category_image) {
                $this->deleteCategoryImage ();
            }

            if ($result || count ($result) > 0) {
                $this->category_image = basename ($result['url']);
                //$this->save ();
            }

            if(!str_contains ($imageURL, 'amazonaws.com'))
                unlink ($imageURL);

        } catch (\Cloudinary\Error $err) {
            Yii::error ("Error when uploading category image to Cloudinry: " . json_encode ($err));
            Yii::error ("Error when uploading category image to Cloudinry: ImageUrl Value " . json_encode ($imageURL));
        }
    }

    /**
     * Upload category image to cloudinary
     * @param type $imageURL
     */
    public function updateImage($imageURL)
    {
        $filename = Yii::$app->security->generateRandomString ();

        try {
            //Delete old category image

            if ($this->category_image) {
                $this->deleteCategoryImage ();
            }

            if(!$imageURL) {
                return true;
            }

            $result = Yii::$app->cloudinaryManager->upload (
                $imageURL,
                [
                    'public_id' => "restaurants/" . $this->restaurant_uuid . "/category/" . $filename
                ]
            );

            if ($result || count ($result) > 0) {
                $this->category_image = basename ($result['url']);
            }

            if(!str_contains ($imageURL, 'amazonaws.com'))
                unlink ($imageURL);

            return true;

        } catch (\Cloudinary\Error $err) {
            Yii::error ("Error when uploading category image to Cloudinry: " . json_encode ($err));
        }
    }

    /**
     * Upload category image to cloudinary
     */
    public function moveCategoryImageFromS3toCloudinary()
    {
        if (!$this->category_image) {
            $this->addError ('category image', Yii::t ('app', 'Image not available to save.'));
            return false;
        }

        try {
            $url = Yii::$app->temporaryBucketResourceManager->getUrl ($this->category_image);

            $filename = Yii::$app->security->generateRandomString ();

            $result = Yii::$app->cloudinaryManager->upload (
                $url, [
                    'public_id' => "restaurants/" . $this->restaurant_uuid . "/category/" . $filename
                ]
            );

            if ($result) {
                $this->category_image = basename ($result['url']);
                // $this->scenario = 'updateLogo';
                return $this->save ();
            }
        } catch (\Cloudinary\Error  $err) {

            $this->addError ('category_image', Yii::t ('app', 'Please try again.'));
            Yii::error ("Error when uploading category image to Cloudinry: " . json_encode ($err));

            return false;

        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne ($modelClass::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryItems($modelClass = "\common\models\CategoryItem")
    {
        return $this->hasMany ($modelClass::className (), ['category_id' => 'category_id']);
    }

    /**
     * Gets query for [[ItemUus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems($modelClass = "\common\models\Item")
    {
        return $this->hasMany ($modelClass::className (), ['item_uuid' => 'item_uuid'])
//            ->andWhere (['item_status' => Item::ITEM_STATUS_PUBLISH])
            ->viaTable ('category_item', ['category_id' => 'category_id'])
            ->orderBy ([new \yii\db\Expression('item.sort_number IS NULL, item.sort_number ASC')]);
    }
}
