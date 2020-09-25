<?php

use yii\db\Migration;

/**
 * Class m200925_170517_alert_live_public_key_field
 */
class m200925_170517_alert_live_public_key_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('restaurant', 'live_public_key');
        $this->dropColumn('restaurant', 'test_public_key');

        $this->addColumn('restaurant', 'live_public_key', $this->string()->defaultValue(null));
        $this->addColumn('restaurant', 'test_public_key', $this->string()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       return true;
    }

}
