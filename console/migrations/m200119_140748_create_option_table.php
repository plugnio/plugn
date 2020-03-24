<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%option}}`.
 */
class m200119_140748_create_option_table extends Migration
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

        
        $this->createTable('{{%option}}', [
            'option_id' => $this->primaryKey(),
            'item_uuid' => $this->string(300)->notNull(),
            'is_required' => $this->tinyInteger(1)->defaultValue(0),
            'max_qty' => $this->integer()->unsigned(), //max number of selection user can make for each optn
            'option_name' => $this->string(255),
            'option_name_ar' => $this->string(255),
        ], $tableOptions);

        
        // creates index for column `item_uuid`
        $this->createIndex(
            'idx-option-item_uuid',
            'option',
            'item_uuid'
        );

        // add foreign key for table `item`
        $this->addForeignKey(
            'fk-option-item_uuid',
            'option',
            'item_uuid',
            'item',
            'item_uuid',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-option-item_uuid', 'option');
        $this->dropIndex('idx-option-item_uuid', 'option');
        $this->dropTable('{{%option}}');
    }
}
