<?php

use yii\db\Migration;

/**
 * Class m201207_115315_drop_vat_field
 */
class m201207_115315_drop_vat_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->dropColumn('restaurant', 'vat');
      $this->addColumn('business_location', 'business_location_tax', $this->float()->unsigned()->defaultValue(0)->notNull());
      $this->addColumn('delivery_zone', 'delivery_zone_tax', $this->float()->unsigned()->defaultValue(0));
      $this->addColumn('delivery_zone', 'time_unit', $this->char(3)->defaultValue('min')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->addColumn('restaurant', 'vat', $this->float()->unsigned()->defaultValue(0)->notNull());
      $this->dropColumn('business_location', 'business_location_tax');
      $this->dropColumn('delivery_zone', 'delivery_zone_tax');

    }
}
