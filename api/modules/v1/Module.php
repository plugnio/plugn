<?php

namespace api\modules\v1;

use common\models\BlockedIp;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'api\modules\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();


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
            header('Access-Control-Allow-Origin: *');
            throw new \yii\web\HttpException(403, 'ILLEGAL USAGE');
        }
    }

}
