<?php

use common\models\Currency;
use yii\db\Migration;

/**
 * Class m211103_105549_currency_conversion_table
 */
class m211103_105549_currency_conversion_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('currency','currency_symbol',$this->string()->after('code'));
        $this->addColumn('currency','rate',$this->double()->after('currency_symbol'));
        $this->addColumn('currency','sort_order',$this->smallInteger(3)->defaultValue(0)->notNull()->after('rate'));
        $this->addColumn('currency','datetime',$this->dateTime()->after('sort_order'));

        Currency::getDataFromApi();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('currency','currency_symbol');
        $this->dropColumn('currency','rate');
        $this->dropColumn('currency','sort_order');
        $this->dropColumn('currency','datetime');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211103_105549_currency_conversion_table cannot be reverted.\n";

        return false;
    }
    */
}
