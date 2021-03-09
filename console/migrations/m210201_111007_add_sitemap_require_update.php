<?php

use yii\db\Migration;

/**
 * Class m210201_111007_add_sitemap_require_update
 */
class m210201_111007_add_sitemap_require_update extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'sitemap_require_update', $this->smallInteger()->defaultValue(0)->unsigned());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'sitemap_require_update');
    }

}
