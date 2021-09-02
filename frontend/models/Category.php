<?php

namespace frontend\models;

use Yii;

class Category extends \common\models\Category
{

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
               $this->save ();
           }

           unlink ($imageURL);


       } catch (\Cloudinary\Error $err) {
           Yii::error ("Error when uploading category image to Cloudinry: " . json_encode ($err));
           Yii::error ("Error when uploading category image to Cloudinry: ImageUrl Value " . json_encode ($imageURL));
       }
   }

}
