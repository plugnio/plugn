<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "restaurant".
 *
 *  New fields added to create a merchant account on tap
 * 
 * @property string $business_type 
 * @property string $vendor_sector 
 * @property string $license_number 
 * @property int $not_for_profit 
 * @property string $document_type 
 * @property string $document_issuing_country 
 * @property string|null $document_issuing_date 
 * @property string|null $document_expiry_date 
 * @property string $document_file_id
 * @property string $document_file 
 * @property string $document_title
 * @property string $document_file_purpose 
 * @property int $document_file_link_create 
 * @property string $iban 
 * @property string $owner_first_name 
 * @property string $owner_last_name 
 * @property string $owner_email 
 * @property string $owner_customer_number 
 * @property string $identification_type 
 * @property string $identification_issuing_country 
 * @property string|null $identification_issuing_date 
 * @property string|null $identification_expiry_date 
 * @property string $identification_file 
 * @property string $identification_file_id 
 * @property string $identification_title 
 * @property string $identification_file_purpose 
 * @property int $identification_file_link_create
 */
class Restaurant extends \common\models\Restaurant {

    public $owner_identification_file;
    public $restaurant_document_file;

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_merge(parent::rules(), [
            //All new fields added are required in order to create an account on Tap
            [
                [
                    'business_type', 'vendor_sector', 'license_number', 'not_for_profit',
//                    'document_type',
                    'document_issuing_country', 'document_issuing_date',
                    'document_expiry_date',  'restaurant_document_file',
                    'document_file_purpose','document_file_link_create', 'owner_last_name',
                    'owner_email', 'owner_customer_number',
                    'iban', 'owner_first_name',
                    'document_title', 'identification_title', 'identification_issuing_country',
                    'identification_issuing_date', 'identification_expiry_date', 
//                    'identification_type',
                    'owner_identification_file', 'identification_file_purpose', 'identification_file_link_create'
                ],
                'required'
            ],
            [['identification_file_id','document_file_id'] , 'safe'],
            [['not_for_profit', 'document_file_link_create', 'identification_file_link_create'], 'number'],
            [['document_issuing_date', 'document_expiry_date', 'identification_issuing_date', 'identification_expiry_date'], 'safe'],
            ['owner_email', 'email'],
            [
                [
                    'business_type', 'vendor_sector', 'license_number', 'document_type',
                    'document_issuing_country', 'document_issuing_date', 'document_expiry_date',
                    'document_file', 'document_file_purpose', 'iban', 'owner_first_name', 'owner_last_name',
                    'owner_email', 'owner_customer_number', 'identification_type',
                    'identification_issuing_country', 'identification_issuing_date',
                    'document_title', 'identification_title',
                    'identification_expiry_date', 'identification_file', 'identification_file_purpose'
                ],
                'string', 'max' => 255
            ],
            [['restaurant_document_file', 'owner_identification_file'], 'file', 'extensions' => 'jpg , png', 'maxFiles' => 1],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'business_type' => 'Business Type',
            'vendor_sector' => 'Vendor Sector',
            'license_number' => 'License Number',
            'not_for_profit' => 'Not For Profit',
            'document_type' => 'Document Type',
            'document_issuing_country' => 'Document Issuing Country',
            'document_issuing_date' => 'Document Issuing Date',
            'document_expiry_date' => 'Document Expiry Date',
            'document_file' => 'Document File',
            'restaurant_document_file' => 'Restaurant Document File',
            'document_title' => 'Document Title',
            'document_file_purpose' => 'Document File Purpose',
            'document_file_link_create' => 'Document File Link Create',
            'iban' => 'Iban',
            'owner_first_name' => 'Owner First Name',
            'owner_last_name' => 'Owner Last Name',
            'owner_email' => 'Owner Email',
            'owner_customer_number' => 'Owner Customer Number',
            'identification_type' => 'Identification Type',
            'identification_issuing_country' => 'Identification Issuing Country',
            'identification_issuing_date' => 'Identification Issuing Date',
            'identification_expiry_date' => 'Identification Expiry Date',
            'identification_file' => 'Identification File',
            'identification_title' => 'Identification Title',
            'identification_file_purpose' => 'Identification File Purpose',
            'identification_file_link_create' => 'Identification File Link Create'
        ]);
    }

    /**
     * Processes file uploads if there are file inputs available
     */
    public function processFileUploads() {
        $this->_uploadTempFile($this->restaurant_document_file, 'document_file');
        $this->_uploadTempFile($this->owner_identification_file, 'identification_file');
    }

    /**
     * Upload a File to cloudinary
     * @param type $imageURL
     */
    public function uploadFileToCloudinary($file, $attribute) {

        if ($file) {
            try {
                $filename = Yii::$app->security->generateRandomString();

                $result = Yii::$app->cloudinaryManager->upload(
                        $file->tempName, [
                    'public_id' => "restaurants/" . $this->restaurant_uuid . "/private_documents/" . $filename
                        ]
                );

                if ($result || count($result) > 0) {
                    $this[$attribute] = basename($result['url']);
                    $this->save();
                }
            } catch (\Cloudinary\Error $err) {
                Yii::error('Error when uploading venue photos to Cloudinry: ' . json_encode($err));
            }
        }
    }

    /**
     * Processes a file upload
     * @param UploadedFile $file instance of yii\web\UploadedFile that will be uploaded into the attribute
     * @param string $attribute attribute of this model that will be updated if the file is successfully uploaded
     */
    public function _uploadTempFile($file, $attribute) {

        if ($file) {
            $filename = Yii::$app->security->generateRandomString() . "." . $file->extension;
            $uploadPath = Yii::getAlias('@projectFiles');

            $file->saveAs($uploadPath . "/" . $filename);

            // Delete old file that was stored within the attribute if exists
            $oldFile = $uploadPath . "/" . $this[$attribute];
            if ($this[$attribute] && file_exists($oldFile)) {
                unlink($oldFile);
            }

            // Set this models attribute to the new filename
            $this[$attribute] = $filename;
        }
    }

    /**
     * Deletes the files associated with this project
     */
    public function deleteTempFiles() {
        if ($this->document_file && file_exists(Yii::getAlias('@projectFiles') . "/" . $this->document_file))
            unlink(Yii::getAlias('@projectFiles') . "/" . $this->document_file);
        if ($this->identification_file && file_exists(Yii::getAlias('@projectFiles') . "/" . $this->identification_file))
            unlink(Yii::getAlias('@projectFiles') . "/" . $this->identification_file);
    }

    /**
     * Create an account for vendor on tap
     * @param type $documentFile
     */
    public function createAMerchantAccountOnTap() {
        //Upload temp file after we create an account on tap we gonaa delete them
        $this->processFileUploads();
     
        
        //Upload Document file
        $response = Yii::$app->tapPayments->uploadFileToTap(
                        Yii::getAlias('@projectFiles') . "/" . $this->document_file,
                        $this->document_file_purpose, $this->document_title,
                        $this->document_file_link_create);
        
        if($response->isOk){
            $this->document_file_id = $response->data['id'];
            $this->save(false);
        }
        
        
        
        //Upload Owner civil id
        $response = Yii::$app->tapPayments->uploadFileToTap(
                        Yii::getAlias('@projectFiles') . "/" . $this->identification_file,
                        $this->identification_file_purpose, $this->identification_title,
                        $this->identification_file_link_create);
        
        if($response->isOk){
            $this->identification_file_id = $response->data['id'];
            $this->save(false);
        }
        
        
                
        
        //delete tmp files
        $this->deleteTempFiles();
    }

}
