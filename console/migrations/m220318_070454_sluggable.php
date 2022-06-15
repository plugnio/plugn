<?php

use yii\db\Migration;

/**
 * Class m220318_070454_sluggable
 */
class m220318_070454_sluggable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%item}}','slug', $this->string()->notNull()->after('item_status'));

        $this->addColumn('{{%category}}','slug', $this->string()->notNull()->after('sort_number'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220318_070454_sluggable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220318_070454_sluggable cannot be reverted.\n";

        return false;
    }
    */
}
