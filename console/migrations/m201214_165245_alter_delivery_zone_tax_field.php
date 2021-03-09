<?php

use yii\db\Migration;

/**
 * Class m201214_165245_alter_delivery_zone_tax_field
 */
class m201214_165245_alter_delivery_zone_tax_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('delivery_zone', 'delivery_zone_tax', $this->float()->unsigned());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('delivery_zone', 'delivery_zone_tax', $this->float()->unsigned()->defaultValue(0));

    }
}
