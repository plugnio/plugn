<?php

use yii\db\Migration;

/**
 * Class m230105_094951_payment_currencies
 */
class m230105_094951_payment_currencies extends Migration
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

        $this->createTable('{{%payment_method_currency}}', [
            'pmc_id' => $this->primaryKey(11),
            'payment_method_id'=> $this->integer(11)->notNull(),
            'currency' => $this->char(3)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->createIndex(
            'idx-payment_method_currency-payment_method_id', 'payment_method_currency', 'payment_method_id'
        );

        $this->addForeignKey(
            'fk-payment_method_currency-payment_method_id', 'payment_method_currency', 'payment_method_id',
                'payment_method', 'payment_method_id'
        );

        //add values

        $methods = \common\models\PaymentMethod::find()->all();

        $allCurrencies = \common\models\Currency::find()->all();

        foreach ($methods as $method) {
            $supportedCurrencies = \yii\helpers\ArrayHelper::getColumn($allCurrencies, "code");

            switch ($method->source_id) {

                case "src_kw.knet":
                    $supportedCurrencies = ["KWD"];
                    break;
                case "src_card":
                    $supportedCurrencies = ["AED","BHD","EGP","EUR","GBP","KWD","OMR","QAR","SAR","USD"];
                    break;
                case "src_sa.mada":
                    $supportedCurrencies = ["SAR"];
                    break;
                case "src_bh.benefit":
                    $supportedCurrencies = ["BHD"];
                    break;
                case "src_om.omannet":
                    $supportedCurrencies = ["OMR"];
                    break;
                case "src_eg.fawry":
                   $supportedCurrencies = ["EGP"];
                   break;
            }

            foreach ($supportedCurrencies as $supportedCurrency) {
                $model = new \common\models\PaymentMethodCurrency();
                $model->payment_method_id = $method->payment_method_id;
                $model->currency = $supportedCurrency;
                $model->save();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230105_094951_payment_currencies cannot be reverted.\n";

        return false;
    }
    */
}
