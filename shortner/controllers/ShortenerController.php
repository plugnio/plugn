<?php
namespace shortner\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use common\models\Order;

/**
 * Shortener controller
 * For shorten and redirect to payment link
 */
class ShortenerController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionRedirect($orderId)
    {
        $model = Order::findOne($orderId);

        if($model)
          $this->redirect($model->restaurant->restaurant_domain . '/order-status/' . $orderId);
        else
          $this->redirect('https://www.plugn.io');
    }

}
