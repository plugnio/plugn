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
        $table = $this
            ->getDb()
            ->getSchema()
            ->getTableSchema('currency');

        if (!isset($table->columns['status'])) {
            $this->addColumn('currency', 'status', $this->tinyInteger(1)
                ->defaultValue(1)->after('sort_order'));
        }

            if (!isset($table->columns['currency_symbol'])) {
                $this->addColumn('currency','currency_symbol',$this->string()->after('code'));
            }

            if (!isset($table->columns['rate'])) {
                $this->addColumn('currency','rate',$this->double()->after('currency_symbol'));
            }

            if (!isset($table->columns['sort_order'])) {
                $this->addColumn('currency','sort_order',$this->smallInteger(3)->defaultValue(0)->notNull()->after('rate'));
            }

            if (!isset($table->columns['datetime'])) {
                $this->addColumn('currency','datetime',$this->dateTime()->after('sort_order'));
            }

        if (!isset($table->columns['decimal_place'])) {
            $this->addColumn(
                'currency',
                'decimal_place',
                $this->tinyInteger(1)
                    ->defaultValue(2)
                    ->after('rate')
            );
        }

        Currency::getDataFromApi(false);
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
