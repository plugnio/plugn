<?php

use yii\db\Migration;

/**
 * Class m200523_211901_rename_fields_in_restaurant_table
 */
class m200523_211901_rename_fields_in_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('restaurant', 'document_issuing_country', 'authorized_signature_issuing_country');
        $this->renameColumn('restaurant', 'document_issuing_date', 'authorized_signature_issuing_date');
        $this->renameColumn('restaurant', 'document_expiry_date', 'authorized_signature_expiry_date');
        $this->renameColumn('restaurant', 'document_title', 'authorized_signature_title');
        $this->renameColumn('restaurant', 'document_file', 'authorized_signature_file');
        $this->renameColumn('restaurant', 'document_file_id', 'authorized_signature_file_id');
        $this->renameColumn('restaurant', 'document_file_purpose', 'authorized_signature_file_purpose');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('restaurant', 'authorized_signature_issuing_country', 'document_issuing_country');
        $this->renameColumn('restaurant', 'authorized_signature_issuing_date', 'document_issuing_date');
        $this->renameColumn('restaurant', 'authorized_signature_expiry_date', 'document_expiry_date');
        $this->renameColumn('restaurant', 'authorized_signature_expiry_title', 'document_title');
        $this->renameColumn('restaurant', 'authorized_signature_file', 'document_file');
        $this->renameColumn('restaurant', 'authorized_signature_file_id', 'document_file_id');
        $this->renameColumn('restaurant', 'authorized_signature_file_purpose', 'document_file_purpose');
    }
}
