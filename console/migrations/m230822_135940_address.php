<?php

use yii\db\Migration;

/**
 * Class m230822_135940_address
 */
class m230822_135940_address extends Migration
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

        $this->createTable('{{%customer_address}}', [
            'address_id' => $this->bigInteger(20), // used as reference id
            'customer_id' => $this->bigInteger(20)->notNull(), // used as reference id
            'area_id' => $this->integer(11),
            'city_id' => $this->integer(11),
            'country_id' => $this->integer(11),
            'unit_type' => $this->string(50),
            'house_number' => $this->integer(10)->unsigned(),
            'floor' => $this->integer(10)->unsigned(),
            'apartment' => $this->string(100),
            'building' => $this->string(100),
            'block' => $this->string(100),
            'street' => $this->string(100),
            'avenue' => $this->string(100),
            'office' => $this->string(100),
            'postalcode' => $this->string(50),
            'address_1' => $this->string(),
            'address_2' => $this->string(),
            'special_directions' => $this->text(),
            'delivery_instructions' => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'customer_address', 'address_id');

        $this->addForeignKey(
            'fk-customer_address-customer_id', 'customer_address',
            'customer_id', 'customer', 'customer_id', "CASCADE"
        );

        $this->addForeignKey(
            'fk-customer_address-area_id', 'customer_address',
            'area_id', 'area', 'area_id', "CASCADE"
        );

        $this->addForeignKey(
            'fk-customer_address-city_id', 'customer_address',
            'city_id', 'city', 'city_id', "CASCADE"
        );

        $this->addForeignKey(
            'fk-customer_address-country_id', 'customer_address',
            'country_id', 'country', 'country_id', "CASCADE"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%customer_address}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230822_135940_address cannot be reverted.\n";

        return false;
    }
    */
}
