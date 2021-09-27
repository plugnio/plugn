<?php
namespace backend\models;

use Yii;
use yii\base\Model;


class TranferExcel extends Model
{
    /**
     * @var UploadedFile
     */
    public $excel;

    public function rules()
    {
        return [
            [
                'excel',
                'file',
                'extensions' => 'xlsx,xls'
            ]
        ];
    }



    /**
     * Upload transferFile to AWS
     * @param type $imageURL
     */
    public function uploadTransferFile($fileUrl) {
        $filename = Yii::$app->security->generateRandomString();

            try {

                $result = Yii::$app->cloudinaryManager->upload(
                        $fileUrl , [
                    'public_id' => "transfer-files/"  . $filename,
                    "resource_type" => "auto",
                    "type" => "authenticated"
                        ]
                );

                // if ($result || count($result) > 0) {
                //
                //     //delete the file from temp folder
                //     unlink($tmpFile );
                //     $this[$attribute] = basename($result['url']);
                // } else {
                //   die('enterr else');
                // }
            } catch (\Cloudinary\Error $err) {
                Yii::error('Error when uploading restaurant document to Cloudinary: ' . json_encode($err));
                die('Error when uploading restaurant document to Cloudinary: ' . json_encode($err));
            }
            
            return basename($result['url']);
    }


}
