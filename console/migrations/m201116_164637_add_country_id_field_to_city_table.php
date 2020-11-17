<?php

use yii\db\Migration;

/**
 * Class m201116_164637_add_country_id_field_to_city_table
 */
class m201116_164637_add_country_id_field_to_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

          $this->addColumn('city', 'country_id', $this->integer()->notNull()->defaultValue(114)->after('city_id'));


          // creates index for column `country_id`
          $this->createIndex(
                  'idx-city-country_id',
                  'city',
                  'country_id'
          );

          // add foreign key for table `city`
          $this->addForeignKey(
                  'fk-city-country_id',
                  'city',
                  'country_id',
                  'country',
                  'country_id',
                  'CASCADE'
          );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropForeignKey('fk-city-country_id', 'city');
      $this->dropIndex('idx-city-country_id', 'city');

      $this->dropColumn('city', 'country_id');

    }
}
