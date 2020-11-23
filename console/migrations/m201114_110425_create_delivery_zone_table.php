<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%delivery_zone}}`.
 */
class m201114_110425_create_delivery_zone_table extends Migration
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


      $this->createTable('{{%delivery_zone}}', [
          'delivery_zone_id' => $this->bigPrimaryKey(),
          'country_id' => $this->integer()->notNull(),
          'business_location_id' => $this->bigInteger()->notNull(),
          'delivery_time' => $this->integer()->unsigned()->defaultValue(60),
          'delivery_fee' => $this->float()->unsigned()->defaultValue(0),
          'min_charge' => $this->float()->unsigned()->defaultValue(0)
      ],$tableOptions);


      // creates index for column `country_id`
      $this->createIndex(
              'idx-delivery_zone-country_id',
              'delivery_zone',
              'country_id'
      );

      // add foreign key for table `delivery_zone`
      $this->addForeignKey(
              'fk-delivery_zone-country_id',
              'delivery_zone',
              'country_id',
              'country',
              'country_id',
              'CASCADE'
      );


      // creates index for column `business_location_id`
      $this->createIndex(
              'idx-delivery_zone-business_location_id',
              'delivery_zone',
              'business_location_id'
      );

      // add foreign key for table `delivery_zone`
      $this->addForeignKey(
              'fk-delivery_zone-business_location_id',
              'delivery_zone',
              'business_location_id',
              'business_location',
              'business_location_id',
              'CASCADE'
      );


      $this->createTable('{{%area_delivery_zone}}', [
          'delivery_zone_id' => $this->bigInteger()->notNull(),
          'country_id' => $this->integer()->notNull(),
          'city_id' => $this->integer()->notNull(),
          'area_id' => $this->integer()->notNull()
      ],$tableOptions);

      $this->addPrimaryKey('PK', 'area_delivery_zone', ['delivery_zone_id','area_id']);



      // creates index for column `country_id`
      $this->createIndex(
          'idx-area_delivery_zone-country_id',
          'area_delivery_zone',
          'country_id'
      );

      // add foreign key for table `area_delivery_zone`
      $this->addForeignKey(
          'fk-area_delivery_zone-country_id',
          'area_delivery_zone',
          'country_id',
          'country',
          'country_id',
          'CASCADE'
      );


      // creates index for column `city_id`
      $this->createIndex(
          'idx-area_delivery_zone-city_id',
          'area_delivery_zone',
          'city_id'
      );

      // add foreign key for table `area_delivery_zone`
      $this->addForeignKey(
          'fk-area_delivery_zone-city_id',
          'area_delivery_zone',
          'city_id',
          'city',
          'city_id',
          'CASCADE'
      );



      // creates index for column `area_id`
      $this->createIndex(
          'idx-area_delivery_zone-area_id',
          'area_delivery_zone',
          'area_id'
      );

      // add foreign key for table `area_delivery_zone`
      $this->addForeignKey(
          'fk-area_delivery_zone-area_id',
          'area_delivery_zone',
          'area_id',
          'area',
          'area_id',
          'CASCADE'
      );


      // creates index for column `delivery_zone_id`
      $this->createIndex(
              'idx-area_delivery_zone-delivery_zone_id',
              'area_delivery_zone',
              'delivery_zone_id'
      );

      // add foreign key for table `area_delivery_zone`
      $this->addForeignKey(
              'fk-area_delivery_zone-delivery_zone_id',
              'area_delivery_zone',
              'delivery_zone_id',
              'delivery_zone',
              'delivery_zone_id',
              'CASCADE'
      );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        $this->dropForeignKey('fk-area_delivery_zone-area_id', 'area_delivery_zone');
        $this->dropIndex('idx-area_delivery_zone-area_id', 'area_delivery_zone');

        $this->dropForeignKey('fk-area_delivery_zone-delivery_zone_id', 'area_delivery_zone');
        $this->dropIndex('idx-area_delivery_zone-delivery_zone_id', 'area_delivery_zone');

        $this->dropTable('{{%area_delivery_zone}}');

        $this->dropForeignKey('fk-delivery_zone-business_location_id', 'delivery_zone');
        $this->dropIndex('idx-delivery_zone-business_location_id', 'delivery_zone');

        $this->dropForeignKey('fk-delivery_zone-country_id', 'delivery_zone');
        $this->dropIndex('idx-delivery_zone-country_id', 'delivery_zone');

        $this->dropTable('{{%delivery_zone}}');
    }
}
