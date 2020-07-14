<?php

use yii\db\Migration;

/**
 * Class m200714_112526_alert_opening_hrs_table
 */
class m200714_112526_alert_opening_hrs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('opening_hour', 'open_time', 'open_at');
        $this->renameColumn('opening_hour', 'close_time', 'close_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('opening_hour', 'open_at', 'open_time');
        $this->renameColumn('opening_hour', 'close_at', 'close_time');
    }

}
