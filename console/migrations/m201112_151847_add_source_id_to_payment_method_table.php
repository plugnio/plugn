<?php

use yii\db\Migration;

/**
 * Class m201112_151847_add_source_id_to_payment_method_table
 */
class m201112_151847_add_source_id_to_payment_method_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('payment_method', 'source_id', $this->string());

      $sql = "
        INSERT INTO `payment_method` (`payment_method_id`, `payment_method_name`, `payment_method_name_ar`,`source_id`) VALUES
        (4, 'Mada', 'مدى', 'src_sa.mada'),
        (5, 'Benefit', 'بنفت', 'src_bh.benefit')
      ";

      Yii::$app->db->createCommand($sql)->execute();

      $sql = "
        UPDATE `payment_method` SET  `source_id`='src_kw.knet' WHERE `payment_method_id` = 1;
      ";

      Yii::$app->db->createCommand($sql)->execute();

      $sql = "
        UPDATE `payment_method` SET  `source_id`='src_card' WHERE `payment_method_id` = 2;
      ";

      Yii::$app->db->createCommand($sql)->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('payment_method', 'source_id');
    }
}
