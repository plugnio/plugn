<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%country_payment_method}}`.
 */
class m201112_232545_create_country_payment_method_table extends Migration
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


        $this->createTable('{{%country_payment_method}}', [
            'payment_method_id' => $this->integer()->notNull(),
            'country_id' => $this->integer()->notNull(),
        ],$tableOptions);

        $this->addPrimaryKey('PK', 'country_payment_method', ['payment_method_id','country_id']);


        // creates index for column `country_id`
        $this->createIndex(
                'idx-country_payment_method-country_id',
                'country_payment_method',
                'country_id'
        );

        // add foreign key for table `country_payment_method`
        $this->addForeignKey(
                'fk-country_payment_method-country_id',
                'country_payment_method',
                'country_id',
                'country',
                'country_id',
                'CASCADE'
        );


        // creates index for column `payment_method_id`
        $this->createIndex(
                'idx-payment-method-payment_method_id',
                'payment_method',
                'payment_method_id'
        );

        // add foreign key for table `payment_method`
        $this->addForeignKey(
                'fk-payment-method-payment_method_id',
                'country_payment_method',
                'payment_method_id',
                'payment_method',
                'payment_method_id',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%country_payment_method}}');
    }
}
