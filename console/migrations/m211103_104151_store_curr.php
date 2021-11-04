<?php

use yii\db\Migration;

/**
 * Class m211103_104151_store_curr
 */
class m211103_104151_store_curr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $restaurants = Yii::$app->db->createCommand ('select * from restaurant')->queryAll ();

        foreach($restaurants as $restaurant)
        {
            if(empty($restaurant['currency_id']))
                continue;

            $model = new \common\models\RestaurantCurrency();
            $model->restaurant_uuid = $restaurant['restaurant_uuid'];
            $model->currency_id = $restaurant['currency_id'];
            $model->save();
        }


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211103_104151_store_curr cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211103_104151_store_curr cannot be reverted.\n";

        return false;
    }
    */
}
