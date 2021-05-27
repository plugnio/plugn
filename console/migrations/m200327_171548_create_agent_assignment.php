<?php

use yii\db\Migration;

/**
 * Class m200327_171548_create_agent_assignment
 */
class m200327_171548_create_agent_assignment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('agent_assignment', [
            'assignment_id' => $this->primaryKey()->unsigned(),
            'restaurant_uuid' => $this->char(60)->notNull(),
            'agent_id' => $this->bigInteger(),
            'assignment_agent_email' => $this->string()->notNull(),
            'assignment_created_at' => $this->datetime()->notNull(),
            'assignment_updated_at' => $this->datetime()->notNull(),
        ],$tableOptions);

        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-agent_assignment-restaurant_uuid',
            'agent_assignment',
            'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-agent_assignment-restaurant_uuid',
            'agent_assignment',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'CASCADE'
        );

        // creates index for column `agent_id`
        $this->createIndex(
            'idx-agent_assignment-agent_id',
            'agent_assignment',
            'agent_id'
        );

        // add foreign key for table `agent`
        $this->addForeignKey(
            'fk-agent_assignment-agent_id',
            'agent_assignment',
            'agent_id',
            'agent',
            'agent_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `restaurant`
        $this->dropForeignKey(
            'fk-agent_assignment-restaurant_uuid',
            'agent_assignment'
        );

        // drops index for column `restaurant_uuid`
        $this->dropIndex(
            'idx-agent_assignment-restaurant_uuid',
            'agent_assignment'
        );

        // drops foreign key for table `agent`
        $this->dropForeignKey(
            'fk-agent_assignment-agent_id',
            'agent_assignment'
        );

        // drops index for column `agent_id`
        $this->dropIndex(
            'idx-agent_assignment-agent_id',
            'agent_assignment'
        );

        $this->dropTable('agent_assignment');
    }

}
