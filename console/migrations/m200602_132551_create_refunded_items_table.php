<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%refunded_items}}`.
 */
class m200602_132551_create_refunded_items_table extends Migration
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

        $this->createTable('{{%refunded_item}}', [
            'refunded_item_id' => $this->primaryKey(),
            'refund_id' => $this->char(60)->notNull(),
            'order_item_id' =>  $this->bigInteger()->notNull(),
            'order_uuid' => $this->char(40)->notNull(),
            'item_uuid' => $this->string(300),
            'item_name' => $this->string(255)->notNull(),
            'item_price' => $this->float()->notNull(),
            
            'qty' => $this->integer()->notNull(),
           ], $tableOptions);

           $this->createIndex(
                   'idx-refund-order_item_id', 'refunded_item', 'order_item_id'
           );

           // add foreign key for `order_item_id` in table `refunded_item`
           $this->addForeignKey(
                   'fk-refunded_item-order_item_id', 'refunded_item', 'order_item_id', 'order_item', 'order_item_id', 'CASCADE'
           );

           $this->createIndex(
                   'idx-refund-refund_id', 'refunded_item', 'refund_id'
           );

           // add foreign key for `refund_id` in table `refunded_item`
           $this->addForeignKey(
                   'fk-refunded_item-refund_id', 'refunded_item', 'refund_id', 'refund', 'refund_id', 'CASCADE'
           );


           // creates index for column `order_uuid`
           $this->createIndex(
                   'idx-refunded_item-order_uuid', 'refunded_item', 'order_uuid'
           );

           // add foreign key for table `order`
           $this->addForeignKey(
                   'fk-refunded_item-order_uuid', 'refunded_item', 'order_uuid', 'order', 'order_uuid', 'CASCADE'
           );

           // creates index for column `item_uuid`
           $this->createIndex(
                   'idx-refunded_item-item_uuid', 'refunded_item', 'item_uuid'
           );

           // add foreign key for table `item`
           $this->addForeignKey(
                   'fk-refunded_item-item_uuid', 'refunded_item', 'item_uuid', 'item', 'item_uuid', 'SET NULL', 'SET NULL'
           );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
          $this->dropForeignKey('fk-refunded_item-order_item_id', 'refunded_item');
          $this->dropForeignKey('fk-refunded_item-refund_id', 'refunded_item');
          $this->dropForeignKey('fk-refunded_item-item_uuid', 'refunded_item');
          $this->dropForeignKey('fk-refunded_item-order_uuid', 'refunded_item');

          $this->dropIndex('idx-refund-order_item_id', 'refunded_item');
          $this->dropIndex('idx-refund-refund_id', 'refunded_item');
          $this->dropIndex('idx-refunded_item-item_uuid', 'refunded_item');
          $this->dropIndex('idx-refunded_item-order_uuid', 'refunded_item');



        $this->dropTable('{{%refunded_item}}');
    }
}
