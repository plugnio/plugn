<?php

use yii\db\Migration;

/**
 * Class m201026_212912_alert_authorized_signature_purpose_field
 */
class m201026_212912_alert_authorized_signature_purpose_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('restaurant', 'authorized_signature_file_purpose',  $this->string()->defaultValue('customer_signature'));
      $this->alterColumn('restaurant', 'commercial_license_file_purpose',  $this->string()->defaultValue('customer_signature'));
      $this->alterColumn('restaurant', 'identification_file_purpose',  $this->string()->defaultValue('identity_document'));


      $this->alterColumn('restaurant', 'authorized_signature_title',  $this->string()->defaultValue('Authorized Signature'));
      $this->alterColumn('restaurant', 'commercial_license_title',  $this->string()->defaultValue('Commercial License'));
      $this->alterColumn('restaurant', 'identification_title',  $this->string()->defaultValue("Owner civil id"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('restaurant', 'authorized_signature_file_purpose',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'commercial_license_file_purpose',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'identification_file_purpose',  $this->string()->notNull());


      $this->alterColumn('restaurant', 'authorized_signature_title',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'commercial_license_title',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'identification_title',  $this->string()->notNull());
    }

}
