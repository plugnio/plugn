<?php

namespace console\controllers;

use common\models\Currency;
use common\models\VendorCampaign;
use Yii;
use common\models\Restaurant;
use common\models\OrderItem;
use common\models\Queue;
use common\models\TapQueue;
use common\models\AgentAssignment;
use common\models\PaymentGatewayQueue;
use common\models\Voucher;
use common\models\BankDiscount;
use common\models\Payment;
use common\models\Item;
use common\models\Customer;
use common\models\City;
use common\models\PaymentMethod;
use common\models\Refund;
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
class CronController extends \yii\console\Controller
{
    public function actionIndex() {
    }

    /**
     * Weekly Store Summary
     */
    public function actionWeeklyReport()
    {

        $stores = Restaurant::find()
            ->all();

        foreach ($stores as $key => $store) {

            //Revenue generated
            $lastWeekRevenue = $store
                ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 14)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 8)));

            $thisWeekRevenue = $store
                ->getStoreRevenue(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 7)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d"))));

            //Orders received
            $lastWeekOrdersReceived = $store
                ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 14)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 8)));

            $thisWeekOrdersReceived = $store
                ->getOrdersReceived(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 7)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d"))));

            //customer gained
            $lastWeekCustomerGained = $store
                ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 14)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - 8)));

            $thisWeekCustomerGained = $store
                ->getCustomerGained(date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") - 7)), date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d"))));

            // Revenue Generated
            $revenuePercentage = 0;

            if ($lastWeekRevenue > 0) {
                if ($thisWeekRevenue > $lastWeekRevenue) { //inc
                    if ($lastWeekRevenue > 0) {
                        $increase = $thisWeekRevenue - $lastWeekRevenue;

                        $revenuePercentage = $increase / $lastWeekRevenue * 100;
                    } else {
                        $revenuePercentage = 100;
                    }

                } else if ($thisWeekRevenue < $lastWeekRevenue) { //dec
                    $decrease = $lastWeekRevenue - $thisWeekRevenue;
                    $revenuePercentage = $decrease / $lastWeekRevenue * -100;
                }
            }

            // Orders received
            $ordersReceivedPercentage = 0;


            if ($thisWeekOrdersReceived > $lastWeekOrdersReceived) { //inc
                if ($lastWeekOrdersReceived > 0) {
                    $increase = $thisWeekOrdersReceived - $lastWeekOrdersReceived;

                    $ordersReceivedPercentage = $increase / $lastWeekOrdersReceived * 100;
                } else {
                    $ordersReceivedPercentage = 100;
                }

            } else if ($thisWeekOrdersReceived < $lastWeekOrdersReceived) { //dec
                $decrease = $lastWeekOrdersReceived - $thisWeekOrdersReceived;
                $ordersReceivedPercentage = $decrease / $lastWeekOrdersReceived * -100;
            }


            //Customer gained
            $customerGainedPercentage = 0;

            if ($thisWeekCustomerGained > $lastWeekCustomerGained) { // inc
                if ($lastWeekCustomerGained > 0) {
                    $increase = $thisWeekCustomerGained - $lastWeekCustomerGained;

                    $customerGainedPercentage = $increase / $lastWeekCustomerGained * 100;
                } else {
                    $customerGainedPercentage = 100;
                }

            } else if ($thisWeekCustomerGained < $lastWeekCustomerGained) { //dec
                $decrease = $lastWeekCustomerGained - $thisWeekCustomerGained;
                $customerGainedPercentage = $decrease / $lastWeekCustomerGained * -100;
            }

            if ($lastWeekOrdersReceived > 0 || $thisWeekOrdersReceived > 0) {

                $agentAssignments = $store->getAgentAssignments()
                    ->andWhere([
                        'role' => AgentAssignment::AGENT_ROLE_OWNER,
                        'receive_weekly_stats' => 1
                    ])
                    ->all();

                foreach ($agentAssignments as $key => $agentAssignment) {

                    if ($agentAssignment->receive_weekly_stats) {

                        $weeklyStoreSummaryEmail = \Yii::$app->mailer->compose([
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
                            ->setSubject('Weekly Store Summary');

                        if ($key == 0)
                            $weeklyStoreSummaryEmail->setBcc(\Yii::$app->params['supportEmail']);

                        $weeklyStoreSummaryEmail->send();

                    }
                }
            }
        }
    }

    public function actionSiteStatus()
    {
        $restaurants = Restaurant::find()
            ->andWhere(['has_deployed' => 0])
            ->all();

        foreach ($restaurants as $restaurant) {

            if ($restaurant->site_id && $restaurant->restaurant_email) {

                $getSiteResponse = Yii::$app->netlifyComponent->getSiteData($restaurant->site_id);

                if ($getSiteResponse->isOk) {
                    if ($getSiteResponse->data['state'] == 'current') {
                        $restaurant->has_deployed = 1;
                        $restaurant->save(false);

                        \Yii::$app->mailer->compose([
                            'html' => 'store-ready',
                        ], [
                            'store' => $restaurant,
                        ])
                            ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
                            ->setTo([$restaurant->restaurant_email])
                            ->setSubject('Your store ' . $restaurant->name . ' is now ready')
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
    public function actionRetentionEmailsWhoPassedTwoDaysAndNoProducts()
    {
        //todo: why processing all stores, when need only with no orders

        $stores = Restaurant::find()
            ->joinWith(['items', 'ownerAgent'])
            ->andWhere(' DATE(restaurant_created_at) = DATE(NOW() - INTERVAL 2 DAY) ')
            ->andWhere(['retention_email_sent' => 0])
            ->all();

        foreach ($stores as $key => $store) {

            $count = $store->getItems()->count();

            if ($count > 0) {
                continue;
            }

            foreach ($store->ownerAgent as $agent) {

                Yii::$app->mailer->compose([
                    'html' => 'offer-assistance',
                ], [
                    'store' => $store
                ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
                    ->setTo($agent->agent_email)
                    ->setSubject('Is there anything we can help with?')
                    ->send();
            }

            $store->retention_email_sent = 1;
            $store->save(false);
        }
    }


    public function actionRetentionEmailsWhoPassedFiveDaysAndNoSales()
    {
        //todo: why processing all stores, when need only with no orders

        $stores = Restaurant::find()
            ->joinWith(['orders', 'ownerAgent'])
            ->andWhere(' DATE(restaurant_created_at) = DATE(NOW() - INTERVAL 5 DAY) ')
            ->andWhere(['retention_email_sent' => 0])
            ->all();


        foreach ($stores as $key => $store) {

            $count = $store->getOrders()->count();

            if ($count > 0) {
                continue;
            }

            foreach ($store->ownerAgent as $agent) {

                Yii::$app->mailer->compose([
                    'html' => 'offer-assistance',
                ], [
                    'store' => $store
                ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
                    ->setTo($agent->agent_email)
                    ->setSubject('Is there anything we can help with?')
                    ->send();
            }

            $store->retention_email_sent = 1;
            $store->save(false);
        }
    }


    public function actionDowngradedStoreSubscription()
    {
        $start_date = date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d")));
        $end_date = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d")));

        $subscriptions = Subscription::find()
            ->andWhere(['subscription_status' => Subscription::STATUS_ACTIVE])
            // ->andWhere(['notified_email' => 1])
            ->andWhere(['not', ['subscription_end_at' => null]])
            ->andWhere(['between', 'subscription_end_at', $start_date, $end_date])//todo: try DATE mysql function
            ->with(['plan', 'restaurant'])
            ->all();

        foreach ($subscriptions as $subscription) {

            if (date('Y-m-d', strtotime($subscription->subscription_end_at)) == date('Y-m-d')) {

                foreach ($subscription->restaurant->getOwnerAgent()->all() as $agent) {

                    $result = \Yii::$app->mailer->compose([
                        'html' => 'subscription-expired',
                    ], [
                        'subscription' => $subscription,
                        'store' => $subscription->restaurant,
                        'plan' => $subscription->plan->name,
                        'agent_name' => $agent->agent_name,
                    ])
                        ->setFrom([\Yii::$app->params['supportEmail']])
                        ->setTo($agent->agent_email)
                        ->setBcc(\Yii::$app->params['supportEmail'])
                        ->setSubject($subscription->restaurant->name . ' has been downgraded to our free plan')
                        ->send();

                    if (!$result)
                        Yii::error('[Error while sending email]' . json_encode($result), __METHOD__);

                }


                $subscription->subscription_status = Subscription::STATUS_INACTIVE;
                $subscription->save();
            }
        }
    }

    public function actionNotifyAgentsForSubscriptionThatWillExpireSoon()
    {
        $start_date = date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") + 5));
        $end_date = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") + 5));

        $subscriptions = Subscription::find()
            ->andWhere(['subscription_status' => Subscription::STATUS_ACTIVE])
            ->andWhere(['notified_email' => 0])
            ->andWhere(['not', ['subscription_end_at' => null]])
            ->andWhere(['between', 'subscription_end_at', $start_date, $end_date])
            ->with(['plan', 'restaurant'])
            ->all();

        foreach ($subscriptions as $subscription) {

            foreach ($subscription->restaurant->getOwnerAgent()->all() as $agent) {
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
                    ->setBcc(\Yii::$app->params['supportEmail'])
                    ->setSubject('Your Subscription is Expiring')
                    ->send();

                if ($result) {
                    $subscription->notified_email = 1;
                    $subscription->save(false);
                }
            }
        }

        $this->stdout("Email sent to all agents of employer that have applicants will expire soon \n", Console::FG_RED, Console::NORMAL);
        return self::EXIT_CODE_NORMAL;

    }

    public function actionTest() {
        $tap = PaymentGatewayQueue::find()->offset(1)->one();

        $tap->enableGateways();

    }

    /**
     * todo: why cron? when can just proccess form submit
     */
    public function actionCreatePaymentGatewayAccount()
    {
        $queue = PaymentGatewayQueue::find()
            ->where(['IN', 'queue_status', [
                PaymentGatewayQueue::QUEUE_STATUS_PENDING,
               // PaymentGatewayQueue::QUEUE_STATUS_CREATING,
               // PaymentGatewayQueue::QUEUE_STATUS_FAILED
            ]])
            ->orderBy(['queue_created_at' => SORT_ASC])
            ->one();

        if ($queue && $queue->restaurant_uuid)
        {
            $queue->processQueue();
            $queue->save();
        }
    }

    public function actionCreateBuildJsFile()
    {
        $queue = Queue::find()
            ->joinWith('restaurant')
            ->andWhere(['queue_status' => Queue::QUEUE_STATUS_PENDING])
            ->orderBy(['queue_created_at' => SORT_ASC])
            ->one();

        if ($queue && $queue->restaurant_uuid)
        {
            $this->stdout("File is creating for ".$queue->restaurant_uuid."! \n", Console::FG_RED, Console::BOLD);

            $queue->queue_status = Queue::QUEUE_STATUS_CREATING;

            if (!$queue->save()) {

                Yii::error('[Netlify > While Creating new site]' . json_encode($queue->getErrors()), __METHOD__);

                $this->stdout("issue while creating build ! \n", Console::FG_RED, Console::BOLD);

                echo "<pre>";
                print_r($queue->getErrors());
                exit;

                return false;
            }

            $this->stdout("File has created! \n", Console::FG_RED, Console::BOLD);
        }
    }

    /**
     * update sitemap for search engines
     * @return false|int
     */
    public function actionUpdateSitemap()
    {
        $stores = Restaurant::find()
            ->andWhere(['sitemap_require_update' => 1])
            /*->andWhere(['or',
                ['version' => 2],
                ['version' => 3],
                ['version' => 4]
            ])
            ->andWhere(['!=', 'restaurant_uuid', 'rest_00f54a5e-7c35-11ea-997e-4a682ca4b290'])//todo: extra load on server just for 1 store?
            */
            ->all();

        foreach ($stores as $key => $store) {

                if ($store) {//&& $store->getItems()->count() > 0

                    $dirName = "store";

                    if (!file_exists($dirName))
                        $createStoreFolder = mkdir($dirName);

                    if (!file_exists($dirName . "/" . $store->store_branch_name)) {
                        $myFolder = mkdir($dirName . "/" . $store->store_branch_name);
                    }

                    $sitemap = fopen($dirName . "/" . $store->store_branch_name . "/sitemap.xml", "w") or die("Unable to open file!");

                    fwrite($sitemap, Yii::$app->fileGeneratorComponent->createSitemapXml($store->restaurant_uuid));
                    fclose($sitemap);

                    //Create sitemap.xml file
                    $fileToBeUploaded = file_get_contents("store/" . $store->store_branch_name . "/sitemap.xml");

                    // Encode the image string data into base64
                    $data = base64_encode($fileToBeUploaded);

                    $getSitemapXmlSHA = Yii::$app->githubComponent->getFileSHA('src/sitemap.xml', $store->store_branch_name,);

                    if ($getSitemapXmlSHA->isOk && $getSitemapXmlSHA->data) {

                        //Replace test with store branch name
                        $commitSitemapXmlFileResponse = Yii::$app->githubComponent->createFileContent(
                            $data,
                            $store->store_branch_name,
                            'src/sitemap.xml',
                            'Update sitemap',
                            $getSitemapXmlSHA->data['sha']);

                        if ($commitSitemapXmlFileResponse->isOk) {

                            Restaurant::updateAll([
                                'sitemap_require_update' => 0
                            ], [
                                'restaurant_uuid' => $store->restaurant_uuid
                            ]);

                            //$store->sitemap_require_update = 0;
                            //$store->save(false);

                            //Delete sitemap file
                            $dirPath = "store/" . $store->store_branch_name;
                            $file_pointer = $dirPath . '/sitemap.xml';

                            // Use unlink() function to delete a file
                            if (!unlink($file_pointer)) {
                                Yii::error("$file_pointer cannot be deleted due to an error", __METHOD__);
                            } else {
                                if (!rmdir($dirPath)) {
                                    Yii::error("Could not remove $dirPath", __METHOD__);
                                }
                            }

                        } else {
                            Yii::error('[Github > Commit Sitemap xml]' . json_encode($commitSitemapXmlFileResponse->data['message']) . ' RestaurantUuid: ' . $store->restaurant_uuid, __METHOD__);
                            return false;
                        }


                    } else {
                        Yii::error('[Github > Error while getting file sha]' . json_encode($getSitemapXmlSHA->data['message']) . ' RestaurantUuid: ' . $store->restaurant_uuid, __METHOD__);
                        return false;
                    }

                }
            }

        return self::EXIT_CODE_NORMAL;
    }


    /**
     * make the Refund Request from the API directly without the need of login into MyFatoorah dashboard
     */
    public function actionMakeRefund()
    {
        $refunds = Refund::find()
            ->joinWith(['store', 'payment', 'currency', 'order'])
            ->where(['refund.refund_reference' => null])
            ->andWhere([
                'order.is_deleted' => 0,
                'payment.payment_current_status' => 'CAPTURED'
            ])
            ->andWhere(['NOT', ['refund.payment_uuid' => null]])
            ->andWhere(new Expression('refund_status IS NULL OR refund_status="" or refund_status = "Initiated"'))
            ->all();

        foreach ($refunds as $refund) {

            // in case order is deleted but still exist

            if (!$refund->payment) {
                Yii::error('Refund Error > Payment id not found for refund id (' . $refund->refund_id. '): ');
                continue;
            }

            if ($refund->store->is_myfatoorah_enable) {

                Yii::$app->myFatoorahPayment->setApiKeys($refund->currency->code);

                $response = Yii::$app->myFatoorahPayment->makeRefund($refund->payment->payment_gateway_payment_id, $refund->refund_amount, $refund->reason, $refund->store->supplierCode);

                $responseContent = json_decode($response->content);

                if (!$response->isOk || ($responseContent && !$responseContent->IsSuccess))
                {
                    $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ? json_encode($responseContent->ValidationErrors) : $responseContent->Message;

                    $refund->refund_status = 'REJECTED';
                    $refund->refund_message = 'Rejected because: ' . $errorMessage;

                    if(!$refund->save()) {
                        Yii::error('Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) .
                            ' Data: '. $refund->attributes .' Message:' . $errorMessage);
                    }

                    //mark as failed and notify customer + vendor

                    $refund->notifyFailure($errorMessage);

                    //Yii::error('Refund Error (' . $refund->refund_id . '): ' . $errorMessage);

                }
                else
                {
                    $refund->refund_reference = $responseContent->Data->RefundReference;
                    $refund->refund_status = 'Pending';

                    if(!$refund->save()) {
                        Yii::error('Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) . ' Data: '. $refund->attributes);
                    }

                    $this->stdout("Your refund request has been initiated successfully #".$refund->refund_id."  \n", Console::FG_RED, Console::BOLD);

                    //return self::EXIT_CODE_NORMAL;
                }

            } else if ($refund->store->is_tap_enable) {

                Yii::$app->tapPayments->setApiKeys(
                    $refund->store->live_api_key,
                    $refund->store->test_api_key,
                    $refund->payment->is_sandbox
                );

                $response = Yii::$app->tapPayments->createRefund(
                    $refund->payment->payment_gateway_transaction_id,
                    $refund->refund_amount,
                    $refund->currency->code,
                    $refund->reason ? $refund->reason : 'requested_by_customer'
                );

                if (array_key_exists('errors', $response->data)) {

                    $errorMessage = $response->data['errors'][0]['description'];

                    //Yii::error('Refund Error (' . $refund->refund_id . '): ' . $errorMessage);

                    //mark as failed and notify customer + vendor

                    $refund->notifyFailure($errorMessage);

                    $refund->refund_status = 'REJECTED';
                    $refund->refund_message = 'Rejected because: ' . $errorMessage;

                    if(!$refund->save()) {
                        Yii::error('Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) .
                            ' Data: '. $refund->attributes .' Response: ' . serialize($response->data));
                    }

                    //return $refund->addError('refund_amount', $response->data['errors'][0]['description']);

                } else if ($response->data && isset($response->data['status'])) {

                    $refund->refund_reference = isset($response->data['id']) ? $response->data['id'] : null;
                    $refund->refund_status = $response->data['status'];

                    if(!$refund->save()) {
                        Yii::error('Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) .
                            ' Data: '. $refund->attributes . ' Response: '. serialize($response->data));
                    }

                    $this->stdout("Your refund request has been initiated successfully #".$refund->refund_id."  \n", Console::FG_RED, Console::BOLD);

                    //return self::EXIT_CODE_NORMAL;
                }
            }
        }

       // $this->stdout("No refund requests available \n", Console::FG_RED, Console::BOLD);

       // return self::EXIT_CODE_NORMAL;

    }

    /**
     * Update refund status  for all refunds record
     */
    public function actionUpdateRefundStatusMessage()
    {
        $refunds = Refund::find()
            ->joinWith(['store'])
            ->where(['NOT', ['refund.refund_reference' => null]])
            ->andWhere(['restaurant.is_tap_enable' => 1])
            ->andWhere(['NOT', ['refund.payment_uuid' => null]])
            ->andWhere([
                'IN',
                'refund.refund_status',
                ['PENDING', 'IN_PROGRESS']
            ])
            ->all();

        foreach ($refunds as $refund)
        {
            //todo: what if fatoorah used? instead of tap

            if(!$refund->payment) {
                continue;
            }
            
            Yii::$app->tapPayments->setApiKeys(
                $refund->store->live_api_key,
                $refund->store->test_api_key,
                $refund->payment->is_sandbox
            );

            $response = Yii::$app->tapPayments->retrieveRefund($refund->refund_reference);

            if (!array_key_exists('errors', $response->data) && isset($response->data['status'])) {

                if ($refund->refund_status != $response->data['status'])
                {
                    //REFUNDED, PENDING, IN_PROGRESS, CANCELLED, FAILED, DECLINED, RESTRICRTED, TIMEDOUT, UNKNOWN

                    if(!in_array($response->data['status'], ['REFUNDED', 'PENDING', 'IN_PROGRESS']))
                    {
                        $errorMessage = $response->data['status'];//$response->data['errors'][0]['description'];

                        $refund->notifyFailure($errorMessage);
                    }

                    $refund->refund_status = $response->data['status'];

                    if(!$refund->save())
                    {
                        Yii::error('Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) .
                            ' Data: '. $refund->attributes . ' Response: '. serialize($response->data));
                    }
                }
            }
        }
    }

    /**
     * todo: remove and auto expire
     * Update voucher status
     */
    public function actionUpdateVoucherStatus()
    {
        $vouchers = Voucher::find()->all();

        foreach ($vouchers as $voucher) {
            if ($voucher->valid_until && date('Y-m-d', strtotime('now')) >= date('Y-m-d', strtotime($voucher->valid_until))) {
                $voucher->voucher_status = Voucher::VOUCHER_STATUS_EXPIRED;
                $voucher->save();
            }
        }

        $bankDiscounts = BankDiscount::find()->all();

        foreach ($bankDiscounts as $bankDiscount)
        {
            if ($bankDiscount->valid_until && date('Y-m-d', strtotime('now')) >= date('Y-m-d', strtotime($bankDiscount->valid_until))) {
                $bankDiscount->bank_discount_status = BankDiscount::BANK_DISCOUNT_STATUS_EXPIRED;
                $bankDiscount->save();
            }
        }
    }

    /**
     * Method called to find old transactions that haven't received callback and force a callback
     */
    public function actionUpdateTransactions()
    {
        $now = new DateTime('now');

        $payments = Payment::find()
            ->where("received_callback = 0")
            ->andWhere(['payment_gateway_name' => 'tap'])
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
                    \Yii::error("[Issue checking status (" . $payment->restaurant_uuid . ") Order Uuid: " . $payment->order_uuid . "] " . $e->getMessage(), __METHOD__);
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
    public function actionSendReminderEmail()
    {
        $now = new DateTime('now');

        $orders = Order::find()
            ->andWhere(['order_status' => Order::STATUS_PENDING])
            ->andWhere(['reminder_sent' => 0])
            ->andWhere(['<', 'order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 5 MINUTE)')])
            ->all();

        if ($orders) {

            foreach ($orders as $order) {

                foreach ($order->restaurant->getAgentAssignments()->where(['reminder_email' => 1])->all() as $agentAssignment) {


                    if ($agentAssignment && $agentAssignment->agent) {

                        if ($agentAssignment->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER) {
                            if ($order->order_mode == Order::ORDER_MODE_DELIVERY && $order->delivery_zone_id && $order->deliveryZone->business_location_id != $agentAssignment->business_location_id) {
                                continue;
                            } else if ($order->order_mode == Order::ORDER_MODE_PICK_UP && $order->pickup_location_id != $agentAssignment->business_location_id) {
                                continue;
                            }
                        }

                        \Yii::$app->mailer->compose([
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

    /**
     * Method called by cron once a day to update currency
     */
    public function actionDaily()
    {
        // GET UPDATED CURRENCY DATA FROM API
        $response = Currency::getDataFromApi();

        $campaigns = VendorCampaign::find()
            ->andWhere(['status' => VendorCampaign::STATUS_READY])
            ->all();

        foreach ($campaigns as $campaign) {
            $campaign->process();
        }

        $this->stdout($response . " \n", Console::FG_RED, Console::BOLD);
    }

    /**
     * https://docs.github.com/en/rest/branches/branches#merge-a-branch
     */
    public function actionUpgrade()
    {
        $commitMessage = "testing merge";

        //foreach ()

        $head = "master";

        $base = "develop";

        $response = Yii::$app->githubComponent->mergeABranch($commitMessage, $base, $head);

        if($response->statusCode == 201) {
            echo 'merged';
        }
        else if($response->statusCode == 409) {
            echo 'conflict';
        }

    }
}
