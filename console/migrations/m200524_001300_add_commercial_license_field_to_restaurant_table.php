<?php

use yii\db\Migration;

/**
 * Class m200524_001300_add_commercial_license_field_to_restaurant_table
 */
class m200524_001300_add_commercial_license_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('restaurant', 'license_number', $this->string());
        
        $this->addColumn('restaurant', 'commercial_license_issuing_country', $this->string()->defaultValue('KW')->notNull()); //In which country is the business located. ISO3 code accepted
        $this->addColumn('restaurant', 'commercial_license_issuing_date', $this->string()->notNull());
        $this->addColumn('restaurant', 'commercial_license_expiry_date', $this->string()->notNull());
        $this->addColumn('restaurant', 'commercial_license_title', $this->string()->notNull());
        $this->addColumn('restaurant', 'commercial_license_file', $this->string());
        $this->addColumn('restaurant', 'commercial_license_file_id', $this->string());
        $this->addColumn('restaurant', 'commercial_license_file_purpose', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant', 'commercial_license_issuing_country'); 
        $this->dropColumn('restaurant', 'commercial_license_issuing_date');
        $this->dropColumn('restaurant', 'commercial_license_expiry_date');
        $this->dropColumn('restaurant', 'commercial_license_title');
        $this->dropColumn('restaurant', 'commercial_license_file');
        $this->dropColumn('restaurant', 'commercial_license_file_id');
        $this->dropColumn('restaurant', 'commercial_license_file_purpose');
    }

}
