<?php

use yii\db\Migration;
use common\models\Currency;


/**
 * Class m211116_084242_currency
 */
class m211116_084242_currency extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'currency',
            'decimal_place',
            $this->tinyInteger(1)
                ->defaultValue(2)
                ->after('rate')
        );

        Currency::updateAll(['decimal_place' => 8], ['code' => 'BTC']);

        Currency::updateAll(['decimal_place' => 3], ['code' => 'BHD']);

        Currency::updateAll(['decimal_place' => 4], ['code' => 'CLF']);

        Currency::updateAll(['decimal_place' => 0], ['IN', 'code', [
            'BIF', 'BYN', 'BYR', 'CLP', 'DJF', 'GNF',
            'JPY', 'KMF', 'KRW', 'PYG', 'RWF', 'UGX', 'VND', 'VUV',
            'XAF', 'XAG', 'XDR', 'XOF', 'XPF', 'XAU'
        ]]);

        Currency::updateAll(['decimal_place' => 3], ['IN', 'code', [
            'IQD', 'JOD', 'KWD', 'LYD', 'OMR', 'TND'
        ]]);
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
        echo "m211116_084242_currency cannot be reverted.\n";

        return false;
    }
    */
}
