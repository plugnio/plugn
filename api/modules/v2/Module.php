<?php

namespace api\modules\v2;

use common\models\BlockedIp;
use common\models\Restaurant;
use Yii;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

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

        header('Access-Control-Allow-Origin: *');

        /*$store_id = Yii::$app->request->getHeaders()->get('Store-Id');

        if($store_id) {

            $store = Restaurant::findOne($store_id);

            if (!$store) {
                \Yii::$app->getResponse()->setStatusCode(404);
            }
        }

        if($store && $store->enable_debugger)
        {
            $component = \Yii::$app->getModule('debug');

            //$component->allowedIPs = Yii::$app->request->userIP;

            $component->bootstrap(\Yii::$app);

            \Yii::$app->getResponse()->on(Response::EVENT_AFTER_PREPARE, [$component, 'setDebugHeaders']);
        }*/

        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $identity = Yii::$app->user->loginByAccessToken($matches[1]);

            if(!$identity) {

                //throw new UnauthorizedHttpException("Invalid token");

            }
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

        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            return true;
        }

        // Get initial IP address of requester
        $ip = Yii::$app->request->getRemoteIP();

        // Check if request is forwarded via load balancer or cloudfront on behalf of user
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'];

            // as "X-Forwarded-For" is usually a list of IP addresses that have routed
            $IParray = array_values(array_filter(explode(',', $forwardedFor)));

            // Get the first ip from forwarded array to get original requester
            $ip = $IParray[0];
        }

        //check if ip is blocked

        $isBlocked = BlockedIp::find()->andWhere(['ip_address' => $ip])->exists();

        if($isBlocked) {
            throw new \yii\web\HttpException(403, 'ILLEGAL USAGE');
        }
    }
}
