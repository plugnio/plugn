<?php

namespace api\modules\v2;

/**
 * v2 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'api\modules\v2\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $lang = \Yii::$app->request->headers->get('language');
        $currency = \Yii::$app->request->headers->get('currency');

        if ($lang && $lang != \Yii::$app->language)
        {
            \Yii::$app->language = $lang;
        }

        if ($currency && $currency != \Yii::$app->currency)
        {
            \Yii::$app->currency = $currency;
        }
    }
}
