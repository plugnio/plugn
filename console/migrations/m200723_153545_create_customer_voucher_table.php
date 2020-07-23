<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer_voucher}}`.
 */
class m200723_153545_create_customer_voucher_table extends Migration
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

        
        $this->createTable('{{%customer_voucher}}', [
            'customer_voucher_id' => $this->bigPrimaryKey(),
            'customer_id' => $this->bigInteger(),
            'voucher_id' => $this->bigInteger(),
        ],$tableOptions);
        
        
        
        // creates index for column `customer_id`
        $this->createIndex(
                'idx-customer_voucher-customer_id',
                'customer_voucher',
                'customer_id'
        );

        // add foreign key for table `customer_voucher`
        $this->addForeignKey(
                'fk-customer_voucher-customer_id',
                'customer_voucher',
                'customer_id', 
                'customer', 
                'customer_id',
                'CASCADE'
        );

        
        
        // creates index for column `voucher_id`
        $this->createIndex(
                'idx-customer_voucher-voucher_id',
                'customer_voucher',
                'voucher_id'
        );

        // add foreign key for table `customer_voucher`
        $this->addForeignKey(
                'fk-customer_voucher-voucher_id',
                'customer_voucher',
                'voucher_id', 
                'voucher', 
                'voucher_id',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-customer_voucher-voucher_id', 'customer_voucher');
        $this->dropIndex('idx-customer_voucher-voucher_id', 'customer_voucher');
        

        $this->dropForeignKey('fk-customer_voucher-customer_id', 'customer_voucher');
        $this->dropIndex('idx-customer_voucher-customer_id', 'customer_voucher');
        
        
        $this->dropTable('{{%customer_voucher}}');
    }
}
