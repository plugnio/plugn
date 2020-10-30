<?php

use yii\db\Migration;

/**
 * Class m201028_162639_add_payment_method_id_to_subscription_table
 */
class m201028_162639_add_payment_method_id_to_subscription_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

      $this->addColumn('subscription', 'payment_method_id', $this->integer()->after('subscription_uuid'));

      // creates index for column `subscription_uuid`in table `subscription`
      $this->createIndex(
          'idx-subscription-subscription_uuid',
          'subscription',
          'payment_method_id'
      );

      // add foreign key for `payment_method_id` in table `subscription`
      $this->addForeignKey(
          'fk-subscription-payment_method_id',
          'subscription',
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
        return true;
    }

}
