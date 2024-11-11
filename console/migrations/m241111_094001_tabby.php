<?php

use yii\db\Migration;

/**
 * Class m241111_094001_tabby
 */
class m241111_094001_tabby extends Migration
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

        $this->createTable('{{%tabby_transaction}}', [
            'id' => $this->integer(11),
            "body" => $this->text(),
            "order_uuid" => $this->char(40)->notNull(),
            "status" => $this->string(16)->notNull(),
            "source" => $this->string(16)->notNull(),
            "transaction_id" => $this->string(64)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'tabby_transaction', ['id', "order_uuid", "transaction_id"]);

        // creates index for column "order_uuid"
        $this->createIndex(
            'idx-tabby_transaction-order_uuid', 'tabby_transaction', 'order_uuid'
        );

        // add foreign key for table "order"
        $this->addForeignKey(
            'fk-tabby_transaction-order_uuid', 'tabby_transaction', 'order_uuid',
            'order', 'order_uuid',"CASCADE", "CASCADE"
        );

        $pm = new \common\models\PaymentMethod();
        $pm->payment_method_name = "Tabby";
        $pm->payment_method_name_ar = "Tabby";
        $pm->vat = 0;
        $pm->payment_method_code = \common\models\PaymentMethod::CODE_TABBY;
        $pm->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241111_094001_tabby cannot be reverted.\n";

        return false;
    }
    */
}
