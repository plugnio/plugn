<?php

use yii\db\Migration;

/**
 * Class m201126_164544_add_delivery_zone_id_field_to_order_table
 */
class m201126_164544_add_delivery_zone_id_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order', 'delivery_zone_id', $this->bigInteger()->after('house_number'));
      $this->addColumn('order', 'country_id', $this->integer()->after('delivery_zone_id'));


        // creates index for column `country_id`
        $this->createIndex(
                'idx-order-country_id',
                'order',
                'country_id'
        );

        // add foreign key for table `order`
        $this->addForeignKey(
                'fk-order-country_id',
                'order',
                'country_id',
                'country',
                'country_id',
                'CASCADE'
        );


        // creates index for column `delivery_zone_id`
        $this->createIndex(
                'idx-order-delivery_zone_id',
                'order',
                'delivery_zone_id'
        );

        // add foreign key for table `order`
        $this->addForeignKey(
                'fk-order-delivery_zone_id',
                'order',
                'delivery_zone_id',
                'delivery_zone',
                'delivery_zone_id',
                'CASCADE'
        );

      $this->addColumn('order', 'country_name', $this->string()->after('country_id'));
      $this->addColumn('order', 'country_name_ar', $this->string()->after('country_name'));
      $this->addColumn('order', 'floor', $this->integer()->after('country_name_ar'));
      $this->addColumn('order', 'apartment', $this->string()->after('floor'));
      $this->addColumn('order', 'building', $this->string()->after('apartment'));
      $this->addColumn('order', 'office', $this->integer()->after('building'));
      $this->addColumn('order', 'city', $this->integer()->after('office'));
      $this->addColumn('order', 'postcode', $this->string(10)->after('city'));
      $this->addColumn('order', 'address_1', $this->string()->after('postcode'));
      $this->addColumn('order', 'address_2', $this->string()->after('address_1'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

      $this->dropForeignKey('fk-order-delivery_zone_id', 'order');
      $this->dropIndex('idx-order-delivery_zone_id', 'order');

      $this->dropForeignKey('fk-order-country_id', 'order');
      $this->dropIndex('idx-order-country_id', 'order');


      $this->dropColumn('order', 'delivery_zone_id');
      $this->dropColumn('order', 'country_id');

      $this->dropColumn('order', 'country_name');
      $this->dropColumn('order', 'country_name_ar');
      $this->dropColumn('order', 'floor');
      $this->dropColumn('order', 'apartment');
      $this->dropColumn('order', 'building');
      $this->dropColumn('order', 'office');
      $this->dropColumn('order', 'city');
      $this->dropColumn('order', 'postcode');
      $this->dropColumn('order', 'address_1');
      $this->dropColumn('order', 'address_2');
    }

}
