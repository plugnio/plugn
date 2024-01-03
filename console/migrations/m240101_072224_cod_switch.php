<?php

use yii\db\Migration;

/**
 * Class m240101_072224_cod_switch
 */
class m240101_072224_cod_switch extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'enable_cod_fee',
            $this->boolean()->after('platform_fee')->defaultValue(true));

        //update cod fee to disabled for existing store

        \common\models\Restaurant::updateAll(['enable_cod_fee' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240101_072224_cod_switch cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240101_072224_cod_switch cannot be reverted.\n";

        return false;
    }
    */
}
