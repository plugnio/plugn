<?php

use yii\db\Migration;

/**
 * Class m230711_190045_weight
 */
class m230711_190045_weight extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn( "item_variant","weight", $this->float(8));
        $this->addColumn( "item_variant","length", $this->float(8));
        $this->addColumn( "item_variant","height", $this->float(8));
        $this->addColumn( "item_variant","width", $this->float(8));

        $this->addColumn( "item","weight", $this->float(8));

        $this->addColumn( "item","length", $this->float(8));
        $this->addColumn( "item","height", $this->float(8));
        $this->addColumn( "item","width", $this->float(8));

        $this->addColumn( "item","shipping", $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230711_190045_weight cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230711_190045_weight cannot be reverted.\n";

        return false;
    }
    */
}
