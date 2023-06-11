<?php

use yii\db\Migration;

/**
 * Class m230611_080136_plan_currency
 */
class m230611_080136_plan_currency extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('plan_price', [
            "plan_price_id" => $this->primaryKey(), // used as reference id
            "plan_id" => $this->integer(11)->notNull(),
            'currency' => $this->char(3)->notNull(),
            "price" => $this->decimal(9, 3),
            "created_at" => $this->dateTime(),
            "updated_at" => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex(
            'idx-plan_price-plan_id',
            'plan_price',
            'plan_id'
        );

        $this->addForeignKey(
            'fk-voucher_item-plan_id',
            'plan_price',
            'plan_id',
            'plan',
            'plan_id'
        );

        $currencies = \agent\models\Currency::find()->all();

        $plan = \agent\models\Plan::find()
            ->andWhere(['>', 'price', 0])
            ->one();

        $data = [];

        foreach ($currencies as $currency)
        {
            $data[] = [
                $plan->plan_id,
                $currency->code,
                round($plan->price * $currency->rate, $currency->decimal_place),
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
            ];
        }

        Yii::$app->db->createCommand()->batchInsert('plan_price',
            ['plan_id', 'currency', 'price', "created_at", "updated_at"], $data)->execute();
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
        echo "m230611_080136_plan_currency cannot be reverted.\n";

        return false;
    }
    */
}
