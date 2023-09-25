<?php

use yii\db\Migration;

/**
 * Class m230925_094703_campaign
 */
class m230925_094703_campaign extends Migration
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

        $this->createTable('{{%campaign_filter}}', [
            'cf_uuid' => $this->char(60), // used as reference id
            'campaign_uuid' => $this->char(60)->notNull(), // used as reference id
            'param' => $this->string(50),
            'value' => $this->string(100),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'campaign_filter', 'cf_uuid');

        $this->createIndex('ind-campaign_filter-campaign_uuid', 'campaign_filter', 'campaign_uuid');

        $this->addForeignKey(
            'fk-campaign_filter-customer_id', 'campaign_filter',
            'campaign_uuid', 'vendor_campaign', 'campaign_uuid', "CASCADE"
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-campaign_filter-customer_id', 'campaign_filter'
        );

        $this->dropIndex('ind-campaign_filter-utm_uuid', 'campaign');

        $this->dropTable( '{{%campaign_filter}}');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230925_094703_campaign cannot be reverted.\n";

        return false;
    }
    */
}
