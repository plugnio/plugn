<?php

use yii\db\Migration;

/**
 * Class m210228_164816_alert_office_field
 */
class m210228_164816_alert_office_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('order', 'floor', $this->string()->after('country_name_ar'));
      $this->alterColumn('order', 'office', $this->string()->after('building'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('order', 'floor', $this->integer()->after('country_name_ar'));
      $this->alterColumn('order', 'office', $this->integer()->after('building'));
    }

}
