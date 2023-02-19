<?php

use yii\db\Migration;

/**
 * Class m230122_044650_email_campaign
 */
class m230122_044650_email_campaign extends Migration
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

        //tables
        //---------------------
        //vendor_email_template
        //customer_email_template
        //vendor_campaign
        //customer_campaign
        //prebuilt_email_template

        $this->createTable('prebuilt_email_template', [
            "template_uuid" => $this->char(60)->notNull(), // used as reference id
            'subject' => $this->string(),
            'message'=> $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'prebuilt_email_template', 'template_uuid');

        $this->createTable('vendor_email_template', [
            "template_uuid" => $this->char(60)->notNull(), // used as reference id
            'subject' => $this->string(),
            'message'=> $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'vendor_email_template', 'template_uuid');

        $this->createTable('vendor_campaign', [
            "campaign_uuid"=> $this->char(60)->notNull(), // used as reference id
            "template_uuid" => $this->char(60)->notNull(), // used as reference id
            "progress" => $this->integer(11)->defaultValue(0),
            "status" => $this->tinyInteger(1)->defaultValue(0),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'vendor_campaign', 'campaign_uuid');

        $this->createIndex(
            'idx-vendor_campaign-template_uuid',
            'vendor_campaign',
            'template_uuid'
        );

        $this->addForeignKey(
            'fk-vendor_campaign-template_uuid',
            'vendor_campaign',
            'template_uuid',
            'vendor_email_template',
            'template_uuid'
        );

        //customer template/ campaign for vendor app

        $this->createTable('customer_email_template', [
            "template_uuid" => $this->char(60)->notNull(), // used as reference id
            "restaurant_uuid" => $this->char(60)->null(), // Which store made the payment?
            'subject' => $this->string(),
            'message'=> $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'customer_email_template', 'template_uuid');

        $this->createIndex(
            'idx-customer_email_template-restaurant_uuid',
            'customer_email_template',
            'restaurant_uuid'
        );

        $this->addForeignKey(
            'fk-customer_email_template-restaurant_uuid',
            'customer_email_template',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'SET NULL'
        );

        $this->createTable('customer_campaign', [
            "campaign_uuid"=> $this->char(60)->notNull(), // used as reference id
            "template_uuid" => $this->char(60)->notNull(), // used as reference id
            "restaurant_uuid" => $this->char(60)->null(),
            "progress" => $this->integer(11)->defaultValue(0),
            "status" => $this->tinyInteger(1)->defaultValue(0),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'customer_campaign', 'campaign_uuid');

        $this->createIndex(
            'idx-customer_campaign-restaurant_uuid',
            'customer_campaign',
            'restaurant_uuid'
        );

        $this->addForeignKey(
            'fk-customer_campaign-restaurant_uuid',
            'customer_campaign',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'SET NULL'
        );

        $this->createIndex(
            'idx-customer_campaign-template_uuid',
            'customer_campaign',
            'template_uuid'
        );

        $this->addForeignKey(
            'fk-customer_campaign-template_uuid',
            'customer_campaign',
            'template_uuid',
            'vendor_email_template',
            'template_uuid'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230122_044650_email_campaign cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230122_044650_email_campaign cannot be reverted.\n";

        return false;
    }
    */
}
