<?php

use yii\db\Migration;

/**
 * Class m201202_132406_drop_issuing_coutnry
 */
class m201202_132406_drop_issuing_coutnry extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->dropColumn('restaurant', 'authorized_signature_issuing_country');
      $this->dropColumn('restaurant', 'commercial_license_issuing_country');
      $this->dropColumn('restaurant', 'identification_issuing_country');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->addColumn('restaurant', 'authorized_signature_issuing_country', $this->string()->defaultValue('KW')->notNull());
      $this->addColumn('restaurant', 'commercial_license_issuing_country', $this->string()->defaultValue('KW')->notNull());
      $this->addColumn('restaurant', 'identification_issuing_country', $this->string()->defaultValue('KW')->notNull());
    }

}
