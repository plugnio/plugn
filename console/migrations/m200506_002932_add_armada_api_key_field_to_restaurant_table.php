<?php

use yii\db\Migration;

/**
 * Class m200506_002932_add_armada_api_key_field_to_restaurant_table
 */
class m200506_002932_add_armada_api_key_field_to_restaurant_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('restaurant', 'armada_api_key', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('restaurant', 'armada_api_key');
    }

}
