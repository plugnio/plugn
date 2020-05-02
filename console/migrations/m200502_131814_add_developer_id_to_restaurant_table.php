<?php

use yii\db\Migration;

/**
 * Class m200502_131814_add_developer_id_to_restaurant_table
 */
class m200502_131814_add_developer_id_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'developer_id', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant', 'developer_id');
    }
}
