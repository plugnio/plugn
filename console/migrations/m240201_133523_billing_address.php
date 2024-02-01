<?php

use yii\db\Migration;

/**
 * Class m240201_133523_billing_address
 */
class m240201_133523_billing_address extends Migration
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

        $this->createTable('{{%restaurant_billing_address}}', [
            'rba_uuid' => $this->char(60),
            "country_id" => $this->integer(11),//  "KW"
            'restaurant_uuid' => $this->char(60),
            'recipient_name' => $this->string()->notNull(),
            'address_1' => $this->string()->notNull(),
            'address_2' => $this->string(),
            "po_box" => $this->string(),
            "district" => $this->string(),// "Salmiya",
            "city" => $this->string(),// "Hawally",
            "state" => $this->string(),//  "Kuwait",
            "zip_code" => $this->string(),// "30003",
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_billing_address', 'rba_uuid');

        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-restaurant_billing_address-restaurant_uuid', 'restaurant_billing_address', 'restaurant_uuid'
        );

        // add foreign key for table `customer`
        $this->addForeignKey(
            'fk-restaurant_billing_address-restaurant_uuid', 'restaurant_billing_address', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', 'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            'idx-restaurant_billing_address-country_id', 'restaurant_billing_address', 'country_id'
        );

        // add foreign key for table `customer`
        $this->addForeignKey(
            'fk-restaurant_billing_address-country_id', 'restaurant_billing_address', 'country_id', 'country', 'country_id'
        );

        $this->addColumn("restaurant", "owner_name_title", $this->string());
        $this->addColumn("restaurant", "owner_middle_name", $this->string());
        $this->addColumn("restaurant", "owner_nationality", $this->char(2));
        $this->addColumn("restaurant", "owner_date_of_birth", $this->date());

        $this->addColumn("restaurant", "tax_number", $this->string());
        $this->addColumn("restaurant", "swift_code", $this->string());
        $this->addColumn("restaurant", "account_number", $this->string());

        $this->addColumn("restaurant", "license_type", $this->string()->defaultValue("Commercial License"));

        //multiple shareholder + their identity documents + share documents
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240201_133523_billing_address cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240201_133523_billing_address cannot be reverted.\n";

        return false;
    }
    */
}
