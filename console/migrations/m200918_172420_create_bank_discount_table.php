<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bank_discount}}`.
 */
class m200918_172420_create_bank_discount_table extends Migration
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


         $this->createTable('bank', [
           'bank_id' => $this->primaryKey(),
           'bank_name' => $this->string(100),
           'bank_iban_code' => $this->string(64)->notNull(),
           'bank_swift_code' => $this->string(64)->notNull(),
           'bank_address' => $this->string(100)->notNull(),
           'bank_transfer_type' => $this->string(100)->notNull(),
           'bank_created_at' => $this->dateTime()->notNull(),
           'bank_updated_at' => $this->dateTime()->notNull(),
           'deleted' => $this->smallInteger(1)->defaultValue(0)->notNull()
       ], $tableOptions);


       // Add Banks as Base
     $sql = 'INSERT INTO bank SET
         bank_id = 1,
         bank_name = "AHLI UNITED BANK",
         bank_iban_code = "BKME",
         bank_swift_code = "BKMEKWKW",
         bank_address = "Kuwait",
         bank_transfer_type = "TRF",
         bank_created_at = "2018-08-21 19:20:58",
         bank_updated_at = "2018-08-21 19:40:58",
         deleted = 0
         ';
     Yii::$app->db->createCommand($sql)->execute();
     $sql = 'INSERT INTO bank SET
         bank_id = 2,
         bank_name = "NATIONAL BANK OF KUWAIT, S.A.K.",
         bank_iban_code = "NBOK",
         bank_swift_code = "NBOKKWKW",
         bank_address = "Kuwait",
         bank_transfer_type = "LCL",
         bank_created_at = "2018-08-21 19:20:58",
         bank_updated_at = "2018-08-21 19:40:58",
         deleted = 0
         ';
     Yii::$app->db->createCommand($sql)->execute();
     $sql = 'INSERT INTO bank SET
         bank_id = 3,
         bank_name = "COMMERCIAL BANK OF KUWAIT S.A.K., THE",
         bank_iban_code = "COMB",
         bank_swift_code = "COMBKWKW",
         bank_address = "Kuwait",
         bank_transfer_type = "LCL",
         bank_created_at = "2018-08-21 19:20:58",
         bank_updated_at = "2018-08-21 19:40:58",
         deleted = 0
         ';
     Yii::$app->db->createCommand($sql)->execute();
     $sql = 'INSERT INTO bank SET
         bank_id = 4,
         bank_name = "GULF BANK K.S.C.",
         bank_iban_code = "GULB",
         bank_swift_code = "GULBKWKW",
         bank_address = "Kuwait",
         bank_transfer_type = "LCL",
         bank_created_at = "2018-08-21 19:20:58",
         bank_updated_at = "2018-08-21 19:40:58",
         deleted = 0
         ';
     Yii::$app->db->createCommand($sql)->execute();
     $sql = 'INSERT INTO bank SET
         bank_id = 5,
         bank_name = "ALAHLI BANK OF KUWAIT (K.S.C.)",
         bank_iban_code = "ABKK",
         bank_swift_code = "ABKKKWKW",
         bank_address = "Kuwait",
         bank_transfer_type = "LCL",
         bank_created_at = "2018-08-21 19:20:58",
         bank_updated_at = "2018-08-21 19:40:58",
         deleted = 0
         ';
     Yii::$app->db->createCommand($sql)->execute();
     $sql = 'INSERT INTO bank SET
         bank_id = 6,
         bank_name = "KUWAIT INTERNATIONAL BANK",
         bank_iban_code = "KWIB",
         bank_swift_code = "KWIBKWKW",
         bank_address = "Kuwait",
         bank_transfer_type = "LCL",
         bank_created_at = "2018-08-21 19:20:58",
         bank_updated_at = "2018-08-21 19:40:58",
         deleted = 0
         ';
     Yii::$app->db->createCommand($sql)->execute();
     $sql = 'INSERT INTO bank SET
         bank_id = 7,
         bank_name = "BURGAN BANK (S.A.K.)",
         bank_iban_code = "BRGN",
         bank_swift_code = "BRGNKWKW",
         bank_address = "Kuwait",
         bank_transfer_type = "LCL",
         bank_created_at = "2018-08-21 19:20:58",
         bank_updated_at = "2018-08-21 19:40:58",
         deleted = 0
         ';
     Yii::$app->db->createCommand($sql)->execute();
     $sql = 'INSERT INTO bank SET
         bank_id = 8,
         bank_name = "KUWAIT FINANCE HOUSE",
         bank_iban_code = "KFHO",
         bank_swift_code = "KFHOKWKW",
         bank_address = "Kuwait",
         bank_transfer_type = "LCL",
         bank_created_at = "2018-08-21 19:20:58",
         bank_updated_at = "2018-08-21 19:40:58",
         deleted = 0
         ';
     Yii::$app->db->createCommand($sql)->execute();
     $sql = 'INSERT INTO bank SET
         bank_id = 9,
         bank_name = "BOUBYAN BANK K.S.C.",
         bank_iban_code = "BBYN",
         bank_swift_code = "BBYNKWKW",
         bank_address = "Kuwait",
         bank_transfer_type = "LCL",
         bank_created_at = "2018-08-21 19:20:58",
         bank_updated_at = "2018-08-21 19:40:58",
         deleted = 0
         ';
     Yii::$app->db->createCommand($sql)->execute();
     $sql = 'INSERT INTO bank SET
         bank_id = 10,
         bank_name = "WARBA BANK K.S.C",
         bank_iban_code = "WRBA",
         bank_swift_code = "WRBAKWKW",
         bank_address = "Kuwait",
         bank_transfer_type = "LCL",
         bank_created_at = "2018-08-21 19:20:58",
         bank_updated_at = "2018-08-21 19:40:58",
         deleted = 0
         ';
     Yii::$app->db->createCommand($sql)->execute();
     $sql = 'INSERT INTO bank SET
         bank_id = 11,
         bank_name = "DOHA BANK Q.S.C.",
         bank_iban_code = "DOHB",
         bank_swift_code = "DOHBKWKW",
         bank_address = "Kuwait",
         bank_transfer_type = "LCL",
         bank_created_at = "2018-08-21 19:20:58",
         bank_updated_at = "2018-08-21 19:40:58",
         deleted = 0
         ';
     Yii::$app->db->createCommand($sql)->execute();



     $this->addColumn('voucher', 'bank_id' , $this->integer());


        // creates index for column `bank_id`
        $this->createIndex(
                'idx-voucher-bank_id',
                'voucher',
                'bank_id'
        );

        // add foreign key for table `voucher`
        $this->addForeignKey(
                'fk-voucher-bank_id',
                'voucher',
                'bank_id',
                'bank',
                'bank_id',
                'CASCADE'
        );



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-voucher-bank_id', 'voucher');
        $this->dropIndex('idx-voucher-bank_id', 'voucher');

        $this->dropColumn('voucher', 'bank_id');

        $this->dropTable('bank');
    }
}
