<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "payment_method_currency".
 *
 * @property int $pmc_id
 * @property int $payment_method_id
 * @property string $currency
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property PaymentMethod $paymentMethod
 */
class PaymentMethodCurrency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_method_currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_method_id', 'currency'], 'required'],
            [['payment_method_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['currency'], 'string', 'max' => 3],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'payment_method_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pmc_id' => Yii::t('app', 'Payment Method Currency'),
            'payment_method_id' => Yii::t('app', 'Payment Method ID'),
            'currency' => Yii::t('app', 'Currency'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod($modelClass = "\common\models\PaymentMethod")
    {
        return $this->hasOne($modelClass::className(), ['payment_method_id' => 'payment_method_id']);
    }
}
