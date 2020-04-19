<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%refund}}`.
 */
class m200419_101304_create_refund_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%refund}}', [
            'refund_id' => $this->char(60)->notNull(),
            'restaurant_uuid' => $this->char(60)->notNull(),
            'order_uuid' => $this->char(40)->notNull(),
            'refund_amount' => $this->float()->notNull(),
            'reason' => $this->string()->notNull(),
            'refund_status' => $this->string(),
           ], $tableOptions);

        $this->addPrimaryKey('PK', 'refund', 'refund_id');
        
        $this->createIndex(
                'idx-refund-restaurant_uuid', 'refund', 'restaurant_uuid'
        );

        // add foreign key for `restaurant_uuid` in table `payment`
        $this->addForeignKey(
                'fk-refund-restaurant_uuid', 'refund', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', 'CASCADE'
        );


        $this->createIndex(
                'idx-refund-order_uuid', 'refund', 'order_uuid'
        );

        // add foreign key for `order_uuid` in table `payment`
        $this->addForeignKey(
                'fk-refund-order_uuid', 'refund', 'order_uuid', 'order', 'order_uuid', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        
        $this->dropPrimaryKey('PK', 'refund');
        $this->dropForeignKey('fk-refund-restaurant_uuid', 'refund');
        $this->dropForeignKey('fk-refund-order_uuid', 'refund');

        $this->dropIndex('idx-refund-order_uuid', 'refund');
        $this->dropIndex('idx-refund-restaurant_uuid', 'refund');

        $this->dropTable('{{%refund}}');
    }

}
