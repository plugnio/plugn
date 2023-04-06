<?php

use yii\db\Migration;
use common\models\Setting;

/**
 * Class m230315_105326_event
 */
class m230315_105326_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Setting::setConfig(null, 'EventManager', 'Mixpanel-Status', "enabled");

        Setting::setConfig(null,'EventManager', 'Segment-Status', "enabled");

        if(YII_ENV == 'prod') {
            Setting::setConfig(null,'EventManager', 'Mixpanel-Key', "045c95d677c4fe04b3b406f71050a659");
            Setting::setConfig(null,'EventManager', 'Segment-Key', "2b6WC3d2RevgNFJr9DGumGH5lDRhFOv5");
            Setting::setConfig(null,'EventManager', 'Segment-Key-Wallet', "j18MpMF6fvZzmc6bvF0VjlTajAlKwai2");
        } else {
            Setting::setConfig(null,'EventManager', 'Mixpanel-Key', "ac62dbe81767f8871f754c7bdf6669d6");
            Setting::setConfig(null,'EventManager', 'Segment-Key', "7oEpdGxjwBMlwBQYuXD7NpYWp4HzDJWh");
            Setting::setConfig(null,'EventManager', 'Segment-Key-Wallet', "7oEpdGxjwBMlwBQYuXD7NpYWp4HzDJWh");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230315_105326_event cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230315_105326_event cannot be reverted.\n";

        return false;
    }
    */
}
