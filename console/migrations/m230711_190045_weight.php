<?php

use yii\db\Migration;

/**
 * Class m230711_190045_weight
 */
class m230711_190045_weight extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn( "item_variant","weight", $this->float(8));
        $this->addColumn( "item_variant","length", $this->float(8));
        $this->addColumn( "item_variant","height", $this->float(8));
        $this->addColumn( "item_variant","width", $this->float(8));

        $this->addColumn( "item","weight", $this->float(8));
        $this->addColumn( "item","length", $this->float(8));
        $this->addColumn( "item","height", $this->float(8));
        $this->addColumn( "item","width", $this->float(8));

        $this->addColumn( "item","shipping", $this->boolean()->defaultValue(true));

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%shipping_method}}', [
            'shipping_method_id' => $this->primaryKey(),
            'name_en' => $this->string(),
            'name_ar' => $this->string(),
            'code' => $this->string(20),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%restaurant_shipping_method}}', [
            'restaurant_uuid' => $this->char(60),
            'shipping_method_id' => $this->integer(11)
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_shipping_method', [
            'restaurant_uuid',
            'shipping_method_id'
        ]);

        $this->addForeignKey(
            'fk-restaurant_shipping_method-shipping_method_id', 'restaurant_shipping_method',
            'shipping_method_id', 'shipping_method', 'shipping_method_id', "CASCADE"
        );

        $this->addForeignKey(
            'fk-restaurant_shipping_method-restaurant_uuid', 'restaurant_shipping_method',
            'restaurant_uuid', 'restaurant', 'restaurant_uuid', "CASCADE"
        );

        $this->addColumn( "order_item","weight", $this->float(8)->after('qty'));
        $this->addColumn( "order_item","length", $this->float(8)->after('weight'));
        $this->addColumn( "order_item","height", $this->float(8)->after('length'));
        $this->addColumn( "order_item","width", $this->float(8)->after('height'));
        $this->addColumn( "order_item","shipping", $this->boolean()->defaultValue(true)->after('width'));

        //Armada
        //Aramex
        //FedEx

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
        echo "m230711_190045_weight cannot be reverted.\n";

        return false;
    }
    */
}
