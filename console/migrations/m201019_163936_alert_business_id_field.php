<?php

use yii\db\Migration;

/**
 * Class m201019_163936_alert_business_id_field
 */
class m201019_163936_alert_business_id_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('restaurant', 'business_id',  $this->string());
      $this->alterColumn('restaurant', 'business_entity_id',  $this->string());
      $this->alterColumn('restaurant', 'wallet_id',  $this->string());
      $this->alterColumn('restaurant', 'merchant_id',  $this->string());
      $this->alterColumn('restaurant', 'operator_id',  $this->string());
      $this->alterColumn('restaurant', 'test_api_key',  $this->string());
      $this->alterColumn('restaurant', 'business_type',  $this->string());
      $this->alterColumn('restaurant', 'vendor_sector',  $this->string());
      $this->alterColumn('restaurant', 'authorized_signature_issuing_date',  $this->string());
      $this->alterColumn('restaurant', 'authorized_signature_expiry_date',  $this->string());
      $this->alterColumn('restaurant', 'authorized_signature_title',  $this->string());
      $this->alterColumn('restaurant', 'authorized_signature_file_purpose',  $this->string());
      $this->alterColumn('restaurant', 'iban',  $this->string());
      $this->alterColumn('restaurant', 'owner_first_name',  $this->string());
      $this->alterColumn('restaurant', 'owner_last_name',  $this->string());
      $this->alterColumn('restaurant', 'owner_email',  $this->string());
      $this->alterColumn('restaurant', 'owner_number',  $this->string());
      $this->alterColumn('restaurant', 'identification_issuing_date',  $this->string());
      $this->alterColumn('restaurant', 'identification_expiry_date',  $this->string());
      $this->alterColumn('restaurant', 'identification_title',  $this->string());
      $this->alterColumn('restaurant', 'identification_file_purpose',  $this->string());
      $this->alterColumn('restaurant', 'developer_id',  $this->string());
      $this->alterColumn('restaurant', 'commercial_license_issuing_date',  $this->string());
      $this->alterColumn('restaurant', 'commercial_license_expiry_date',  $this->string());
      $this->alterColumn('restaurant', 'commercial_license_title',  $this->string());
      $this->alterColumn('restaurant', 'commercial_license_file_purpose',  $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('restaurant', 'business_id',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'business_entity_id',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'wallet_id',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'merchant_id',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'operator_id',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'test_api_key',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'vendor_sector',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'authorized_signature_issuing_date',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'authorized_signature_expiry_date',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'authorized_signature_title',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'authorized_signature_file_purpose',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'iban',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'owner_first_name',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'owner_last_name',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'owner_email',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'owner_number',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'identification_expiry_date',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'identification_title',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'identification_file_purpose',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'developer_id',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'commercial_license_issuing_date',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'commercial_license_expiry_date',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'commercial_license_title',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'commercial_license_file_purpose',  $this->string()->notNull());
    }

}
