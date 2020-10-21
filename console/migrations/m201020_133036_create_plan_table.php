<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plan}}`.
 */
class m201020_133036_create_plan_table extends Migration
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

        $this->createTable('{{%plan}}', [
            'plan_id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'price' => $this->float()->unsigned(),
            'valid_for' => $this->integer()->unsigned(),
            'platform_fee' => $this->float()->unsigned(),
           ], $tableOptions);

           $sql = 'INSERT INTO plan SET
               plan_id = 1,
               name = "Free Plan",
               price = 0,
               valid_for = 0,
               platform_fee = 0.05
               ';

           Yii::$app->db->createCommand($sql)->execute();

           $sql = 'INSERT INTO plan SET
               plan_id = 2,
               name = "Premium Plan",
               price = 200,
               valid_for = 12,
               platform_fee = 0
               ';
           Yii::$app->db->createCommand($sql)->execute();


        $this->createTable('{{%subscription}}', [
            'subscription_uuid' => $this->char(60)->unique(),
            'restaurant_uuid' => $this->char(60),
            'plan_id' => $this->integer()->notNull(),
            'subscription_status' => $this->tinyInteger(1)->unsigned()->defaultValue(1),
            'notified_email' => $this->tinyInteger(1)->unsigned()->defaultValue(0)->notNull(),
            'subscription_start_at' => $this->datetime()->notNull(),
            'subscription_end_at' => $this->datetime(),
           ], $tableOptions);

           $this->addPrimaryKey('PK', 'subscription', 'subscription_uuid');

      // creates index for column `restaurant_uuid`
       $this->createIndex(
               'idx-subscription-restaurant_uuid', 'subscription', 'restaurant_uuid'
       );

       // add foreign key for table `subscription`
       $this->addForeignKey(
               'fk-subscription-restaurant_uuid', 'subscription', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', 'CASCADE'
       );



      // creates index for column `plan_id`
       $this->createIndex(
               'idx-subscription-plan_id', 'subscription', 'plan_id'
       );

       // add foreign key for table `subscription`
       $this->addForeignKey(
               'fk-subscription-plan_id', 'subscription', 'plan_id', 'plan', 'plan_id', 'CASCADE'
       );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

      // drops foreign key for table `subscription`
      $this->dropForeignKey(
          'fk-subscription-restaurant_uuid',
          'subscription'
      );

      // drops index for column `restaurant_uuid`
      $this->dropIndex(
          'idx-subscription-restaurant_uuid',
          'subscription'
      );


      // drops foreign key for table `subscription`
      $this->dropForeignKey(
          'fk-subscription-plan_id',
          'subscription'
      );

      // drops index for column `plan_id`
      $this->dropIndex(
          'idx-subscription-plan_id',
          'subscription'
      );


        $this->dropTable('{{%subscription}}');
        $this->dropTable('{{%plan}}');
    }
}
