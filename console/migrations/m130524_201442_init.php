<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

      /**
       * Create Admin Table
       */
      $this->createTable('{{%admin}}', [
          'admin_id' => $this->primaryKey(),
          'admin_name' => $this->string()->notNull(),
          'admin_email' => $this->string()->notNull()->unique(),
          'admin_auth_key' => $this->string(32)->notNull(),
          'admin_password_hash' => $this->string()->notNull(),
          'admin_password_reset_token' => $this->string()->unique(),
          'admin_status' => $this->smallInteger()->notNull()->defaultValue(10),
          'admin_created_at' => $this->datetime()->notNull(),
          'admin_updated_at' => $this->datetime()->notNull(),
      ], $tableOptions);

      // Add Saoud as Base Admin
      $sql = 'INSERT INTO admin SET
          admin_id = 1,
          admin_name = "Saoud AlTurki",
          admin_email = "saoud@bawes.net",
          admin_auth_key = "Lu4vPW4Npfgce6WkXdt9OErpxXdB7GW4",
          admin_password_hash = "$2y$13$LdNaUZOdyyL5.TYl/tbfI.i9YVkhxFd/9LTzaaCFgn4lCeTNgL8le",
          admin_password_reset_token = NULL,
          admin_status = 10,
          admin_created_at = "2018-08-21 19:20:58",
          admin_updated_at = "2018-08-21 19:40:58"';

        Yii::$app->db->createCommand($sql)->execute();

          /**
         * Create Agent Table
         */
        $this->createTable('{{%agent}}', [
            'agent_id' => $this->bigPrimaryKey(),
            'agent_name' => $this->string()->notNull(),
            'agent_email' => $this->string()->notNull()->unique(),
            'agent_auth_key' => $this->string(32)->notNull(),
            'agent_password_hash' => $this->string()->notNull(),
            'agent_password_reset_token' => $this->string()->unique(),
            'agent_status' => $this->smallInteger()->notNull()->defaultValue(10),
            'agent_created_at' => $this->datetime()->notNull(),
            'agent_updated_at' => $this->datetime()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%admin}}');
        $this->dropTable('{{%agent}}');

    }
}
