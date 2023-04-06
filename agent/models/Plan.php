<?php


namespace agent\models;
 

class Plan extends \common\models\Plan
{
    public function extraFields()
    {
        $fields = parent::extraFields ();

        $fields['paymentMethods'] =  function ($model) {

            //todo: payment method based on currency

            return PaymentMethod::find ()
                ->andWhere (['in', 'payment_method_id', ['1', '2']])
                ->all ();
        };

        $fields['formatedPrice'] = function ($model) {

            //$store = \Yii::$app->accountManager->getManagedAccount();
            //$store->currency->code

            return \Yii::$app->formatter->asCurrency($model->price, 'KWD');
        };

        return $fields;
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions($modelClass = "\agent\models\Subscription")
    {
        return parent::getSubscriptions($modelClass);
    }
}