<?php

use yii\db\Migration;

/**
 * Class m201028_142149_add_description_field_to_plan_table
 */
class m201028_142149_add_description_field_to_plan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('plan', 'description', $this->string()->notNull());

      $sql = 'UPDATE  plan SET
          description = "Ideal for stores making a large number of sales"
           WHERE price = 200
          ';
          Yii::$app->db->createCommand($sql)->execute();

      $sql = 'UPDATE  plan SET
          description = "Ideal for startups and small businesses"
           WHERE price = 0
          ';
      Yii::$app->db->createCommand($sql)->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('plan', 'description');
    }

}
