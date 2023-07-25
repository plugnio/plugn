<?php

use yii\db\Migration;

/**
 * Class m230725_151906_google_tag_manager
 */
class m230725_151906_google_tag_manager extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'google_tag_manager_id',  $this->string()->after('google_tag_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230725_151906_google_tag_manager cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230725_151906_google_tag_manager cannot be reverted.\n";

        return false;
    }
    */
}
