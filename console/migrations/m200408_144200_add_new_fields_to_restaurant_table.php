<?php

use yii\db\Migration;

/**
 * Class m200408_144200_add_new_fields_to_restaurant_table
 */
class m200408_144200_add_new_fields_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $this->addColumn('restaurant', 'business_type', $this->string()->notNull()); // If it is individual based (e.g. selling cookies on Instagram account or you are a freelancing designer, etc.) Or corporate based (e.g. a registered restaurant or a licensed clinic, etc.) 
        $this->addColumn('restaurant', 'vendor_sector', $this->string()->notNull()); //F&B
        $this->addColumn('restaurant', 'license_number', $this->string()->notNull());
        $this->addColumn('restaurant', 'not_for_profit', $this->tinyInteger(1)->defaultValue(0)->notNull()); 
        $this->addColumn('restaurant', 'document_type', $this->string()); //Authorized Signature
        $this->addColumn('restaurant', 'document_issuing_country', $this->string()->defaultValue('KW')->notNull()); //In which country is the business located. ISO3 code accepted
        $this->addColumn('restaurant', 'document_issuing_date', $this->string()->notNull());
        $this->addColumn('restaurant', 'document_expiry_date', $this->string()->notNull());
        $this->addColumn('restaurant', 'document_title', $this->string()->notNull()); 
        $this->addColumn('restaurant', 'document_file', $this->string()); 
        $this->addColumn('restaurant', 'document_file_id', $this->string()); 
        $this->addColumn('restaurant', 'document_file_purpose', $this->string()->notNull());
        $this->addColumn('restaurant', 'document_file_link_create', $this->tinyInteger(1)->defaultValue(0)->notNull());
        $this->addColumn('restaurant', 'iban', $this->string()->notNull());
        
        //Owner Info
        $this->addColumn('restaurant', 'owner_first_name', $this->string()->notNull());
        $this->addColumn('restaurant', 'owner_last_name', $this->string()->notNull());
        $this->addColumn('restaurant', 'owner_email', $this->string()->notNull());
        $this->addColumn('restaurant', 'owner_customer_number', $this->string()->notNull());
        $this->addColumn('restaurant', 'identification_type', $this->string()); //OWner's civil id
        $this->addColumn('restaurant', 'identification_issuing_country', $this->string()->defaultValue('KW')->notNull()); //In which country is the business located. ISO3 code accepted
        $this->addColumn('restaurant', 'identification_issuing_date', $this->string()->notNull());
        $this->addColumn('restaurant', 'identification_expiry_date', $this->string()->notNull());
        $this->addColumn('restaurant', 'identification_file', $this->string()); 
        $this->addColumn('restaurant', 'identification_file_id', $this->string()); 
        $this->addColumn('restaurant', 'identification_title', $this->string()->notNull()); 
        $this->addColumn('restaurant', 'identification_file_purpose', $this->string()->notNull());
        $this->addColumn('restaurant', 'identification_file_link_create', $this->tinyInteger(1)->defaultValue(0)->notNull());
   
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant', 'business_type');
        $this->dropColumn('restaurant', 'vendor_sector');
        $this->dropColumn('restaurant', 'license_number');
        $this->dropColumn('restaurant', 'not_for_profit');
        $this->dropColumn('restaurant', 'document_type');
        $this->dropColumn('restaurant', 'document_issuing_country');
        $this->dropColumn('restaurant', 'document_issuing_date');
        $this->dropColumn('restaurant', 'document_expiry_date');
        $this->dropColumn('restaurant', 'document_file');
        $this->dropColumn('restaurant', 'document_file_id');
        $this->dropColumn('restaurant', 'document_title');
        $this->dropColumn('restaurant', 'document_file_purpose');
        $this->dropColumn('restaurant', 'document_file_link_create');
        $this->dropColumn('restaurant', 'iban');
        $this->dropColumn('restaurant', 'owner_first_name');
        $this->dropColumn('restaurant', 'owner_last_name');
        $this->dropColumn('restaurant', 'owner_email');
        $this->dropColumn('restaurant', 'owner_customer_number');
        $this->dropColumn('restaurant', 'identification_type');
        $this->dropColumn('restaurant', 'identification_issuing_country');
        $this->dropColumn('restaurant', 'identification_issuing_date');
        $this->dropColumn('restaurant', 'identification_expiry_date');
        $this->dropColumn('restaurant', 'identification_file');
        $this->dropColumn('restaurant', 'identification_file_id');
        $this->dropColumn('restaurant', 'identification_title');
        $this->dropColumn('restaurant', 'identification_file_purpose');
        $this->dropColumn('restaurant', 'identification_file_link_create');
    }
}
