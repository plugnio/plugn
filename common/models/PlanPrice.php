<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "plan_price".
 *
 * @property int $plan_price_id
 * @property int $plan_id
 * @property string $currency
 * @property float|null $price
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Plan $plan
 */
class PlanPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_id', 'currency'], 'required'],
            [['plan_id'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['currency'], 'string', 'max' => 3],
            [['plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Plan::className(), 'targetAttribute' => ['plan_id' => 'plan_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'plan_price_id' => Yii::t('app', 'Plan Price ID'),
            'plan_id' => Yii::t('app', 'Plan ID'),
            'currency' => Yii::t('app', 'Currency'),
            'price' => Yii::t('app', 'Price'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Plan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlan()
    {
        return $this->hasOne(Plan::className(), ['plan_id' => 'plan_id']);
    }
}
