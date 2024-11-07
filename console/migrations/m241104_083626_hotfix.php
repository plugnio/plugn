<?php

use yii\db\Migration;

/**
 * Class m241104_083626_hotfix
 */
class m241104_083626_hotfix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = $this
            ->getDb()
            ->getSchema()
            ->getTableSchema('restaurant');

        if (!isset($table->columns['total_items'])) {
            $this->addColumn("restaurant", "total_items", $this->integer(11)->defaultValue(0));

            $query = \common\models\Restaurant::find()
                ->andWhere(['is_deleted' => false]);

            foreach ($query->batch() as $stores) {

                foreach ($stores as $store) {
                    $total_items = $store->getItems()->count();

                    \common\models\Restaurant::updateAll(['total_items' => $total_items], [
                        "restaurant_uuid" => $store->restaurant_uuid
                    ]);
                }
            }
        }

        /*$sql = "ALTER TABLE `item`
        MODIFY `item_name` VARCHAR(255) COLLATE utf8mb4_unicode_ci,
        MODIFY `item_name_ar` VARCHAR(255) COLLATE utf8mb4_unicode_ci,
        MODIFY `item_description` text COLLATE utf8mb4_unicode_ci,
        MODIFY `item_description_ar` VARCHAR(255) COLLATE utf8mb4_unicode_ci";

        Yii::$app->db->createCommand($sql)->execute();*/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241104_083626_hotfix cannot be reverted.\n";

        return false;
    }
    */
}
