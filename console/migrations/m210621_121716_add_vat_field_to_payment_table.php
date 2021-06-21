<?php

use yii\db\Migration;

/**
 * Class m210621_121716_add_vat_field_to_payment_table
 */
class m210621_121716_add_vat_field_to_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('payment', 'payment_vat', $this->decimal(10,3)->unsigned()->notNull()->defaultValue(0)->after('payment_gateway_fee'));
      $this->addColumn('payment_method', 'vat', $this->decimal(10,3)->unsigned()->notNull()->defaultValue(0)->after('payment_method_name_ar'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('payment', 'payment_vat');
      $this->dropColumn('payment_method', 'vat');
    }
}
