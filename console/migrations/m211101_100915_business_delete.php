<?php

use yii\db\Migration;

/**
 * Class m211101_100915_business_delete
 */
class m211101_100915_business_delete extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn (
            'business_location', 
            'is_deleted', 
            $this->boolean()->defaultValue(0)->after('diggipack_customer_id')
        );    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211101_100915_business_delete cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211101_100915_business_delete cannot be reverted.\n";

        return false;
    }
    */
}
