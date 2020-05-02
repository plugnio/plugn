<?php

namespace console\controllers;

use Yii;
use common\models\Restaurant;
use common\models\Payment;
use \DateTime;
use yii\helpers\Console;
use yii\db\Expression;

/**
 * All Cron actions related to this project
 */
class CronController extends \yii\console\Controller {
public function actionIndex(){
           $operatorApiResponse = Yii::$app->tapPayments->createAnOperator('burgera', 'wal_uATwK14201421HM1z2of4D626', 'dev_hGTwK2201421ovzQ2ck4Q465');

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
                    $payment = Payment::updatePaymentStatusFromTap($payment->payment_gateway_transaction_id);
                    $payment->received_callback = true;
                    $payment->save(false);
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
