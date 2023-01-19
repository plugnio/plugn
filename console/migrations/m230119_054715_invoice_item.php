<?php

use yii\db\Migration;

/**
 * Class m230119_054715_invoice_item
 */
class m230119_054715_invoice_item extends Migration
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

        $this->createTable('invoice_item', [
            "invoice_item_uuid" => $this->char(60)->notNull(), // used as reference id
            "restaurant_uuid" => $this->char(60)->null(), // Which store made the payment?
            'invoice_uuid' => $this->char(60)->null(),
            'plan_id'=> $this->integer(11),
            'addon_uuid'=> $this->char(60),
            'order_uuid'=> $this->char(40),
            "comment" => $this->string()->null(),
            "total" => $this->decimal(10, 3)->notNull(), // amount charged to customer
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'invoice_item', 'invoice_item_uuid');

        $this->createIndex(
            'idx-invoice_item-plan_id',
            'invoice_item',
            'plan_id'
        );

        $this->addForeignKey(
            'fk-invoice_item-plan_id',
            'invoice_item',
            'plan_id',
            'plan',
            'plan_id',
            'SET NULL'
        );

        $this->createIndex(
            'idx-invoice_item-addon_uuid',
            'invoice_item',
            'addon_uuid'
        );

        $this->addForeignKey(
            'fk-invoice_item-addon_uuid',
            'invoice_item',
            'addon_uuid',
            'addon',
            'addon_uuid',
            'SET NULL'
        );

        $this->createIndex(
            'idx-invoice_item-order_uuid',
            'invoice_item',
            'order_uuid'
        );

        $this->addForeignKey(
            'fk-invoice_item-order_uuid',
            'invoice_item',
            'order_uuid',
            'order',
            'order_uuid',
            'SET NULL'
        );

        $this->createIndex(
            'idx-invoice_item-invoice_uuid',
            'invoice_item',
            'invoice_uuid'
        );

        $this->addForeignKey(
            'fk-invoice_item-invoice_uuid',
            'invoice_item',
            'invoice_uuid',
            'restaurant_invoice',
            'invoice_uuid',
            'SET NULL'
        );

        $this->createIndex(
            'idx-invoice_item-restaurant_uuid',
            'invoice_item',
            'restaurant_uuid'
        );

        $this->addForeignKey(
            'fk-invoice_item-restaurant_uuid',
            'invoice_item',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'SET NULL'
        );

        $invoices = \common\models\RestaurantInvoice::find()->all();

        $items = [];

        foreach ($invoices as $invoice) {
            $items[] = [
                "invoice_item_uuid" =>  'invoice_item_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar (),
                "restaurant_uuid" => $invoice->restaurant_uuid,
                'invoice_uuid' => $invoice->invoice_uuid,
                'order_uuid'=> isset($invoice->order_uuid)? $invoice->order_uuid: null,
                "total" => $invoice->amount,
                'created_at' => new \yii\db\Expression("NOW()"),
                'updated_at' => new \yii\db\Expression("NOW()")
            ];
        }

        $this->db->createCommand()->batchInsert('invoice_item',
            ['invoice_item_uuid', 'restaurant_uuid', "invoice_uuid", "order_uuid", "total", "created_at", 'updated_at'], $items
        )->execute();

        Yii::$app->db->createCommand('SET foreign_key_checks = 0')->execute();

        $this->dropForeignKey("fk-restaurant_invoice-order_uuid", "restaurant_invoice");

        $this->dropIndex("idx-restaurant_invoice-order_uuid", "restaurant_invoice");

        $this->dropColumn('restaurant_invoice', 'order_uuid');

        Yii::$app->db->createCommand('SET foreign_key_checks = 1')->execute();
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
        echo "m230119_054715_invoice_item cannot be reverted.\n";

        return false;
    }
    */
}
