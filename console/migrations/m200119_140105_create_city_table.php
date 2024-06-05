<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city}}`.
 */
class m200119_140105_create_city_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%city}}', [
            'city_id' => $this->primaryKey(),
            'city_name' => $this->string(255)->notNull(),
            'city_name_ar' => $this->string(255)->notNull(),
            "is_deleted" => $this->boolean()->defaultValue(0)
                ], $tableOptions);

 
        $sql = "INSERT INTO `city` (`city_id`, `city_name`, `city_name_ar`) VALUES
                (1, 'Kuwait City', 'مدينة الكويت'),
                (2, 'Hawally', 'حولي'),
                (3, 'Mubarak Al Kabir', 'مبارك الكبير'),
                (4, 'Farwaniya', 'الفروانية'),
                (6, 'Ahmadi', 'الأحمدي'),
                (7, 'Jahra', 'الجهراء');";

        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%city}}');
    }

}
