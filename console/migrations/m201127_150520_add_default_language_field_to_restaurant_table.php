<?php

use yii\db\Migration;

/**
 * Class m201127_150520_add_default_language_field_to_restaurant_table
 */
class m201127_150520_add_default_language_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'default_language', $this->char(2)->notNull()->defaultValue('en'));
      $this->addColumn('restaurant', 'phone_number_country_code', $this->integer(3)->after('phone_number')->defaultValue(965));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'default_language');
      $this->dropColumn('restaurant', 'phone_number_country_code');
    }

}
