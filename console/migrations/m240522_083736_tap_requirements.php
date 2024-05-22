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


        KYC Documents (Know Your Customer)

        place_of_birth: string;
        marital_status: string;
        residence_region: string;
        source_of_income: string;
        occupation: string;
        expected_annual_sales: string;
        sales_channels: string;

    Place of birth (Name of country)
Marital Status (Married, Single, Widowed)
Residence region (please specify the country)
Source of income (savings, investment, salary)
Occupation (position and company)
Expected annual sales
Sales channels (Physical store, Online store, both)


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240522_083736_tap_requirements cannot be reverted.\n";

        return false;
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
