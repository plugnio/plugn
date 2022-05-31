<?php

use yii\db\Migration;

/**
 * Class m220530_110723_crm
 */
class m220530_110723_crm extends Migration
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
        
        /**
         * Create staff Table
         */
        $this->createTable('{{%staff}}', [
            'staff_id' => $this->bigPrimaryKey(),
            'staff_name' => $this->string()->notNull(),
            'staff_email' => $this->string()->notNull()->unique(),
            'staff_auth_key' => $this->string(32)->notNull(),
            'staff_password_hash' => $this->string()->notNull(),
            'staff_password_reset_token' => $this->string()->unique(),
            'staff_status' => $this->smallInteger()->notNull()->defaultValue(10),
            'staff_created_at' => $this->datetime()->notNull(),
            'staff_updated_at' => $this->datetime()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%staff_token}}', [
            'token_uuid' => $this->char(60),
            'staff_id' => $this->bigInteger()->notNull(),
            'token_value' => $this->string(255),
            'token_device' => $this->string(255),
            'token_device_id' => $this->string(255),
            'token_status' => $this->smallInteger(6),
            'token_last_used_datetime' => $this->dateTime(),
            'token_expiry_datetime' => $this->dateTime(),
            'token_created_datetime' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'staff_token', 'token_uuid');

        $this->createIndex(
            'idx-staff_token-staff_id', 'staff_token', 'staff_id'
        );

        $this->addForeignKey(
            'fk-staff_token-staff_id', 'staff_token', 'staff_id', 'staff', 'staff_id', 'CASCADE'
        );

        $this->createTable('{{%ticket}}', [
            'ticket_uuid' => $this->char(60),
            'restaurant_uuid' => $this->char(60),
            'agent_id' => $this->bigInteger(),
            'staff_id' => $this->bigInteger(),
            'ticket_detail' => $this->text(),
            'ticket_status' => $this->smallInteger(1)->defaultValue(0),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'ticket', 'ticket_uuid');

        $this->createIndex(
            'idx-ticket-restaurant_uuid', 'ticket', 'restaurant_uuid'
        );

        $this->addForeignKey(
            'fk-ticket-restaurant_uuid', 'ticket', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', 'CASCADE'
        );

        $this->createIndex(
            'idx-ticket-agent_id', 'ticket', 'agent_id'
        );

        $this->addForeignKey(
            'fk-ticket-agent_id', 'ticket', 'agent_id', 'agent', 'agent_id'
        );

        $this->createIndex(
            'idx-ticket-staff_id', 'ticket', 'staff_id'
        );

        $this->addForeignKey(
            'fk-ticket-staff_id', 'ticket', 'staff_id', 'staff', 'staff_id'
        );

        $this->createTable('{{%ticket_attachment}}', [
            'ticket_attachment_uuid' => $this->char(60),
            'ticket_uuid' => $this->char(60),
            'attachment_uuid' => $this->char(60),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'ticket_attachment', 'ticket_attachment_uuid');

        $this->createIndex(
            'idx-ticket_attachment-attachment_uuid', 'ticket_attachment', 'attachment_uuid'
        );

        $this->createTable('{{%attachment}}', [
            'attachment_uuid' => $this->char(60),
            'file_path' => $this->string(250)
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'attachment', 'attachment_uuid');


        $this->addForeignKey(
            'fk-ticket_attachment-attachment_uuid', 'ticket_attachment', 'attachment_uuid', 'attachment', 'attachment_uuid'
        );

        $this->createIndex(
            'idx-ticket_attachment-agent_id', 'ticket_attachment', 'ticket_uuid'
        );

        $this->addForeignKey(
            'fk-ticket_attachment-agent_id', 'ticket_attachment', 'ticket_uuid', 'ticket', 'ticket_uuid'
        );

        $this->createTable('{{%ticket_comment}}', [
            'ticket_comment_uuid'=> $this->char(60),
            'ticket_uuid' => $this->char(60),
            'agent_id' => $this->bigInteger(),
            'staff_id' => $this->bigInteger(),
            'ticket_comment_detail' => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'ticket_comment', 'ticket_comment_uuid');

        $this->createIndex(
            'idx-ticket_comment-ticket_uuid', 'ticket_comment', 'ticket_uuid'
        );

        $this->addForeignKey(
            'fk-ticket_comment-ticket_uuid', 'ticket_comment', 'ticket_uuid', 'ticket', 'ticket_uuid'
        );

        $this->createIndex(
            'idx-ticket_comment-agent_id', 'ticket_comment', 'agent_id'
        );

        $this->addForeignKey(
            'fk-ticket_comment-agent_id', 'ticket_comment', 'agent_id', 'agent', 'agent_id'
        );

        $this->createIndex(
            'idx-ticket_comment-staff_id', 'ticket_comment', 'staff_id'
        );

        $this->addForeignKey(
            'fk-ticket_comment-staff_id', 'ticket_comment', 'staff_id', 'staff', 'staff_id'
        );

        $this->createTable('{{%ticket_comment_attachment}}', [
            'ticket_comment_attachment_uuid' => $this->char(60),
            'ticket_comment_uuid' => $this->char(60),
            'attachment_uuid' => $this->char(60),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'ticket_comment_attachment', 'ticket_comment_uuid');

        $this->createIndex(
            'idx-ticket_comment_attachment-ticket_comment_uuid', 'ticket_comment_attachment', 'ticket_comment_uuid'
        );

        $this->addForeignKey(
            'fk-ticket_comment_attachment-ticket_comment_uuid', 'ticket_comment_attachment', 'ticket_comment_uuid', 'ticket_comment', 'ticket_comment_uuid'
        );

        $this->createIndex(
            'idx-ticket_comment_attachment-attachment_uuid', 'ticket_comment_attachment', 'attachment_uuid'
        );

        $this->addForeignKey(
            'fk-ticket_comment_attachment-attachment_uuid', 'ticket_comment_attachment', 'attachment_uuid', 'attachment', 'attachment_uuid'
        );

        /*
        Staff
        StaffToken
        Attachment
        Ticket
        TicketComment
        TicketAttachment
        TicketCommentAttachment
         */

        //add staff data

        //Yii::$app->db->createCommand('SET foreign_key_checks = 1')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220530_110723_crm cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220530_110723_crm cannot be reverted.\n";

        return false;
    }
    */
}
