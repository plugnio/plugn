<?php

use yii\db\Migration;

/**
 * Class m200504_034009_drop_agent_id_field
 */
class m200504_034009_drop_agent_id_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-restaurant-agent_id', 'restaurant');
        $this->dropIndex('idx-restaurant-agent_id', 'restaurant');
        
        $this->dropColumn('restaurant', 'agent_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
       $this->addColumn('restaurant', 'agent_id',$this->bigInteger()->notNull());

  
        // creates index for column `agent_id`
        $this->createIndex(
                'idx-restaurant-agent_id',
                'restaurant',
                'agent_id'
        );

        // add foreign key for table `agent`
        $this->addForeignKey(
                'fk-restaurant-agent_id',
                'restaurant',
                'agent_id',
                'agent',
                'agent_id',
                'CASCADE'
        );

    }
}
