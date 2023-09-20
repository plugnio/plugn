<?php

use yii\db\Migration;

/**
 * Class m230920_095206_iban
 */
class m230920_095206_iban extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'iban_certificate_file',
            $this->string()->after('authorized_signature_file'));

        $this->addColumn('restaurant', 'iban_certificate_file_id',
            $this->string()->after('iban_certificate_file'));

        $this->addColumn('restaurant', 'is_tap_created',
            $this->boolean()->after('is_tap_enable')->defaultValue(0));

        $this->addColumn('restaurant', 'is_tap_business_active',
            $this->boolean()->after('is_tap_enable')->defaultValue(0));

        Yii::$app->db->createCommand("UPDATE restaurant SET is_tap_created = is_tap_enable, 
                        is_tap_business_active = is_tap_enable")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant', 'is_tap_created');

        $this->dropColumn('restaurant', 'is_tap_business_active');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230920_095206_iban cannot be reverted.\n";

        return false;
    }
    */
}
