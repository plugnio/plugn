<?php

use yii\db\Expression;
use yii\db\Migration;
use common\models\Staff;


/**
 * Class m220530_125457_staff_access
 */
class m220530_125457_staff_access extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand()->batchInsert('staff', [
            'staff_name', 'staff_email', 'staff_password_hash', 'staff_status',
            'staff_created_at', 'staff_updated_at', 'staff_auth_key'
        ], [
            [
                'Krishna',
                'kk@bawes.net',
                Yii::$app->security->generatePasswordHash ('KK@PlugnCRM'),
                Staff::STATUS_ACTIVE,
                new Expression('NOW()'),
                new Expression('NOW()'),
                ''
            ],
            [
                'Anil',
                'anilkumar.dhiman1@gmail.com',
                Yii::$app->security->generatePasswordHash ('Anil@PlugnCRM'),
                Staff::STATUS_ACTIVE,
                new Expression('NOW()'),
                new Expression('NOW()'),
                ''
            ],
            [
                'Customer Service Agent #1',
                'cs1@plugn.io',
                Yii::$app->security->generatePasswordHash ('CS1@PlugnCRM'),
                Staff::STATUS_ACTIVE,
                new Expression('NOW()'),
                new Expression('NOW()'),
                ''
            ],
            [
                'Customer Service Agent #2',
                'cs2@plugn.io',
                Yii::$app->security->generatePasswordHash ('CS2@PlugnCRM'),
                Staff::STATUS_ACTIVE,
                new Expression('NOW()'),
                new Expression('NOW()'),
                ''
            ],
        ])->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220530_125457_staff_access cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220530_125457_staff_access cannot be reverted.\n";

        return false;
    }
    */
}
