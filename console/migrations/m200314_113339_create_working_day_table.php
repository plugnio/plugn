<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%working_day}}`.
 */
class m200314_113339_create_working_day_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%working_day}}', [
            'working_day_id' => $this->bigPrimaryKey(),
            'name' => $this->string(),
            'name_ar' => $this->string(),
        ],$tableOptions);


        $sql = 'INSERT INTO working_day SET
            working_day_id = 1,
            name = "Sunday",
            name_ar = "الأحد"
          ';
        Yii::$app->db->createCommand($sql)->execute();
        
        
        $sql = 'INSERT INTO working_day SET
            working_day_id = 2,
            name = "Monday",
            name_ar = "الإثنين"
          ';
        Yii::$app->db->createCommand($sql)->execute();
        
        $sql = 'INSERT INTO working_day SET
            working_day_id = 3,
            name = "Tuesday",
            name_ar = "الثلاثاء"
          ';
        
        Yii::$app->db->createCommand($sql)->execute();
        
        $sql = 'INSERT INTO working_day SET
            working_day_id = 4,
            name = "Wednesday",
            name_ar = "الأربعاء"
          ';
        Yii::$app->db->createCommand($sql)->execute();
        
        $sql = 'INSERT INTO working_day SET
            working_day_id = 5,
            name = "Thursday",
            name_ar = "الخميس"
          ';
        Yii::$app->db->createCommand($sql)->execute();
        
        $sql = 'INSERT INTO working_day SET
            working_day_id = 6,
            name = "Friday",
            name_ar = "الجمعة"
          ';
        Yii::$app->db->createCommand($sql)->execute();
        
        $sql = 'INSERT INTO working_day SET
            working_day_id = 7,
            name = "Saturday",
            name_ar = "السبت"
          ';
        Yii::$app->db->createCommand($sql)->execute();

        
        
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%working_day}}');
    }

}
