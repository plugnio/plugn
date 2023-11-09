<?php

use yii\db\Migration;

/**
 * Class m231109_081603_business_data
 */
class m231109_081603_business_data extends Migration
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

        //table: business_category

        $this->createTable('{{%business_category}}', [
            'business_category_uuid' => $this->char(60), // used as reference id
            'business_category_en' => $this->string(100)->notNull(),
            'business_category_ar' => $this->string(100),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'business_category', 'business_category_uuid');

        Yii::$app->db->createCommand()->batchInsert('business_category', ['business_category_uuid', 'business_category_en', 'created_at'], [
            ['bc_' . $this->getUUID(), 'Beauty and Personal Care', date('Y-m-d')],
            ['bc_' . $this->getUUID(), 'Charities, Education and Membership', date('Y-m-d')],
            ['bc_' . $this->getUUID(), 'Fitness', date('Y-m-d')],
            ['bc_' . $this->getUUID(), 'Food and Drink', date('Y-m-d')],
            ['bc_' . $this->getUUID(), 'Health Care', date('Y-m-d')],
            ['bc_' . $this->getUUID(), 'Home and Repair', date('Y-m-d')],
            ['bc_' . $this->getUUID(), 'Leisure and Entertainment', date('Y-m-d')],
            ['bc_' . $this->getUUID(), 'Pet Care', date('Y-m-d')],
            ['bc_' . $this->getUUID(), 'Professional Services', date('Y-m-d')],
            ['bc_' . $this->getUUID(), 'Retail', date('Y-m-d')],
            ['bc_' . $this->getUUID(), 'Transportation', date('Y-m-d')],
            ['bc_' . $this->getUUID(), 'Casual Use', date('Y-m-d')],
        ])->execute();

        //table: Business_item_type

        $this->createTable('{{%business_item_type}}', [
            'business_item_type_uuid' => $this->char(60), // used as reference id
            'business_item_type_en' => $this->string(100)->notNull(),
            'business_item_type_ar' => $this->string(100),
            'business_item_type_subtitle_en' => $this->text(),
            'business_item_type_subtitle_ar' => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'business_item_type', 'business_item_type_uuid');

        Yii::$app->db->createCommand()->batchInsert('business_item_type', ['business_item_type_uuid', 'business_item_type_en', 'business_item_type_subtitle_en', 'created_at'], [
            ['bit_' . $this->getUUID(), 'Physical items', 'Clothing, jewelry, packaged food', date('Y-m-d')],
            ['bit_' . $this->getUUID(), 'Menu items', 'Pizza, sushi, fresh meals & drink', date('Y-m-d')],
            ['bit_' . $this->getUUID(), 'Donations', 'Fundraising, gifts, charity', date('Y-m-d')],
            ['bit_' . $this->getUUID(), 'Tickets', 'Conferences, sports, classes', date('Y-m-d')],
            ['bit_' . $this->getUUID(), 'Memberships', 'Clubs, museums, subscriptions', date('Y-m-d')],
            ['bit_' . $this->getUUID(), 'Services', 'Haircuts, repairs, consultations', date('Y-m-d')],
            ['bit_' . $this->getUUID(), 'Other', 'None of above', date('Y-m-d')],
        ])->execute();

        //table: business_type

        $this->createTable('{{%business_type}}', [
            'business_type_uuid' => $this->char(60), // used as reference id
            'parent_business_type_uuid' => $this->char(60),
            'business_type_en' => $this->string(100)->notNull(),
            'business_type_ar' => $this->string(100),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'business_type', 'business_type_uuid');

        //parent_business_type_uuid

        $this->createIndex('ind-business_type-parent_business_type_uuid', 'business_type', 'parent_business_type_uuid');

        $this->addForeignKey(
            'fk-business_type-parent_business_type_uuid', 'business_type',
            'parent_business_type_uuid', 'business_type', 'business_type_uuid', "CASCADE"
        );

        //table: merchant_type

        $this->createTable('{{%merchant_type}}', [
            'merchant_type_uuid' => $this->char(60), // used as reference id
            'merchant_type_en' => $this->string(100)->notNull(),
            'merchant_type_ar' => $this->string(100),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'merchant_type', 'merchant_type_uuid');

        Yii::$app->db->createCommand()->batchInsert('merchant_type', ['merchant_type_uuid', 'merchant_type_en', 'created_at'], [
            ['mt_' . $this->getUUID(), 'Individual / Sole Proprietorship', date('Y-m-d')],
            ['mt_' . $this->getUUID(), 'Limited Liability Company (LLC)', date('Y-m-d')],
            ['mt_' . $this->getUUID(), 'Private Company', date('Y-m-d')],
            ['mt_' . $this->getUUID(), 'Public Company (Unlisted)', date('Y-m-d')],
            ['mt_' . $this->getUUID(), 'Charitable organisation / Not-for-profit', date('Y-m-d')],
            ['mt_' . $this->getUUID(), 'Partnership', date('Y-m-d')],
            ['mt_' . $this->getUUID(), 'Other', date('Y-m-d')]
        ])->execute();

        //table: restaurant_type

        $this->createTable('{{%restaurant_type}}', [
            'restaurant_type_uuid' => $this->char(60), // used as reference id
            'restaurant_uuid' => $this->char(60)->notNull(), // used as reference id
            'merchant_type_uuid' => $this->char(60),
            'business_type_uuid' => $this->char(60),
            'business_category_uuid' => $this->char(60),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_type', 'restaurant_type_uuid');

        //restaurant_uuid

        $this->createIndex('ind-restaurant_type-restaurant_uuid', 'restaurant_type', 'restaurant_uuid');

        $this->addForeignKey(
            'fk-restaurant_type-restaurant_uuid', 'restaurant_type',
            'restaurant_uuid', 'restaurant', 'restaurant_uuid', "CASCADE"
        );

        //merchant_type_uuid

        $this->createIndex('ind-restaurant_type-merchant_type_uuid', 'restaurant_type', 'merchant_type_uuid');

        $this->addForeignKey(
            'fk-restaurant_type-merchant_type_uuid', 'restaurant_type',
            'merchant_type_uuid', 'merchant_type', 'merchant_type_uuid'
        );

        //business_type_uuid

        $this->createIndex('ind-restaurant_type-business_type_uuid', 'restaurant_type', 'business_type_uuid');

        $this->addForeignKey(
            'fk-restaurant_type-business_type_uuid', 'restaurant_type',
            'business_type_uuid', 'business_type', 'business_type_uuid'
        );

        //business_category_uuid

        $this->createIndex('ind-restaurant_type-business_category_uuid', 'restaurant_type', 'business_category_uuid');

        $this->addForeignKey(
            'fk-restaurant_type-business_category_uuid', 'restaurant_type',
            'business_category_uuid', 'business_category', 'business_category_uuid'
        );

        //table: Restaurant_item_type

        $this->createTable('{{%restaurant_item_type}}', [
            'rit_uuid' => $this->char(60), // used as reference id
            'restaurant_uuid' => $this->char(60)->notNull(), // used as reference id
            'business_item_type_uuid' => $this->char(60)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_item_type', 'rit_uuid');

        //restaurant_uuid

        $this->createIndex('ind-restaurant_item_type-restaurant_uuid', 'restaurant_item_type', 'restaurant_uuid');

        $this->addForeignKey(
            'fk-restaurant_item_type-restaurant_uuid', 'restaurant_item_type',
            'restaurant_uuid', 'restaurant', 'restaurant_uuid', "CASCADE"
        );

        //business_item_type_uuid

        $this->createIndex('ind-restaurant_item_type-business_item_type_uuid', 'restaurant_item_type', 'business_item_type_uuid');

        $this->addForeignKey(
            'fk-restaurant_item_type-business_item_type_uuid', 'restaurant_item_type',
            'business_item_type_uuid', 'business_item_type', 'business_item_type_uuid'
        );
    }

    private function getUUID() {
        return Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231109_081603_business_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231109_081603_business_data cannot be reverted.\n";

        return false;
    }
    */
}
