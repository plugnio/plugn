<?php

use yii\db\Migration;

/**
 * Class m211008_073951_item
 */
class m211008_073951_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn (
            'order',
            'currency_code',
            $this->char(3)->after('payment_method_name_ar')->notNull ()
        );

        //copy data in new column

        //$sql = "SELECT * FROM `restaurant` where restaurant_uuid IN (select restaurant_uuid from `order`)";

        $rows = \common\models\Restaurant::find()
            ->joinWith('currency')
            ->andWhere(new \yii\db\Expression('restaurant_uuid IN (select DISTINCT restaurant_uuid from `order`)'))
            ->all();

        //Yii::$app->db->createCommand ($sql)->queryAll ();

        foreach($rows as $row) {

            if(!$row->currency) {
                continue;
            }

            \common\models\Order::updateAll([
                'currency_code' => $row->currency->code
            ], [
                'restaurant_uuid' => $row['restaurant_uuid']
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211008_073951_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211008_073951_item cannot be reverted.\n";

        return false;
    }
    */
}
