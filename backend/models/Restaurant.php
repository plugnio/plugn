<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "restaurant".
 *
 *  New fields added to create a merchant account on tap
 *
 * @property string $business_id
 * @property string $developer_id
 * @property string $business_entity_id
 * @property string $wallet_id
 * @property string $merchant_id
 * @property string $operator_id
 * @property string $live_api_key
 * @property string $test_api_key
 * @property string $business_type
 * @property string $vendor_sector
 * @property string $license_number
 * @property int $not_for_profit
 * @property string $document_issuing_country
 * @property string|null $document_issuing_date
 * @property string|null $document_expiry_date
 * @property string $document_file_id
 * @property string $document_file
 * @property string $document_title
 * @property string $document_file_purpose
 * @property string $iban
 * @property string $owner_first_name
 * @property string $owner_last_name
 * @property string $owner_email
 * @property string $owner_customer_number
 * @property string $identification_issuing_country
 * @property string|null $identification_issuing_date
 * @property string|null $identification_expiry_date
 * @property string $identification_file
 * @property string $identification_file_id
 * @property string $identification_title
 * @property string $identification_file_purpose
 */
class Restaurant extends \common\models\Restaurant {

    public $owner_identification_file;
    public $restaurant_document_file;

    const SCENARIO_CREATE_TAP_ACCOUNT = 'tap_account';
    const SCENARIO_CREATE = 'create';

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_merge(parent::rules(), [
            [['owner_first_name', 'owner_last_name', 'owner_email', 'owner_customer_number'], 'required', 'on' => 'create'],
            //All new fields added are required in order to create an account on Tap
            [
                [
//                    'business_id', 'developer_id', 'business_entity_id', 'wallet_id', 'merchant_id', 'operator_id',
                    'vendor_sector', 'iban',
                    'identification_title', 'identification_issuing_country',
                    'identification_issuing_date', 'identification_expiry_date',
                    'owner_identification_file', 'identification_file_purpose',
//                    'live_api_key', 'test_api_key'
                ],
                'required', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT
            ],
            [['owner_first_name', 'owner_last_name'], 'string', 'min' => 3],
            [['identification_file_id', 'document_file_id'], 'safe', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],
            [['not_for_profit'], 'number'],
            [['document_issuing_date', 'document_expiry_date', 'identification_issuing_date', 'identification_expiry_date'], 'safe', 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],
            ['owner_email', 'email'],
            [
                [
                    'business_type', 'vendor_sector', 'license_number',
                    'document_issuing_country', 'document_issuing_date', 'document_expiry_date',
                    'document_file', 'document_file_purpose', 'iban', 'owner_first_name', 'owner_last_name',
                    'owner_email', 'owner_customer_number',
                    'identification_issuing_country', 'identification_issuing_date',
                    'document_title', 'identification_title',
                    'identification_expiry_date', 'identification_file', 'identification_file_purpose',
                    'business_id', 'business_entity_id', 'wallet_id', 'merchant_id', 'operator_id',
                    'live_api_key', 'test_api_key'  ,'developer_id'
                ],
                'string', 'max' => 255
            ],
            [['restaurant_document_file', 'owner_identification_file'], 'file', 'skipOnEmpty' => true, 'on' => self::SCENARIO_CREATE_TAP_ACCOUNT],
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
            'document_issuing_country' => 'Document Issuing Country',
            'document_issuing_date' => 'Document Issuing Date',
            'document_expiry_date' => 'Document Expiry Date',
            'document_file' => 'Document File',
            'restaurant_document_file' => 'Store Document File',
            'document_title' => 'Document Title',
            'document_file_purpose' => 'Document File Purpose',
            'iban' => 'IBAN',
            'owner_first_name' => 'Owner First Name',
            'owner_last_name' => 'Owner Last Name',
            'owner_email' => 'Owner Email',
            'owner_customer_number' => 'Owner Customer Number',
            'identification_issuing_country' => 'Identification Issuing Country',
            'identification_issuing_date' => 'Identification Issuing Date',
            'identification_expiry_date' => 'Identification Expiry Date',
            'identification_file' => 'Identification File',
            'identification_title' => 'Identification Title',
            'identification_file_purpose' => 'Identification File Purpose'
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
    public function uploadFileToCloudinary($file_path, $filename, $attribute) {

        if ($filename) {
            try {

                $result = Yii::$app->cloudinaryManager->upload(
                        $file_path, [
                    'public_id' => "restaurants/" . $this->restaurant_uuid . "/private_documents/" . $filename
                        ]
                );

                if ($result || count($result) > 0) {
                    //delete the file from temp folder
                    unlink($file_path);
                    $this[$attribute] = basename($result['url']);
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

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if ($this->scenario == self::SCENARIO_CREATE_TAP_ACCOUNT) {
            //delete tmp files
            $this->deleteTempFiles();
        }
    }

    /**
     * Deletes the files associated with this project
     */
    public function deleteTempFiles() {

        if ($this->document_file && file_exists(Yii::getAlias('@projectFiles') . "/" . $this->document_file)) {
            $this->uploadFileToCloudinary(Yii::getAlias('@projectFiles') . "/" . $this->document_file, $this->document_file, 'document_file');
        }
        if ($this->identification_file && file_exists(Yii::getAlias('@projectFiles') . "/" . $this->identification_file)) {
            $this->uploadFileToCloudinary(Yii::getAlias('@projectFiles') . "/" . $this->identification_file, $this->identification_file, 'identification_file');
        }
    }

    public function uploadDocumentsToTap() {
        $this->processFileUploads();

        if ($this->document_expiry_date && $this->document_issuing_date && $this->document_issuing_country && $this->document_file_purpose && $this->document_title) {

            //Upload Document file
            $response = Yii::$app->tapPayments->uploadFileToTap(
                    Yii::getAlias('@projectFiles') . "/" . $this->document_file, $this->document_file_purpose, $this->document_title);

            if ($response->isOk) {
                $this->document_file_id = $response->data['id'];
            }
        }


        //Upload Owner civil id
        $response = Yii::$app->tapPayments->uploadFileToTap(
                Yii::getAlias('@projectFiles') . "/" . $this->identification_file, $this->identification_file_purpose, $this->identification_title);

        if ($response->isOk) {
            $this->identification_file_id = $response->data['id'];
        }
    }

    /**
     * Create an account for vendor on tap
     * @param type $documentFile
     */
    public function createAMerchantAccountOnTap() {
        //Upload temp file on our server after we create an account on tap we gonaa delete them
        $this->uploadDocumentsToTap();

        //Create a business for a vendor on Tap
        $businessApiResponse = Yii::$app->tapPayments->createBussiness($this);

        if ($businessApiResponse->isOk) {
            $this->business_id = $businessApiResponse->data['id'];
            $this->business_entity_id = $businessApiResponse->data['entity']['id'];
//            $this->developer_id = $businessApiResponse->data['entity']['operator']['developer_id'];

        }

        //Create a merchant on Tap
        $merchantApiResponse = Yii::$app->tapPayments->createMergentAccount($this->name, $this->business_id, $this->business_entity_id, $this->iban);

        if ($merchantApiResponse->isOk) {
            $this->merchant_id = $merchantApiResponse->data['id'];
            $this->wallet_id = $merchantApiResponse->data['wallets']['id'];
        }
        

       //Create an Operator
//       $operatorApiResponse = Yii::$app->tapPayments->createAnOperator($this->name, $this->wallet_id, $this->developer_id);

 
       if ($operatorApiResponse->isOk) {
           $this->operator_id = $operatorApiResponse->data['id'];
           $this->test_api_key = $operatorApiResponse->data['api_credentials']['test']['secret'];

           if (array_key_exists('live', $operatorApiResponse->data['api_credentials']))
               $this->live_api_key = $operatorApiResponse->data['api_credentials']['live']['secret'];
       }

    }

}
