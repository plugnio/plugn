<?php

use yii\db\Migration;

/**
 * Class m210824_113904_update_resturant_tbl
 */
class m210824_113904_update_resturant_tbl extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn ('restaurant', 'annual_revenue', $this->char (60)->after ('payment_gateway_queue_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant', 'annual_revenue');
    }
}
