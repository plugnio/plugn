<?php

namespace agent\models;

use common\models\ItemImage;
use common\models\Option;
use Yii;
use yii\base\BaseObject;

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
     * Upload item image  to Cloudinary
     * @param type $images
     */
    public function saveItemImages($images)
    {
        //deleteallofThem
//        foreach ($this->getItemImages()->all() as  $itemImage) {
//          $itemImage->delete();
//        }
        if (count($images) > 0) {
            foreach ($images as $img) {
                if ($img['product_file_name']) {
                    try {
                        $url = Yii::$app->temporaryBucketResourceManager->getUrl($img['product_file_name']);
                        $filename = Yii::$app->security->generateRandomString();
                        $result = Yii::$app->cloudinaryManager->upload(
                            $url,
                            [
                                'public_id' => "restaurants/" . $this->restaurant_uuid . "/items/" . $filename
                            ]
                        );

                        if ($result || count($result) > 0) {
                            $item_image_model = new ItemImage();
                            $item_image_model->item_uuid = $this->item_uuid;
                            $item_image_model->product_file_name = basename($result['url']);
                            $item_image_model->save(false);
                        }

                    } catch (\Cloudinary\Error $err) {
                        Yii::error("Error when uploading item's image to Cloudinry: " . json_encode($err));
//                        Yii::error("Error when uploading item's image to Cloudinry: imagesPath Value " . json_encode($images));
                    }
                }
            }
        }
    }

    public function extraFields()
    {
        $fields = parent::extraFields();
        return [
            'itemImage',
            'itemImages',
            'options'
        ];
    }

    public function getOptions()
    {
        return parent::getOptions();
    }

    /**
     * Gets query for [[ItemImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemImages()
    {
        return parent::getItemImages();
    }

    public function getItemImage(){
        return parent::getItemImage();
    }

}
