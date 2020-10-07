<?php

use yii\db\Migration;

/**
 * Class m201007_122323_add_site_id_field_to_restaurant_table
 */
class m201007_122323_add_site_id_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201007_122323_add_site_id_field_to_restaurant_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201007_122323_add_site_id_field_to_restaurant_table cannot be reverted.\n";

        return false;
    }
    */
}
