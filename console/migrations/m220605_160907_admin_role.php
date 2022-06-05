<?php

use yii\db\Migration;

/**
 * Class m220605_160907_admin_role
 */
class m220605_160907_admin_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn ('admin', 'admin_role',
            $this->smallInteger (1)->defaultValue (1)->after('admin_email')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220605_160907_admin_role cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220605_160907_admin_role cannot be reverted.\n";

        return false;
    }
    */
}
