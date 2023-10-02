<?php

use yii\db\Migration;

/**
 * Class m231002_191648_logo
 */
class m231002_191648_logo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("restaurant", "logo_file_id", $this->string()->after("logo"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231002_191648_logo cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231002_191648_logo cannot be reverted.\n";

        return false;
    }
    */
}
