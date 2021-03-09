<?php

use yii\db\Migration;

/**
 * Class m210213_122412_alert_tax_field
 */
class m210213_122412_alert_tax_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('order', 'tax', $this->decimal(10,3)->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('order', 'tax', $this->float()->defaultValue(0)->unsigned());
    }
}
