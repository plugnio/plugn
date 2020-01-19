<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%extra_option}}`.
 */
class m200119_141034_create_extra_option_table extends Migration
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

        $this->createTable('{{%extra_option}}', [
            'extra_option_id' => $this->primaryKey(),
            'option_id' => $this->integer(),
            'extra_option_name' => $this->string(255),
            'extra_option_name_ar' => $this->string(255),
            'price' => $this->float(),
        ], $tableOptions);

        
        // creates index for column `option_id`
        $this->createIndex(
            'idx-extra_option-option_id',
            'extra_option',
            'option_id'
        );

        // add foreign key for table `option`
        $this->addForeignKey(
            'fk-extra_option-option_id',
            'extra_option',
            'option_id',
            'option',
            'option_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->dropForeignKey('fk-extra_option-option_id', 'extra_option');
        $this->dropIndex('idx-extra_option-option_id', 'extra_option');
        $this->dropTable('{{%extra_option}}');
    }
}
