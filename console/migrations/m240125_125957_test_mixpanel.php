<?php

use yii\db\Migration;
use common\models\Setting;

/**
 * Class m240125_125957_test_mixpanel
 */
class m240125_125957_test_mixpanel extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
            \common\models\Setting::setConfig(null,'EventManager', 'Test-Mixpanel-Key', "98888fdeb1dd2290b1ff29f587e863b9");
            Setting::setConfig(null,'EventManager', 'Test-Segment-Key', "7oEpdGxjwBMlwBQYuXD7NpYWp4HzDJWh");
            Setting::setConfig(null,'EventManager', 'Test-Segment-Key-Wallet', "7oEpdGxjwBMlwBQYuXD7NpYWp4HzDJWh");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240125_125957_test_mixpanel cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240125_125957_test_mixpanel cannot be reverted.\n";

        return false;
    }
    */
}
