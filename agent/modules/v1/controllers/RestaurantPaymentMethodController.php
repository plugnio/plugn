<?php

namespace agent\modules\v1\controllers;

use agent\models\Payment;
use agent\models\PaymentMethod;
use agent\models\RestaurantPaymentMethod;
use Yii;
use common\components\TapPayments;
use agent\models\Plan;
use agent\models\Subscription;
use agent\models\SubscriptionPayment;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Cookie;


class RestaurantPaymentMethodController extends BaseController
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'callback'];

        return $behaviors;
    }

    /**
     * only owner will have access
     */
    public function beforeAction($action)
    {
        parent::beforeAction ($action);

        if($action->id == 'options') {
            return true;
        }

        if(!Yii::$app->accountManager->isOwner() && !in_array ($action->id, ['view'])) {
            throw new \yii\web\BadRequestHttpException(
                Yii::t('agent', 'You are not allowed to manage plan. Please contact with store owner')
            );

            return false;
        }

        //should have access to store

        Yii::$app->accountManager->getManagedAccount();

        return true;
    }

    /**
     * return plan detail
     * @param $id
     * @return Plan|null
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        return RestaurantPaymentMethod::findAll(['restaurant_uuid' => $store->restaurant_uuid]);
    }

    /**
     * return all payment method
     * @param $id
     * @return Plan|null
     * @throws NotFoundHttpException
     */
    public function actionListAll()
    {
        return PaymentMethod::find()->all();
    }
    
    /**
     * Finds the Plan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RestaurantPaymentMethod::findOne ($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
