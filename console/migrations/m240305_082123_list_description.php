<?php

use yii\db\Migration;

/**
 * Class m240305_082123_list_description
 */
class m240305_082123_list_description extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("restaurant_theme", "show_description_in_list", $this->boolean()->defaultValue(false));

        \agent\models\RestaurantTheme::updateAll(["show_description_in_list" => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240305_082123_list_description cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240305_082123_list_description cannot be reverted.\n";

        return false;
    }
    */
}
