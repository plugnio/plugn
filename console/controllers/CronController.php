<?php

namespace console\controllers;

use Yii;
use common\models\Restaurant;
use common\models\OrderItem;
use common\models\Queue;
use common\models\TapQueue;
use common\models\AgentAssignment;
use common\models\Voucher;
use common\models\BankDiscount;
use common\models\Payment;
use common\models\Item;
use common\models\Customer;
use common\models\City;
use common\models\Plan;
use common\models\Area;
use common\models\Order;
use common\models\Subscription;
use common\models\OpeningHour;
use common\models\CountryPaymentMethod;
use common\models\Country;
use common\models\Agent;
use common\models\ExtraOption;
use common\models\ItemImage;
use common\models\AreaDeliveryZone;
use common\models\DeliveryZone;
use common\models\RestaurantTheme;
use common\models\BusinessLocation;
use common\models\SubscriptionPayment;
use common\models\RestaurantBranch;
use \DateTime;
use yii\helpers\Console;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

/**
 * All Cron actions related to this project
 */
class CronController extends \yii\console\Controller {

  /**
   *
   */
  public function actionMigration(){
    $agents = Agent::find()->all();
    foreach ($agents as $key => $agent) {
      if($agentAssignments = $agent->getAgentAssignments()->all()){
        foreach ($agentAssignments as  $agentAssignment) {
          $agentAssignment->email_notification = $agent->email_notification;
          $agentAssignment->reminder_email = $agent->reminder_email;
          $agentAssignment->receive_weekly_stats = $agent->receive_weekly_stats;
          $agentAssignment->save();
        }
      }
    }

    $this->stdout("Thank you Big Boss \n", Console::FG_RED, Console::NORMAL);
    return self::EXIT_CODE_NORMAL;
  }

    /**
     * Weekly Store Summary
     */
    public function actionWeeklyReport(){

        $stores = Restaurant::find()
                ->all();


          foreach ($stores as $key => $store) {


          //Revenue generated
          $lastWeekRevenue =  $store
              ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"),  date("d") - 14 )) , date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"),  date("d") -8)) );

          $thisWeekRevenue =  $store
              ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"),  date("d") - 7 )), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"),  date("d"))) );

          //Orders received
          $lastWeekOrdersReceived =  $store
              ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"),  date("d") - 14 )) , date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"),  date("d") -8)) );

          $thisWeekOrdersReceived =  $store
              ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"),  date("d") - 7 )), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"),  date("d"))) );

          //customer gained
          $lastWeekCustomerGained =  $store
              ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"),  date("d") - 14 )) , date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"),  date("d") -8)) );

          $thisWeekCustomerGained =  $store
              ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"),  date("d") - 7 )), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"),  date("d"))) );

          // Revenue Generated
          $revenuePercentage = 0;


          if($thisWeekRevenue > $lastWeekRevenue) { //inc
               if($lastWeekRevenue > 0){
                 $increase = $thisWeekRevenue - $lastWeekRevenue;

                 $revenuePercentage = $increase / $lastWeekRevenue * 100;
               } else {
                 $revenuePercentage = 100;
               }

             }
             else if($thisWeekRevenue < $lastWeekRevenue) { //dec
               $decrease = $lastWeekRevenue - $thisWeekRevenue;
               $revenuePercentage = $decrease / $lastWeekRevenue * -100;
             }

          // Orders received
          $ordersReceivedPercentage = 0;


          if($thisWeekOrdersReceived > $lastWeekOrdersReceived) { //inc
               if($lastWeekOrdersReceived > 0){
                 $increase = $thisWeekOrdersReceived - $lastWeekOrdersReceived;

                 $ordersReceivedPercentage = $increase / $lastWeekOrdersReceived * 100;
               } else {
                 $ordersReceivedPercentage = 100;
               }

             }
             else if($thisWeekOrdersReceived < $lastWeekOrdersReceived) { //dec
               $decrease = $lastWeekOrdersReceived - $thisWeekOrdersReceived;
               $ordersReceivedPercentage = $decrease / $lastWeekOrdersReceived * -100;
             }


             //Customer gained
             $customerGainedPercentage = 0;

             if($thisWeekCustomerGained > $lastWeekCustomerGained) { // inc
                  if($lastWeekCustomerGained > 0){
                    $increase = $thisWeekCustomerGained - $lastWeekCustomerGained;

                    $customerGainedPercentage = $increase / $lastWeekCustomerGained * 100;
                  } else {
                    $customerGainedPercentage = 100;
                  }

                }
                else if($thisWeekCustomerGained < $lastWeekCustomerGained) { //dec
                  $decrease = $lastWeekCustomerGained - $thisWeekCustomerGained;
                  $customerGainedPercentage = $decrease / $lastWeekCustomerGained * -100;
                }


                if($lastWeekOrdersReceived > 0 || $thisWeekOrdersReceived > 0) {

                  $agentAssignments = $store->getAgentAssignments()
                              ->where([
                                          'role' => AgentAssignment::AGENT_ROLE_OWNER,
                                          'receive_weekly_stats' => 1
                                      ])
                              ->all();

                  foreach ($agentAssignments as $agentAssignment) {

                    if($agentAssignment->receive_weekly_stats){
                      \Yii::$app->mailer->compose([
                             'html' => 'weekly-summary',
                                 ], [
                             'store' => $store,
                             'agent_name' => $agentAssignment->agent->agent_name,
                             'revenuePercentage' => $revenuePercentage,
                             'ordersReceivedPercentage' => $ordersReceivedPercentage,
                             'customerGainedPercentage' => $customerGainedPercentage,
                             'thisWeekRevenue' => $thisWeekRevenue,
                             'thisWeekOrdersReceived' => $thisWeekOrdersReceived,
                             'thisWeekCustomerGained' => $thisWeekCustomerGained,

                         ])
                         ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
                         ->setTo([$agentAssignment->agent->agent_email])
                         ->setSubject('Weekly Store Summary')
                         ->setBcc(\Yii::$app->params['supportEmail'])
                         ->send();
                    }

                  }

                }

        }

    }




    public function actionSiteStatus(){

            $restaurants = Restaurant::find()
                          ->where(['has_deployed' => 0])
                          ->all();

            foreach ($restaurants as $restaurant) {

              if($restaurant->site_id){

                $getSiteResponse = Yii::$app->netlifyComponent->getSiteData($restaurant->site_id);

                if ($getSiteResponse->isOk) {
                  if($getSiteResponse->data['state'] == 'current'){
                    $restaurant->has_deployed = 1;
                    $restaurant->save(false);

                    \Yii::$app->mailer->compose([
                           'html' => 'store-ready',
                               ], [
                           'store' => $restaurant,
                       ])
                       ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
                       ->setTo([$restaurant->restaurant_email])
                       ->setBcc(\Yii::$app->params['supportEmail'])
                       ->setSubject('Your store ' . $restaurant->name .' is now ready')
                       ->send();

                  }

                }

              }

            }

            $this->stdout("Thank you Big Boss \n", Console::FG_RED, Console::NORMAL);
            return self::EXIT_CODE_NORMAL;
        }


    /**
     * Anything we can help with?
     * Once either when 2 days passed no products added
     * OR 5 days passed and no sales
     */
    public function actionRetentionEmailsWhoPassedTwoDaysAndNoProducts(){

      $stores = Restaurant::find()
              ->joinWith(['items','ownerAgent'])
              ->where(' DATE(restaurant_created_at) = DATE(NOW() - INTERVAL 2 DAY) ')
              ->andWhere(['retention_email_sent' => 0])
              ->all();



      foreach ($stores as $key => $store) {

        if(sizeof($store->items) == 0 ){

          foreach ($store->ownerAgent as $agent) {

            Yii::$app->mailer->compose([
                        'html' => 'offer-assistance',
                            ], [
                        'store' => $store
                    ])
                     ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
                    ->setTo($agent->agent_email)
                    ->setBcc(\Yii::$app->params['supportEmail'])
                    ->setSubject('Is there anything we can help with?')
                    ->send();
          }

          $store->retention_email_sent = 1;
          $store->save(false);

        }

      }

    }


    public function actionRetentionEmailsWhoPassedFiveDaysAndNoSales(){

      $stores = Restaurant::find()
              ->joinWith(['orders','ownerAgent'])
              ->where(' DATE(restaurant_created_at) = DATE(NOW() - INTERVAL 5 DAY) ')
              ->andWhere(['retention_email_sent' => 0])
              ->all();


      foreach ($stores as $key => $store) {

        if(sizeof($store->orders) == 0 ){

          foreach ($store->ownerAgent as $agent) {

            Yii::$app->mailer->compose([
                        'html' => 'offer-assistance',
                            ], [
                        'store' => $store
                    ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
                    ->setTo($agent->agent_email)
                    ->setBcc(\Yii::$app->params['supportEmail'])
                    ->setSubject('Is there anything we can help with?')
                    ->send();
          }

          $store->retention_email_sent = 1;
          $store->save(false);

        }

      }

    }


    public function actionDowngradedStoreSubscription(){

         $start_date = date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"),  date("d")  ));
         $end_date =  date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"),  date("d") ));

         $subscriptions = Subscription::find()
                 ->where(['subscription_status' => Subscription::STATUS_ACTIVE])
                 // ->andWhere(['notified_email' => 1])
                 ->andWhere(['not', ['subscription_end_at' => null]])
                 ->andWhere(['between', 'subscription_end_at', $start_date, $end_date])
                 ->with(['plan','restaurant'])
                 ->all();



         foreach ($subscriptions as $subscription) {
           if(date('Y-m-d',strtotime($subscription->subscription_end_at)) == date('Y-m-d')){
             $subscription->subscription_status =  Subscription::STATUS_INACTIVE;
             $subscription->save();
           }
         }
       }

       public function actionNotifyAgentsForSubscriptionThatWillExpireSoon(){

         $start_date = date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"),  date("d") + 5 ));
         $end_date =  date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"),  date("d") + 5));

         $subscriptions = Subscription::find()
                 ->where(['subscription_status' => Subscription::STATUS_ACTIVE])
                 ->andWhere(['notified_email' => 0])
                 ->andWhere(['not', ['subscription_end_at' => null]])
                 ->andWhere(['between', 'subscription_end_at', $start_date, $end_date])
                 ->with(['plan','restaurant'])
                 ->all();


         foreach ($subscriptions as $subscription) {

           foreach ($subscription->restaurant->getOwnerAgent()->all() as $agent ) {
             $result = \Yii::$app->mailer->compose([
                         'html' => 'subscription-will-expire-soon-html',
                             ], [
                         'subscription' => $subscription,
                         'store' => $subscription->restaurant,
                         'plan' => $subscription->plan->name,
                         'agent_name' => $agent->agent_name,
                     ])
                     ->setFrom([\Yii::$app->params['supportEmail']])
                     ->setTo($agent->agent_email)
                     ->setSubject('Your Subscription is Expiring')
                     ->send();

               if($result){
                 $subscription->notified_email = 1;
                 $subscription->save(false);
               }
           }
         }

         $this->stdout("Email sent to all agents of employer that have applicants will expire soon \n", Console::FG_RED, Console::NORMAL);
         return self::EXIT_CODE_NORMAL;

    }

    public function actionCreateTapAccount() {

      $queue = TapQueue::find()
              ->where(['queue_status' => Queue::QUEUE_STATUS_PENDING])
              ->orderBy(['queue_created_at' => SORT_ASC])
              ->one();

      if($queue && $queue->restaurant_uuid){
        $queue->queue_status = TapQueue::QUEUE_STATUS_CREATING;
        $queue->save();
      }

    }


    public function actionCreateBuildJsFile() {

            $queue = Queue::find()
                    ->joinWith('restaurant')
                    ->andWhere(['queue_status' => Queue::QUEUE_STATUS_PENDING])
                    ->orderBy(['queue_created_at' => SORT_ASC])
                    ->one();

            if($queue && $queue->restaurant_uuid){
              $queue->queue_status = Queue::QUEUE_STATUS_CREATING;
              $queue->save();
            }

        $this->stdout("File has been created! \n", Console::FG_RED, Console::BOLD);

    }

        public function actionUpdateSitemap() {

          $stores = Restaurant::find()
                  ->where(['sitemap_require_update' => 1])
                  ->andWhere(['version' => 2])
                  ->andWhere(['!=', 'restaurant_uuid', 'rest_00f54a5e-7c35-11ea-997e-4a682ca4b290'])
                  ->all();


            if($stores){
              foreach ($stores as $key => $store) {

                if($store && $store->getItems()->count() > 0){

                  $dirName = "store";
                  if(!file_exists($dirName))
                    $createStoreFolder = mkdir($dirName);

                  if (!file_exists( $dirName . "/" . $store->store_branch_name )) {
                    $myFolder = mkdir( $dirName . "/" . $store->store_branch_name);
                  }

                $sitemap =  fopen($dirName . "/" .   $store->store_branch_name . "/sitemap.xml", "w") or die("Unable to open file!");

                fwrite($sitemap, Yii::$app->fileGeneratorComponent->createSitemapXml($store->restaurant_uuid));
                fclose($sitemap);


                //Create sitemap.xml file
                $fileToBeUploaded = file_get_contents("store/" . $store->store_branch_name . "/sitemap.xml");

                // Encode the image string data into base64
                $data = base64_encode($fileToBeUploaded);

                $getSitemapXmlSHA = Yii::$app->githubComponent->getFileSHA('src/sitemap.xml', $store->store_branch_name,);

                if ($getSitemapXmlSHA->isOk && $getSitemapXmlSHA->data) {

                    //Replace test with store branch name
                    $commitSitemapXmlFileResponse = Yii::$app->githubComponent->createFileContent($data, $store->store_branch_name, 'src/sitemap.xml', 'Update sitemap', $getSitemapXmlSHA->data['sha']);

                    if ($commitSitemapXmlFileResponse->isOk) {


                      $store->sitemap_require_update = 0;
                      $store->save(false);

                      //Delete sitemap file
                      $dirPath = "store/" .  $store->store_branch_name;
                      $file_pointer =  $dirPath . '/sitemap.xml';

                      // Use unlink() function to delete a file
                      if (!unlink($file_pointer)) {
                          Yii::error("$file_pointer cannot be deleted due to an error", __METHOD__);
                      } else {
                          if (!rmdir($dirPath)) {
                              Yii::error("Could not remove $dirPath", __METHOD__);
                          }
                      }

                    } else {
                      Yii::error('[Github > Commit Sitemap xml]' . json_encode($commitSitemapXmlFileResponse->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
                      return false;
                    }


                } else {
                  Yii::error('[Github > Error while getting file sha]' . json_encode($getSitemapXmlSHA->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
                  return false;
                }

              }
            }
          }

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
            if ($voucher->valid_until && date('Y-m-d', strtotime('now')) >= date('Y-m-d', strtotime($voucher->valid_until))) {
                $voucher->voucher_status = Voucher::VOUCHER_STATUS_EXPIRED;
                $voucher->save();
            }
        }

        $bankDiscounts = BankDiscount::find()->all();


        foreach ($bankDiscounts as $bankDiscount) {
            if ($bankDiscount->valid_until && date('Y-m-d', strtotime('now')) >= date('Y-m-d', strtotime($bankDiscount->valid_until))) {
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
                ->andWhere(['order.order_status' => Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['order.items_has_been_restocked' => 0]) // if items hasnt been restocked
                ->andWhere(['<', 'payment.payment_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 10 MINUTE)')]);

        foreach ($payments->all() as $payment) {
            $payment->order->restockItems();
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
                  \Yii::error("[Issue checking status (". $payment->restaurant_uuid .") Order Uuid: ". $payment->order_uuid ."] " . $e->getMessage(), __METHOD__);
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
     * Method called to Send  reminder if order not picked up in 5 minutes
     */
    public function actionSendReminderEmail() {

        $now = new DateTime('now');
        $orders = Order::find()
                ->where(['order_status' => Order::STATUS_PENDING])
                ->andWhere(['reminder_sent' => 0])
                ->andWhere(['<', 'order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 5 MINUTE)')])
                ->all();


        if ($orders) {

            foreach ($orders as $order) {

                foreach ($order->restaurant->getAgentAssignments()->where(['reminder_email' => 1])->all() as $agentAssignment) {


                    if ($agentAssignment && $agentAssignment->agent) {
                        $result = \Yii::$app->mailer->compose([
                                    'html' => 'order-reminder-html',
                                        ], [
                                    'order' => $order,
                                    'agent_name' => $agentAssignment->agent->agent_name
                                ])
                                ->setFrom([\Yii::$app->params['supportEmail'] => $order->restaurant->name])
                                ->setTo($agentAssignment->agent->agent_email)
                                ->setSubject('Order #' . $order->order_uuid . ' from ' . $order->restaurant->name)
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
