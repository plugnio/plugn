<?php

use yii\db\Migration;

/**
 * Class m240103_081411_cod
 */
class m240103_081411_cod extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('restaurant', 'enable_cod_fee',
            $this->boolean()->after('platform_fee')->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240103_081411_cod cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240103_081411_cod cannot be reverted.\n";

        return false;
    }
    */
}
