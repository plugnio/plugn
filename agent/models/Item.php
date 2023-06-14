<?php

namespace agent\models;

use common\models\ItemImage;
use common\models\ItemVideo;
use common\models\Option;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "Item".
 * It extends from \common\models\Item but with custom functionality for Candidate application module
 *
 */
class Item extends \common\models\Item {

    /**
     * @inheritdoc
     */
    public function fields() {
        $fields = parent::fields();

        $fields['unit_sold'] = function($model) {
         return $model->unit_sold;
        };

        $fields['sku'] = function($model) {
         return $model->sku;
        };

        $fields['barcode'] = function($model) {
         return $model->barcode;
        };

        return $fields;
    }

    /**
     * Upload item videos to Cloudinary
     * @param $videos
     * @return array|false
     * @throws \yii\base\Exception
     */
    public function saveItemVideos($videos)
    {
        foreach ($videos as $video) {

            $model = new ItemVideo();
            $model->item_uuid = $this->item_uuid;
            $model->youtube_video_id = $video['youtube_video_id'];

            if (isset($video['product_file_name'])) {

                // check if same image exist then skip

                $exist = $this->getItemVideos()->andWhere(['product_file_name'=>$video['product_file_name']])->exists();

                if ($exist) {
                    continue;
                }

                try {

                    $url = Yii::$app->temporaryBucketResourceManager->getUrl($video['product_file_name']);

                    $filename = Yii::$app->security->generateRandomString();

                    $result = Yii::$app->cloudinaryManager->upload(
                        $url,
                        [
                            'public_id' => "restaurants/" . $this->restaurant_uuid . "/items/" . $filename
                        ]
                    );

                    $model->product_file_name = basename($result['ObjectURL']);

                } catch (\Cloudinary\Error $err) {
                    //Yii::error("Error when uploading item's image to Cloudinry: imagesPath Value " . json_encode($images));

                    //todo: show cloudinary error in api response
                    return false;
                }
            }

            $model->save(false);
        }
    }

    /**
     * Upload item image  to Cloudinary
     * @param $images
     * @return array|false
     * @throws \yii\base\Exception
     */
    public function saveItemImages($images)
    {
        $data = [];

        if (count($images) > 0) {
            foreach ($images as $img) {
                if ($img['product_file_name']) {

                    // check if same image exist then skip

                    $exist = $this->getItemImages()->andWhere(['product_file_name'=>$img['product_file_name']])->exists();

                    if ($exist) {
                        continue;
                    }

                    try {
                        $url = Yii::$app->temporaryBucketResourceManager->getUrl($img['product_file_name']);
                        $filename = Yii::$app->security->generateRandomString();
                        $data[] = $result = Yii::$app->cloudinaryManager->upload(
                            $url,
                            [
                                'public_id' => "restaurants/" . $this->restaurant_uuid . "/items/" . $filename
                            ]
                        );

                        $item_image_model = new ItemImage();
                        $item_image_model->item_uuid = $this->item_uuid;
                        $item_image_model->product_file_name = basename($result['url']);
                        $item_image_model->save(false);

                    } catch (\Cloudinary\Error $err) {
                        //Yii::error("Error when uploading item's image to Cloudinry: imagesPath Value " . json_encode($images));

                        //todo: show cloudinary error in api response
                        return false;
                    }
                }
            }
        }
        return $data;
    }

    public function extraFields()
    {
        $fields = parent::extraFields ();

        return array_merge ($fields, [
            'itemImage',
            'itemImages',
            'itemVideos',
            'categoryItems',
            'currency'
        ]);
    }

    public function getOptions($modelClass = "\agent\models\Option")
    {
        return parent::getOptions($modelClass);
    }


    /**
     * Gets query for [[ItemImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemVideos($model = '\common\models\ItemVideo')
    {
        return parent::getItemVideos($model);
    }

    /**
     * Gets query for [[ItemImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemImages($model = '\agent\models\ItemImage')
    {
        return parent::getItemImages($model);
    }

    /**
     * @param string $model
     * @return \yii\db\ActiveQuery
     */
    public function getItemImage($model = '\agent\models\ItemImage'){
        return parent::getItemImage($model);
    }

    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryItems($model = '\agent\models\CategoryItem')
    {
        return parent::getCategoryItems($model);
    }

    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory($modelClass = "\agent\models\Category")
    {
        return parent::getCategory ($modelClass);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories($modelClass = "\agent\models\Category")
    {
        return parent::getCategories ($modelClass);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurant ($modelClass);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\agent\models\Currency")
    {
        return parent::getCurrency($modelClass);
    }

    /**
     * Gets query for [[Options]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtraOptions($modelClass = "\agent\models\ExtraOption")
    {
        return parent::getExtraOptions($modelClass);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems($modelClass = "\agent\models\OrderItem")
    {
        return parent::getOrderItems($modelClass);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\agent\models\Order")
    {
        return parent::getOrder($modelClass);
    }
}
