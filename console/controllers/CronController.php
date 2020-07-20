<?php

namespace console\controllers;

use Yii;
use common\models\Restaurant;
use common\models\OrderItem;
use common\models\Payment;
use common\models\Item;
use common\models\Order;
use common\models\OpeningHour;
use common\models\ItemImage;
use \DateTime;
use yii\helpers\Console;
use yii\db\Expression;

/**
 * All Cron actions related to this project
 */
class CronController extends \yii\console\Controller {

    public function actionIndex() {
        $restaurants = Restaurant::find();

        OpeningHour::deleteAll();

        foreach ($restaurants->all() as $restaurant) {

            for ($i = 0; $i < 7; ++$i) {
                $opening_hour = new OpeningHour();
                $opening_hour->restaurant_uuid = $restaurant->restaurant_uuid;
                $opening_hour->day_of_week = $i;
                $opening_hour->open_at = 0;
                $opening_hour->is_closed = 0;
                $opening_hour->close_at = '23:59:59';
                $opening_hour->save();
            }
        }


        $this->stdout("Thanks Big Boss \n", Console::FG_RED, Console::BOLD);


    }

    /**
     * Update refund status  for all refunds record
     */
    public function actionUpdateRefundStatusMessage() {

        $restaurants = Restaurant::find()->all();
        foreach ($restaurants as $restaurant) {

            foreach ($restaurant->getRefunds()->all() as $refund) {

                Yii::$app->tapPayments->setApiKeys($restaurant->live_api_key, $restaurant->test_api_key);
                $response = Yii::$app->tapPayments->retrieveRefund($refund->refund_id);

                if (!array_key_exists('errors', $response->data)) {
                    if ($refund->refund_status != $response->data['status']) {
                        $refund->refund_status = $response->data['status'];
                        $refund->save(false);
                    }
                }
            }
        }
    }

    public function actionUpdateStockQty() {

        $now = new DateTime('now');
        $payments = Payment::find()
                ->joinWith('order')
                ->where(['!=', 'payment.payment_current_status', 'CAPTURED'])
                ->andWhere(['order.items_has_been_restocked' => 0]) // if items hasnt been restocked
                ->andWhere(['<', 'payment.payment_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 10 MINUTE)')]);

        foreach ($payments->all() as $payment) {
            $payment->order->restockAllItems();
        }
    }

    /**
     * Method called to find old transactions that haven't received callback and force a callback
     */
    public function actionUpdateTransactions() {

        $now = new DateTime('now');
        $payments = Payment::find()
                ->where("received_callback = 0")
                ->andWhere(['<', 'payment_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 5 MINUTE)')])
                ->all();

        if ($payments) {
            foreach ($payments as $payment) {
                try {
                    if ($payment->payment_gateway_transaction_id) {
                        $payment = Payment::updatePaymentStatusFromTap($payment->payment_gateway_transaction_id);
                        $payment->received_callback = true;
                        $payment->save(false);
                    }
                } catch (\Exception $e) {
                    \Yii::error("[Issue checking status] " . $e->getMessage(), __METHOD__);
                }
            }
        } else {
            $this->stdout("All Payments received callback \n", Console::FG_RED, Console::BOLD);
            return self::EXIT_CODE_NORMAL;
        }

        $this->stdout("Payments status updated successfully \n", Console::FG_RED, Console::BOLD);
        return self::EXIT_CODE_NORMAL;
    }

}
