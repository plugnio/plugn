<?php

use yii\db\Migration;

/**
 * Class m200418_225006_add_restaurant_uuid_to_payment_table
 */
class m200418_225006_add_restaurant_uuid_to_payment_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('payment', 'restaurant_uuid', $this->char(60)->after('payment_uuid'));

        // creates index for column `restaurant_uuid`
        $this->createIndex(
                'idx-payment-restaurant_uuid', 'payment', 'restaurant_uuid'
        );

        // add foreign key for table `payment`
        $this->addForeignKey(
                'fk-payment-restaurant_uuid', 'payment', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropIndex('idx-payment-restaurant_uuid', 'payment');
        $this->dropForeignKey('fk-payment-restaurant_uuid', 'payment');
        
        $this->dropColumn('payment', 'restaurant_uuid');
        
    }

}
