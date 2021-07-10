<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%partner}}`.
 */
class m210710_075754_create_partner_table extends Migration
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


      Yii::$app->db->createCommand('SET foreign_key_checks = 0')->execute();


        $this->createTable('{{%partner}}', [
          'partner_uuid' => $this->char(60)->notNull(),
          'username' => $this->string()->notNull()->unique(),
          'partner_auth_key' => $this->string(32)->notNull(),
          'partner_password_hash' => $this->string()->notNull(),
          'partner_password_reset_token' => $this->string()->unique(),
          'partner_email' => $this->string()->notNull()->unique(),
          'partner_status' => $this->smallInteger()->notNull()->defaultValue(10),
          'referral_code' => $this->string(6),
          'commission' => $this->decimal(10,3)->unsigned()->defaultValue(0.2),
          'partner_created_at' => $this->dateTime(),
          'partner_updated_at' => $this->dateTime(),
      ], $tableOptions);

      $this->addPrimaryKey('PK', 'partner', ['partner_uuid','referral_code']);

      // creates index for column `partner_uuid`
      $this->createIndex(
              'idx-partner-partner_uuid', 'partner', 'partner_uuid'
      );

      // creates index for column `referral_code`
      $this->createIndex(
              'idx-partner-referral_code', 'partner', 'referral_code'
      );



      $this->createTable('{{%partner_token}}', [
          'token_uuid' => $this->char(60),
          'partner_uuid' => $this->char(60)->notNull(),
          'token_value' => $this->string(255),
          'token_device' => $this->string(255),
          'token_device_id' => $this->string(255),
          'token_status' => $this->smallInteger(6),
          'token_last_used_datetime' => $this->dateTime(),
          'token_expiry_datetime' => $this->dateTime(),
          'token_created_datetime' => $this->dateTime(),
              ], $tableOptions);

      $this->addPrimaryKey('PK', 'partner_token', 'token_uuid');

      // creates index for column `partner_uuid`
      $this->createIndex(
              'idx-partner_token-partner_uuid', 'partner_token', 'partner_uuid'
      );

      // add foreign key for table `partner_token`
      $this->addForeignKey(
              'fk-partner_token-partner_uuid', 'partner_token', 'partner_uuid', 'partner', 'partner_uuid', 'CASCADE'
      );





      $this->createTable('{{%partner_payout}}', [
          'partner_payout_uuid' => $this->char(60),
          'partner_uuid' => $this->char(60)->notNull(),
          'payment_uuid' => $this->char(36)->notNull(),
          'amount' => $this->decimal(10,3)->unsigned()->defaultValue(0),
          'partner_status' => $this->smallInteger()->notNull()->defaultValue(10),
          'created_at' => $this->integer()->notNull(),
          'updated_at' => $this->integer()->notNull(),
      ], $tableOptions);

      $this->addPrimaryKey('PK', 'partner_payout', 'partner_payout_uuid');

      // creates index for column `partner_uuid`
      $this->createIndex(
              'idx-partner_payout-partner_uuid', 'partner_payout', 'partner_uuid'
      );

      // add foreign key for table `partner_payout`
      $this->addForeignKey(
              'fk-partner_payout-partner_uuid', 'partner_payout', 'partner_uuid', 'partner', 'partner_uuid', 'CASCADE'
      );

      // creates index for column `payment_uuid`
      $this->createIndex(
              'idx-partner_payout-payment_uuid', 'partner_payout', 'payment_uuid'
      );

      // add foreign key for table `partner_payout`
      $this->addForeignKey(
              'fk-partner_payout-payment_uuid', 'partner_payout', 'payment_uuid', 'payment', 'payment_uuid', 'CASCADE'
      );






      $this->addColumn('restaurant', 'referral_code',  $this->string(6));

      $this->createIndex(
              'idx-restaurant-referral_code', 'restaurant', 'referral_code'
      );

      $this->addForeignKey(
              'fk-restaurant-referral_code', 'restaurant', 'referral_code', 'partner', 'referral_code', 'SET NULL', 'SET NULL'
      );



      $this->addColumn('payment', 'partner_fee',  $this->decimal(10,3)->unsigned()->defaultValue(0));
      $this->addColumn('payment', 'paid_partner', $this->smallInteger()->defaultValue(0));



      Yii::$app->db->createCommand('SET foreign_key_checks = 1')->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

      $this->dropColumn('payment', 'partner_fee');
      $this->dropColumn('payment', 'paid_partner');

      //Drop partner_uuid field
      $this->dropForeignKey('fk-restaurant-referral_code', 'restaurant');
      $this->dropIndex('idx-restaurant-referral_code', 'restaurant');
      $this->dropColumn('restaurant', 'referral_code');

      //Drop partner_payout table
      $this->dropForeignKey('fk-partner_payout-partner_uuid', 'partner_payout');
      $this->dropIndex('idx-partner_payout-partner_uuid', 'partner_payout');
      $this->dropForeignKey('fk-partner_payout-payment_uuid', 'partner_payout');
      $this->dropIndex('idx-partner_payout-payment_uuid', 'partner_payout');
      $this->dropPrimaryKey('PK', 'partner_payout');
      $this->dropTable('{{%partner_payout}}');



      //Drop partner_token table
      $this->dropForeignKey('fk-partner_token-partner_uuid', 'partner_payout');
      $this->dropIndex('idx-partner_token-partner_uuid', 'partner_payout');
      $this->dropPrimaryKey('PK', 'partner_token');
      $this->dropTable('{{%partner_token}}');


        $this->dropPrimaryKey('PK', 'partner');
        $this->dropTable('{{%partner}}');
    }
}
