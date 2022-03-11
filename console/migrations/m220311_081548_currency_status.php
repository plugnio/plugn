<?php

use yii\db\Migration;

/**
 * Class m220311_081548_currency_status
 */
class m220311_081548_currency_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('currency', 'status', $this->tinyInteger(1)
            ->defaultValue(1)->after('sort_order'));

        $arr = [
            'KWD',
            'SAR',
            'OMR',
            'QAR',
            'BHD',
            'UAE',
            'EGP',
            'JOD',
            'LBP'
        ];

        \common\models\Currency::updateAll(['status' => \common\models\Currency::STATUS_INACTIVE], [
            'NOT IN', 'code', $arr
        ]);
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
        echo "m220311_081548_currency_status cannot be reverted.\n";

        return false;
    }
    */
}
