<?php

use yii\db\Migration;
use common\models\Option;

/**
 * Class m220330_111120_optionh
 */
class m220330_111120_optionh extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('option', 'is_required', $this->boolean()->defaultValue(false)->after('max_qty'));
        $this->addColumn('option', 'option_type', $this->tinyInteger(1)->after('option_name_ar'));

        Option::updateAll(['option_type' => Option::TYPE_CHECKBOX]);

        Option::updateAll([
            'is_required' => true
        ], [
            '>', 'min_qty', 0
        ]);

        Option::updateAll([
            'option_type' => Option::TYPE_RADIO
        ], [
            'max_qty' => 1
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220330_111120_optionh cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220330_111120_optionh cannot be reverted.\n";

        return false;
    }
    */
}
