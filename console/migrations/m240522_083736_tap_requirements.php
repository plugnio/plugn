<?php

use yii\db\Migration;

/**
 * Class m240522_083736_tap_requirements
 */
class m240522_083736_tap_requirements extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tap_requirements}}', [
            'tap_requirements_uuid' => $this->char(60),
            "country_id" => $this->integer(11),
            "requirement_en" => $this->text(),
            "requirement_ar" => $this->text(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'tap_requirements', 'tap_requirements_uuid');
        
        // creates index for column `country_id`
        $this->createIndex(
            'idx-tap_requirements-country_id', 'tap_requirements', 'country_id'
        );

        // add foreign key for table `country`
        $this->addForeignKey(
            'fk-tap_requirements-country_id', 'tap_requirements', 'country_id', 'country', 'country_id'
        );

        $this->addColumn("restaurant", "bank_account_name", $this->string());

        $this->addColumn("restaurant", "commercial_registration", $this->string());
        $this->addColumn("restaurant", "establishment_card", $this->string());
        $this->addColumn("restaurant", "work_permit", $this->string());
        $this->addColumn("restaurant", "residence_permit", $this->string());

        $this->addColumn("restaurant", "commercial_registration_file_id", $this->string());
        $this->addColumn("restaurant", "establishment_card_file_id", $this->string());
        $this->addColumn("restaurant", "work_permit_file_id", $this->string());
        $this->addColumn("restaurant", "residence_permit_file_id", $this->string());

        $this->addColumn("restaurant", "tax_document", $this->string());
        $this->addColumn("restaurant", "tax_document_file_id", $this->string());


        //KYC Documents (Know Your Customer)

        $this->createTable('{{%store_kyc}}', [
            'kyc_uuid' => $this->char(60),
            "restaurant_uuid"=> $this->char(60),
            "place_of_birth" => $this->string(),
            "marital_status" => $this->string(),
            "residence_region" => $this->string(),
            "source_of_income" => $this->string(),
            "occupation" => $this->string(),
            "expected_annual_sales" => $this->string(),
            "sales_channels" => $this->string(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'store_kyc', 'kyc_uuid');

        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-store_kyc-restaurant_uuid', 'store_kyc', 'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-store_kyc-restaurant_uuid', 'store_kyc', 'restaurant_uuid', 'restaurant', 'restaurant_uuid'
        );

        /*
           Place of birth (Name of country)
       Marital Status (Married, Single, Widowed)
       Residence region (please specify the country)
       Source of income (savings, investment, salary)
       Occupation (position and company)
       Expected annual sales
       Sales channels (Physical store, Online store, both)
       */

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240522_083736_tap_requirements cannot be reverted.\n";

        return false;
    }
    */
}
