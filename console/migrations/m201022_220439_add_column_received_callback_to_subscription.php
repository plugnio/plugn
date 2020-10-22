<?php

use yii\db\Migration;

/**
 * Class m201022_220439_add_column_received_callback_to_subscription
 */
class m201022_220439_add_column_received_callback_to_subscription extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
      $this->addColumn('subscription_payment', 'received_callback', $this->boolean()->notNull()->defaultValue(0));
      $this->addColumn('subscription_payment', 'response_message', $this->string());
      $this->addColumn('subscription_payment', 'payment_token', $this->string());

  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
      $this->dropColumn('subscription_payment', 'received_callback');
      $this->dropColumn('subscription_payment', 'response_message');
      $this->dropColumn('subscription_payment', 'payment_token');
  }

}
