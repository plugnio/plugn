<?php

use yii\db\Migration;

/**
 * Class m230619_190800_state
 */
class m230619_190800_state extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'area_delivery_zone',
            'state_id',
            $this->integer(11)->after('country_id'));

        $this->createIndex(
            'idx-area_delivery_zone-state_id',
            'area_delivery_zone',
            'state_id'
        );

        $this->addForeignKey(
            'fk-area_delivery_zone-state_id',
            'area_delivery_zone',
            'state_id',
            'state',
            'state_id'
        );

        $this->addColumn('delivery_zone', 'deliver_whole_country',
            $this->boolean()->after('time_unit'));

        $country = \agent\models\Country::findOne(['iso' => 'KW']);

        if($country)
        {
            \common\models\DeliveryZone::updateAll(['deliver_whole_country' => 1], [
                '!=', 'country_id', $country->country_id
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230619_190800_state cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230619_190800_state cannot be reverted.\n";

        return false;
    }
    */
}
