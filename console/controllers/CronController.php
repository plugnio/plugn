<?php

namespace console\controllers;

use Yii;
use common\models\Restaurant;
use common\models\OrderItem;
use common\models\Voucher;
use common\models\BankDiscount;
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



    /**
     * Update refund status  for all refunds record
     */
    public function actionUpdateOrdersRecord() {
      Order::updateAll(['reminder_sent' => 1], 'reminder_sent = 0');

      $this->stdout("Thank you Big Boss! \n", Console::FG_RED, Console::BOLD);
      return self::EXIT_CODE_NORMAL;
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

    /**
     * Update voucher status
     */
    public function actionUpdateVoucherStatus() {

        $vouchers = Voucher::find()->all();

        foreach ($vouchers as $voucher) {
          if($voucher->valid_until && date('Y-m-d',strtotime('now')) >= date('Y-m-d',strtotime($voucher->valid_until))) {
            $voucher->voucher_status = Voucher::VOUCHER_STATUS_EXPIRED;
            $voucher->save();
          }
        }

        $bankDiscounts = BankDiscount::find()->all();


        foreach ($bankDiscounts as $bankDiscount) {
          if($bankDiscount->valid_until && date('Y-m-d',strtotime('now')) >= date('Y-m-d',strtotime($bankDiscount->valid_until))) {
            $bankDiscount->bank_discount_status = BankDiscount::BANK_DISCOUNT_STATUS_EXPIRED;
            $bankDiscount->save();
          }
        }
    }

    public function actionUpdateStockQty() {

        $now = new DateTime('now');
        $payments = Payment::find()
                ->joinWith('order')
                ->where(['!=', 'payment.payment_current_status', 'CAPTURED'])
                ->andWhere(['order.items_has_been_restocked' => 0]) // if items hasnt been restocked
                ->andWhere(['<', 'payment.payment_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 15 MINUTE)')]);

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
                ->andWhere(['<', 'payment_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 10 MINUTE)')])
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

    /**
     * Method called to Send  reminder if order not picked up in 10 minutes
     */
    public function actionSendReminderEmail() {

        $now = new DateTime('now');
        $orders = Order::find()
                ->where(['order_status' => Order::STATUS_PENDING])
                ->andWhere("reminder_sent = 0")
                ->andWhere(['<', 'order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 5 MINUTE)')])
                ->all();

        if ($orders) {

            foreach ($orders as $order) {

              foreach ($order->restaurant->getAgents()->where(['reminder_email' => 1])->all() as $agent) {

                  if ($agent) {
                    $result =  \Yii::$app->mailer->compose([
                                  'html' => 'order-reminder-html',
                                      ], [
                                  'order' => $order,
                                  'agent_name' => $agent->agent_name
                              ])
                              ->setFrom([\Yii::$app->params['supportEmail'] => $order->restaurant->name])
                              ->setTo($agent->agent_email)
                              ->setSubject('Order #' . $order->order_uuid . ' from ' . $order->restaurant->name)
                              ->setReplyTo([$order->restaurant->restaurant_email => $order->restaurant->name])
                              ->send();
                  }
              }

              $order->reminder_sent = 1;
              $order->save(false);
            }
        }

        $this->stdout("Reminder email has been sent \n", Console::FG_RED, Console::BOLD);
        return self::EXIT_CODE_NORMAL;
    }

}
