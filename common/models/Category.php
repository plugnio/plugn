<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "category".
 *
 * @property int $category_id
 * @property int $restaurant_uuid
 * @property string|null $title
 * @property string|null $title_ar
 * @property string|null $subtitle
 * @property string|null $subtitle_ar
 * @property string|null $category_meta_title
 * @property string|null $category_meta_title_ar
 * @property string|null $category_meta_description
 * @property string|null $category_meta_description_ar
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
            [['title', 'title_ar', 'restaurant_uuid'], 'required'],
            //Upload Category Image
            // ['category_image', 'file', 'extensions' => 'jpg, png, gif', 'maxSize' => 10000000,
            //     'wrongExtension' => Yii::t('app', 'Only files with these extensions are allowed for your Image: {extensions}')
            // ],
            [['image'], 'file', 'extensions' => 'jpg, jpeg , png', 'maxFiles' => 1],
            [['sort_number'], 'integer', 'min' => 0],
            [['restaurant_uuid'], 'string', 'max' => 60],
            ['slug', 'safe'],
            [['title', 'title_ar', 'subtitle', 'subtitle_ar'], 'string', 'max' => 255],
            [['category_meta_title', 'category_meta_title_ar', 'category_meta_description', 'category_meta_description_ar'], 'string'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className (), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_id' => Yii::t('app', 'Category ID'),
            'title' => Yii::t('app', 'Title'),
            'title_ar' => Yii::t('app', 'Title in Arabic'),
            'subtitle' => Yii::t('app', 'Subtitle'),
            'image' => Yii::t('app', 'Category Image'),
            'subtitle_ar' => Yii::t('app', 'Subtitle in Arabic'),
            'category_meta_title' => Yii::t('app', 'Page Title'),
            'category_meta_title_ar' => Yii::t('app', 'Page Title in Arabic'),
            'category_meta_description' => Yii::t('app', 'Meta tag description'),
            'category_meta_description_ar' => Yii::t('app', 'Meta tag description in Arabic'),
            'sort_number' => Yii::t('app', 'Sort Number'),
            'slug' => Yii::t('app', 'Slug'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant UUID')
        ];
    }

    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'ensureUnique' => true,
                'uniqueValidator' => ['targetAttribute' => ['restaurant_uuid', 'slug']]
            ],
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

            $props = [
                "category_id" => $this->category_id,
                "category_name_english" => $this->title,
                "category_name_arabic" => $this->title_ar,
                "category_subtitle_english" => $this->subtitle,
                "category_subtitle_arabic" => $this->subtitle_ar,
                "meta_tag_description_english" => $this->category_meta_description,
                "meta_tag_description_arabic" => $this->category_meta_description_ar,
            ];
            
            if($insert) {

                Yii::$app->eventManager->track('Category Added', $props,
                    null,
                    $this->restaurant_uuid
                );
            }
            else
            {
                Yii::$app->eventManager->track('Category Updated', $props,
                    null,
                    $this->restaurant_uuid
                );
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
            //Yii::error ('Error while deleting thumbnail image to Cloudinry: ' . json_encode ($err));
        }
    }

    public function beforeDelete()
    {
        if ($this->category_image)
            $this->deleteCategoryImage ();

        return parent::beforeDelete ();
    }


    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            'noOfItems' => function($data) {
                return $data->getCategoryItems()->count();
            }
        ];
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
            //todo: notify vendor in api response?

            //Yii::error ("Error when uploading category image to Cloudinry: " . json_encode ($err));
            //Yii::error ("Error when uploading category image to Cloudinry: ImageUrl Value " . json_encode ($imageURL));
        }
    }

    /**
     * Upload category image to cloudinary
     * @param type $imageURL
     */
    public function updateImage($imageURL)
    {
        $filename = Yii::$app->security->generateRandomString ();

        if($imageURL && !str_contains($imageURL, "https://")) {
            $imageURL = Yii::$app->temporaryBucketResourceManager->getUrl($imageURL);
        }

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
            //todo: notify vendor?
            //Yii::error ("Error when uploading category image to Cloudinry: " . json_encode ($err));

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

            //Yii::error ("Error when uploading category image to Cloudinry: " . json_encode ($err));

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
