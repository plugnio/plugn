<?php

namespace agent\modules\v1;

use Yii;
use common\models\Agent;
use common\models\Restaurant;
use yii\db\Expression;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'agent\modules\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $lang = \Yii::$app->request->headers->get('language');

        if ($lang && $lang != \Yii::$app->language)
        {
            \Yii::$app->language = $lang;
        }

        $restaurantUuid = Yii::$app->request->headers->get('Store-Id');

        if($restaurantUuid)
        {
            Restaurant::updateAll(['last_active_at' => new Expression('NOW()')], [
                'restaurant_uuid' => $restaurantUuid
            ]);
        }
    }
}
