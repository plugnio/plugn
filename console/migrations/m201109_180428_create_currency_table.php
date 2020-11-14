<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency}}`.
 */
class m201109_180428_create_currency_table extends Migration
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

        $this->createTable('{{%currency}}', [
            'currency_id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'code' => $this->string()->notNull(),
        ],$tableOptions);

        $sql = "
          INSERT INTO `currency` (`currency_id`, `title`, `code`) VALUES
          (1, 'Saudi Riyal', 'SAR'),
          (2, 'Kuwaiti Dinar', 'KWD'),
          (3, 'Bahraini Dinar', 'BHD')
        ";

        Yii::$app->db->createCommand($sql)->execute();




        $this->addColumn('restaurant', 'currency_id', $this->integer()->notNull()->defaultValue(2)->after('country_id'));


        // creates index for column `currency_id`
        $this->createIndex(
                'idx-restaurant-currency_id',
                'restaurant',
                'currency_id'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
                'fk-restaurant-currency_id',
                'restaurant',
                'currency_id',
                'currency',
                'currency_id',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-restaurant-currency_id', 'restaurant');
        $this->dropIndex('idx-restaurant-currency_id', 'restaurant');

        $this->dropColumn('restaurant', 'currency_id');


        $this->dropTable('{{%currency}}');
    }
}
