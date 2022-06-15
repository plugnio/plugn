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
