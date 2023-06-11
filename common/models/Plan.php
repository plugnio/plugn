<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "plan".
 *
 * @property int $plan_id
 * @property string $name
 * @property float|null $price
 * @property int $valid_for
 * @property float|null $platform_fee
 *
 * @property Subscription[] $subscriptions
 * @property PlanPrice[] $planPrices
 */
class Plan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['valid_for'], 'integer'],
            [['price', 'platform_fee'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'plan_id' => Yii::t('app','Plan ID'),
            'name' => Yii::t('app','Name'),
            'description' => Yii::t('app','Description'),
            'price' => Yii::t('app','Price'),
            'platform_fee' => Yii::t('app','Platform Fee'),
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($this->price > 0)
        {
            PlanPrice::deleteAll(['plan_id' => $this->plan_id]);

            $currencies = \agent\models\Currency::find()->all();

            $kwdCurrency = \agent\models\Currency::find()
                ->andWhere(['code' => "KWD"])
                ->one();

            $planPriceUSD = $this->price / $kwdCurrency->rate;

            $data = [];

            foreach ($currencies as $currency)
            {
                $data[] = [
                    $this->plan_id,
                    $currency->code,
                    round($planPriceUSD * $currency->rate, $currency->decimal_place),
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                ];
            }

            Yii::$app->db->createCommand()->batchInsert('plan_price',
                ['plan_id', 'currency', 'price', "created_at", "updated_at"], $data)->execute();
        }
    }

    /**
     * Gets query for [[PlanPrices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanPrices($modelClass = "\common\models\PlanPrice")
    {
        return $this->hasMany($modelClass::className(), ['plan_id' => 'plan_id']);
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions($modelClass = "\common\models\Subscription")
    {
        return $this->hasMany($modelClass::className(), ['plan_id' => 'plan_id']);
    }
}
