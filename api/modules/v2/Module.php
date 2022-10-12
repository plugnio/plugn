<?php

namespace api\modules\v2;

use common\models\Restaurant;
use Yii;
use yii\web\Response;

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

        $store_id = Yii::$app->request->getHeaders()->get('Store-Id');

        $store = Restaurant::findOne($store_id);

        if($store && $store->enable_debugger)
        {
            $component = \Yii::$app->getModule('debug');

            $component->bootstrap(\Yii::$app);

            \Yii::$app->getResponse()->on(Response::EVENT_AFTER_PREPARE, [$component, 'setDebugHeaders']);
        }

        $lang = \Yii::$app->request->headers->get('language');

        $currency = \Yii::$app->request->headers->get('currency');

        if ($lang && $lang != \Yii::$app->language)
        {
            \Yii::$app->language = $lang;
        }

        if ($currency)//&& $currency != \Yii::$app->currency->getCode()
        {
            \Yii::$app->currency->setCode($currency);
        }
    }
}
