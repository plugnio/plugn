<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_method}}`.
 */
class m200119_140122_create_payment_method_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%payment_method}}', [
            'payment_method_id' => $this->primaryKey(),
            'payment_method_name' => $this->string(),
            'payment_method_name_ar' => $this->string(),
                ], $tableOptions);

        $sql = "INSERT INTO `payment_method` (`payment_method_id`, `payment_method_name`, `payment_method_name_ar`) VALUES
                (1, 'K-net', 'كي نت'),
                (2, 'Credit Card', 'بطاقة الائتمان'),
                (3, 'Cash', 'نقدي')
                ;";

        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%payment_method}}');
    }

}
