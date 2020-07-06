<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%working_hours}}`.
 */
class m200629_224650_drop_working_hours_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

      $this->dropForeignKey('fk-working_hours-working_day_id', 'working_hours');
      $this->dropIndex('idx-working_hours-working_day_id', 'working_hours');

      $this->dropForeignKey('fk-working_hours-restaurant_uuid', 'working_hours');
      $this->dropIndex('idx-working_hours-restaurant_uuid', 'working_hours');
      
        $this->dropTable('{{working_hours}}');
        $this->dropTable('{{working_day}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $tableOptions = null;
      if ($this->db->driverName === 'mysql') {
          // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
          $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
      }

      $this->createTable('{{%working_day}}', [
          'working_day_id' => $this->bigPrimaryKey(),
          'name' => $this->string(),
          'name_ar' => $this->string(),
      ],$tableOptions);



      $this->createTable('{{%working_hours}}', [
          'working_day_id' => $this->bigInteger(),
          'restaurant_uuid' => $this->char(60),
          'operating_from' => $this->time(),
          'operating_to' => $this->time(),
      ],$tableOptions);

      $this->addPrimaryKey('PK', 'working_hours', ['working_day_id', 'restaurant_uuid']);


      // creates index for column `working_day_id`
      $this->createIndex(
              'idx-working_hours-working_day_id',
              'working_hours',
              'working_day_id'
      );

      // add foreign key for table `working_hours`
      $this->addForeignKey(
              'fk-working_hours-working_day_id',
              'working_hours',
              'working_day_id',
              'working_day',
              'working_day_id',
              'CASCADE'
      );

      // creates index for column `restaurant_uuid`
      $this->createIndex(
              'idx-working_hours-restaurant_uuid',
              'working_hours',
              'restaurant_uuid'
      );

      // add foreign key for table `working_hours`
      $this->addForeignKey(
              'fk-working_hours-restaurant_uuid',
              'working_hours',
              'restaurant_uuid',
              'restaurant',
              'restaurant_uuid',
              'CASCADE'
      );
    }
}
