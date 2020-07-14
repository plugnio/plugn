<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%opening_hours}}`.
 */
class m200629_235611_create_opening_hours_table extends Migration
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


        $this->createTable('{{%opening_hour}}', [
          'opening_hour_id' => $this->primaryKey(),
          'restaurant_uuid' => $this->char(60)->notNull(),
          'day_of_week' => $this->smallInteger()->notNull(),
          'open_time' => $this->time()->notNull(),
          'close_time' => $this->time()->notNull(),
        ],$tableOptions);

        // creates index for column `restaurant_uuid`
        $this->createIndex(
                'idx-opening_hour-restaurant_uuid', 'opening_hour', 'restaurant_uuid'
        );

        // add foreign key for table `opening_hour`
        $this->addForeignKey(
                'fk-opening_hour-restaurant_uuid', 'opening_hour', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', 'CASCADE'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropForeignKey('fk-opening_hour-restaurant_uuid', 'opening_hour');
      $this->dropIndex('idx-opening_hour-restaurant_uuid', 'opening_hour');

        $this->dropTable('{{%opening_hour}}');
    }
}
