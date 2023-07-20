<?php

use yii\db\Migration;

/**
 * Class m230720_073313_tiktok
 */
class m230720_073313_tiktok extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'google_tag_id',  $this->string()->after('facebook_pixil_id'));
        $this->addColumn('restaurant', 'tiktok_pixel_id',  $this->string()->after('google_tag_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230720_073313_tiktok cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230720_073313_tiktok cannot be reverted.\n";

        return false;
    }
    */
}
