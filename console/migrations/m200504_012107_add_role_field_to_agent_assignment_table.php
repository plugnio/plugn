<?php

use yii\db\Migration;

/**
 * Class m200504_012107_add_role_field_to_agent_assignment_table
 */
class m200504_012107_add_role_field_to_agent_assignment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agent_assignment', 'role', $this->smallInteger()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('agent_assignment', 'role');
    }
}
