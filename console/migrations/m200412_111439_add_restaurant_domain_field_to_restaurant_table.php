<?php

use yii\db\Migration;

/**
 * Class m200412_111439_add_restaurant_domain_field_to_restaurant_table
 */
class m200412_111439_add_restaurant_domain_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'restaurant_domain', $this->string()->after('tagline_ar'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant', 'restaurant_domain');
    }
}
