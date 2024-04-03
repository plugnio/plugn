<?php

namespace console\controllers;

use common\models\Agent;
use common\models\MailLog;
use Yii;
use common\models\Currency;
use common\models\RestaurantInvoice;
use common\models\VendorCampaign;
use common\models\Restaurant;
use common\models\Queue;
use common\models\AgentAssignment;
use common\models\PaymentGatewayQueue;
use common\models\Voucher;
use common\models\BankDiscount;
use common\models\Payment;
use common\models\Item;
use common\models\Refund;
use common\models\Order;
use common\models\Subscription;
use \DateTime;
use yii\db\Exception;
use yii\helpers\Console;
use yii\db\Expression;


/**
 * All Cron actions related to this project
 */
class CronController extends \yii\console\Controller
{
    public function actionIndex() {

        //UPDATE agent SET deleted=1 where agent_email_verification=0 AND DATE(agent_created_at) > DATE('2023-11-20');

        /*Yii::$app->mailer->compose ([
            'text' => 'test',
            'message' => 'test'
        ])
            ->setFrom (["no-reply@mail.plugn.site" => \Yii::$app->params['appName']])
            ->setSubject ('Test email')
            ->setTo ("kathrechakrushn@gmail.com")
            //->setCc($contactEmails)
            ->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool'])
            ->send ();*/
    }

    public function actionFixSpam() {

        //adding date as older account not had email verification

        $query = Agent::find()
            ->andWhere(new Expression("agent_email_verification=0 AND DATE(agent_created_at) > DATE('2023-11-20')"));

            //Yii::$app->db->createCommand("SELECT * FROM agent where agent_email_verification=0 AND DATE(agent_created_at) > DATE('2023-11-20')")
            //->queryAll();

        $total = 0;

        foreach ($query->batch() as $agents) {

            $total += sizeof($agents);

            foreach ($agents as $agent) {

                $assignments = $agent->getAgentAssignments()->all();

                foreach ($assignments as $assignment) {

                    $store = $assignment->restaurant;

                    //if store having only 1 assignment

                    $count = $store->getAgentAssignments()->count();

                    if ($count == 1) {
                        $store->deleteSite();
                        //delete
                        //  $assignment->delete();
                    } else {
                        $msg = $store->name . " having " . $count . "assignments";
                        Yii::info($msg);
                    }
                }

                $agent->deleted = 1;
                $agent->save(false);
            }
        }

        echo  $total . " agnets updated!";
    }

    /**
     * Weekly Store Summary
     */
    public function actionWeeklyReport()
    {
        $query = Restaurant::find();

        foreach ($query->batch(100) as $stores) {
            foreach ($stores as $key => $store) {
                $store->sendWeeklyReport();
            }
        }

        //for unpaid invoices

        $query = RestaurantInvoice::find()
            ->mailNotSent()
            ->notPaid();

        foreach ($query->batch(100) as $invoices) {
            foreach ($invoices as $invoice) {
                $invoice->sendEmail();
            }
        }
    }

    /**
     * todo: remove from cron as we not longer need this
     * @return int
     */
    public function actionSiteStatus()
    {
        $query = Restaurant::find()
            ->andWhere(['has_deployed' => 0])
            ->andWhere(new Expression('site_id IS NOT NULL'))
            ->andWhere(['!=', 'site_id', ""])
            ->andWhere(['!=', 'restaurant_email', ""]);

        foreach ($query->batch() as $restaurants) {

            foreach ($restaurants as $restaurant) {

                $getSiteResponse = Yii::$app->netlifyComponent->getSiteData($restaurant->site_id);

                if (!$getSiteResponse->isOk) {
                    continue;
                }

                if ($getSiteResponse->data['state'] == 'current') {

                    $restaurant->has_deployed = 1;
                    $restaurant->save(false);

                    $ml = new MailLog();
                    $ml->to = $restaurant->restaurant_email;
                    $ml->from = \Yii::$app->params['noReplyEmail'];
                    $ml->subject = 'Your store ' . $restaurant->name . ' is now ready';
                    $ml->save();

                    $mailer = \Yii::$app->mailer->compose([
                            'html' => 'store-ready',
                        ], [
                            'store' => $restaurant,
                        ])
                        ->setFrom([\Yii::$app->params['noReplyEmail'] => \Yii::$app->name])
                        ->setReplyTo(\Yii::$app->params['supportEmail'])
                        ->setTo([$restaurant->restaurant_email])
                        ->setSubject('Your store ' . $restaurant->name . ' is now ready');

                    if(\Yii::$app->params['elasticMailIpPool'])
                        $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

                    try {
                        $mailer->send();
                    } catch (\Swift_TransportException $e) {
                        Yii::error($e->getMessage(), "email");
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

        $query = Restaurant::find()
            ->joinWith(['items', 'ownerAgent'])
            ->andWhere(' DATE(restaurant_created_at) = DATE(NOW() - INTERVAL 2 DAY) ')
            ->andWhere(['retention_email_sent' => 0]);

        foreach ($query->batch() as $stores) {

            foreach ($stores as $key => $store) {

                $count = $store->getItems()->count();

                if ($count > 0) {
                    continue;
                }

                foreach ($store->ownerAgent as $agent) {

                    $ml = new MailLog();
                    $ml->to = $agent->agent_email;
                    $ml->from = \Yii::$app->params['noReplyEmail'];
                    $ml->subject = 'Is there anything we can help with?';
                    $ml->save();

                    $mailer = Yii::$app->mailer->compose([
                        'html' => 'offer-assistance',
                    ], [
                        'store' => $store
                    ])
                        ->setFrom([\Yii::$app->params['noReplyEmail'] => \Yii::$app->name])
                        ->setReplyTo(\Yii::$app->params['supportEmail'])
                        ->setTo($agent->agent_email)
                        ->setSubject('Is there anything we can help with?');

                    if(\Yii::$app->params['elasticMailIpPool'])
                        $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

                    try {
                        $mailer->send();
                    } catch (\Swift_TransportException $e) {
                        Yii::error($e->getMessage(), "email");
                    }
                }

                $store->retention_email_sent = 1;
                $store->save(false);
            }
        }
    }

    public function actionRetentionEmailsWhoPassedFiveDaysAndNoSales()
    {
        //todo: why processing all stores, when need only with no orders

        $query = Restaurant::find()
            ->joinWith(['orders', 'ownerAgent'])
            ->andWhere(' DATE(restaurant_created_at) = DATE(NOW() - INTERVAL 5 DAY) ')
            ->andWhere(['retention_email_sent' => 0]);

        foreach ($query->batch() as $stores) {
            foreach ($stores as $key => $store) {

                $count = $store->getOrders()->count();

                if ($count > 0) {
                    continue;
                }

                foreach ($store->ownerAgent as $agent) {

                    $ml = new MailLog();
                    $ml->to = $agent->agent_email;
                    $ml->from = \Yii::$app->params['noReplyEmail'];
                    $ml->subject = 'Is there anything we can help with?';
                    $ml->save();

                    $mailer = Yii::$app->mailer->compose([
                        'html' => 'offer-assistance',
                    ], [
                        'store' => $store
                    ])
                        ->setFrom([\Yii::$app->params['noReplyEmail'] => \Yii::$app->name])
                        ->setReplyTo(\Yii::$app->params['supportEmail'])
                        ->setTo($agent->agent_email)
                        ->setSubject('Is there anything we can help with?');

                    if(\Yii::$app->params['elasticMailIpPool'])
                        $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

                    try {
                        $mailer->send();
                    } catch (\Swift_TransportException $e) {
                        Yii::error($e->getMessage(), "email");
                    }
                }

                $store->retention_email_sent = 1;
                $store->save(false);
            }
        }
    }

    /**
     * @return void
     */
    public function actionDowngradedStoreSubscription()
    {
        $start_date = date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d")));
        $end_date = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d")));

        $query = Subscription::find()
            ->andWhere(['subscription_status' => Subscription::STATUS_ACTIVE])
            // ->andWhere(['notified_email' => 1])
            ->andWhere(['not', ['subscription_end_at' => null]])
            ->andWhere(['between', 'subscription_end_at', $start_date, $end_date])//todo: try DATE mysql function
            ->with(['plan', 'restaurant']);

        foreach ($query->batch() as $subscriptions) {
            foreach ($subscriptions as $subscription) {

                if (date('Y-m-d', strtotime($subscription->subscription_end_at)) == date('Y-m-d')) {

                    foreach ($subscription->restaurant->getOwnerAgent()->all() as $agent) {

                        $ml = new MailLog();
                        $ml->to = $agent->agent_email;
                        $ml->from = \Yii::$app->params['noReplyEmail'];
                        $ml->subject = $subscription->restaurant->name . ' has been downgraded to our free plan';
                        $ml->save();

                        $mailer = \Yii::$app->mailer->compose([
                            'html' => 'subscription-expired',
                        ], [
                            'subscription' => $subscription,
                            'store' => $subscription->restaurant,
                            'plan' => $subscription->plan->name,
                            'agent_name' => $agent->agent_name,
                        ])
                            ->setFrom([\Yii::$app->params['noReplyEmail'] => \Yii::$app->name])
                            ->setReplyTo(\Yii::$app->params['supportEmail'])
                            ->setTo($agent->agent_email)
                            ->setBcc(\Yii::$app->params['supportEmail'])
                            ->setSubject($subscription->restaurant->name . ' has been downgraded to our free plan');

                        if(\Yii::$app->params['elasticMailIpPool'])
                            $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

                        try {

                            $result = $mailer->send();

                            if (!$result)
                                Yii::error('[Error while sending email]' . json_encode($result), __METHOD__);

                        } catch (\Swift_TransportException $e) {
                            Yii::error($e->getMessage(), "email");
                        }
                    }

                    $subscription->subscription_status = Subscription::STATUS_INACTIVE;
                    $subscription->save();
                }
            }
        }
    }

    /**
     * @return int
     */
    public function actionNotifyAgentsForSubscriptionThatWillExpireSoon()
    {
        $start_date = date("Y-m-d H:i:s", mktime(00, 00, 0, date("m"), date("d") + 5));
        $end_date = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") + 5));

        $query = Subscription::find()
            ->andWhere(['subscription_status' => Subscription::STATUS_ACTIVE])
            ->andWhere(['notified_email' => 0])
            ->andWhere(['not', ['subscription_end_at' => null]])
            ->andWhere(['between', 'subscription_end_at', $start_date, $end_date])
            ->with(['plan', 'restaurant']);

        foreach ($query->batch() as $subscriptions) {
            foreach ($subscriptions as $subscription) {

                foreach ($subscription->restaurant->getOwnerAgent()->all() as $agent) {

                    $ml = new MailLog();
                    $ml->to = $agent->agent_email;
                    $ml->from = \Yii::$app->params['noReplyEmail'];
                    $ml->subject = 'Your Subscription is Expiring';
                    $ml->save();

                    $mailer = \Yii::$app->mailer->compose([
                        'html' => 'subscription-will-expire-soon-html',
                    ], [
                        'subscription' => $subscription,
                        'store' => $subscription->restaurant,
                        'plan' => $subscription->plan->name,
                        'agent_name' => $agent->agent_name,
                    ])
                        ->setFrom([\Yii::$app->params['noReplyEmail'] => \Yii::$app->name])
                        ->setReplyTo(\Yii::$app->params['supportEmail'])
                        ->setTo($agent->agent_email)
                        ->setBcc(\Yii::$app->params['supportEmail'])
                        ->setSubject('Your Subscription is Expiring');

                    if(\Yii::$app->params['elasticMailIpPool'])
                        $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

                    try {
                        $result = $mailer->send();

                        if ($result) {
                            $subscription->notified_email = 1;
                            $subscription->save(false);
                        }

                    } catch (\Swift_TransportException $e) {
                        Yii::error($e->getMessage(), "email");
                    }

                }
            }
        }

        $this->stdout("Email sent to all agents of employer that have applicants will expire soon \n", Console::FG_RED, Console::NORMAL);
        return self::EXIT_CODE_NORMAL;

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

    /**
     * @return void
     */
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

                //Yii::error('[Netlify > While Creating new site]' . json_encode($queue->getErrors()), __METHOD__);

                $queue->queue_status = Queue::QUEUE_STATUS_FAILED;
                $queue->queue_response = print_r($queue->getErrors(), true);
                $queue->save(false);

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
     * **no need this**
     * -------------------------------------------
     * update sitemap for search engines
     * @return false|int
     *
    public function actionUpdateSitemap()
    {
        $stores = Restaurant::find()
            ->andWhere(['sitemap_require_update' => 1])
            *->andWhere(['or',
                ['version' => 2],
                ['version' => 3],
                ['version' => 4]
            ])
            ->andWhere(['!=', 'restaurant_uuid', 'rest_00f54a5e-7c35-11ea-997e-4a682ca4b290'])//todo: extra load on server just for 1 store?
            *
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
    }*/


    /**
     * make the Refund Request from the API directly without the need of login into MyFatoorah dashboard
     */
    public function actionMakeRefund()
    {
         $query = Refund::find()
            ->joinWith(['store', 'payment', 'currency', 'order'])
            ->where(['refund.refund_reference' => null])
            ->andWhere([
                'order.is_deleted' => 0,
                'payment.payment_current_status' => 'CAPTURED'
            ])
            ->andWhere(['NOT', ['refund.payment_uuid' => null]])
            ->andWhere(new Expression('refund_status IS NULL OR refund_status="" or refund_status = "Initiated"'));

         foreach ($query->batch() as $refunds) {
             foreach ($refunds as $refund) {

                 // in case order is deleted but still exist

                 if (!$refund->payment) {
                     Yii::error('Refund Error > Payment id not found for refund id (' . $refund->refund_id . '): ');
                     continue;
                 }

                 if ($refund->store->is_myfatoorah_enable) {

                     Yii::$app->myFatoorahPayment->setApiKeys($refund->currency->code);

                     $response = Yii::$app->myFatoorahPayment->makeRefund($refund->payment->payment_gateway_payment_id, $refund->refund_amount, $refund->reason, $refund->store->supplierCode);

                     $responseContent = json_decode($response->content);

                     if (!$response->isOk || ($responseContent && !$responseContent->IsSuccess)) {
                         $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ? json_encode($responseContent->ValidationErrors) : $responseContent->Message;

                         $refund->refund_status = 'REJECTED';
                         $refund->refund_message = 'Rejected because: ' . $errorMessage;

                         if (!$refund->save()) {
                             Yii::error('Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) .
                                 ' Data: ' . $refund->attributes . ' Message:' . $errorMessage);
                         }

                         //mark as failed and notify customer + vendor

                         $refund->notifyFailure($errorMessage);

                         //Yii::error('Refund Error (' . $refund->refund_id . '): ' . $errorMessage);

                     } else {
                         $refund->refund_reference = $responseContent->Data->RefundReference;
                         $refund->refund_status = 'Pending';

                         if (!$refund->save()) {
                             Yii::error('Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) . ' Data: ' . $refund->attributes);
                         }

                         $this->stdout("Your refund request has been initiated successfully #" . $refund->refund_id . "  \n", Console::FG_RED, Console::BOLD);

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
                         $refund->reason ? $refund->reason : 'requested_by_customer',
                         $refund->store
                     );

                     if (array_key_exists('errors', $response->data)) {

                         $errorMessage = $response->data['errors'][0]['description'];

                         //Yii::error('Refund Error (' . $refund->refund_id . '): ' . $errorMessage);

                         //mark as failed and notify customer + vendor

                         $refund->notifyFailure($errorMessage);

                         $refund->refund_status = 'REJECTED';
                         $refund->refund_message = 'Rejected because: ' . $errorMessage;

                         if (!$refund->save()) {
                             Yii::error('Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) .
                                 ' Data: ' . $refund->attributes . ' Response: ' . serialize($response->data));
                         }

                         //return $refund->addError('refund_amount', $response->data['errors'][0]['description']);

                     } else if ($response->data && isset($response->data['status'])) {

                         $refund->refund_reference = isset($response->data['id']) ? $response->data['id'] : null;
                         $refund->refund_status = $response->data['status'];

                         if (!$refund->save()) {
                             Yii::error('Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) .
                                 ' Data: ' . $refund->attributes . ' Response: ' . serialize($response->data));
                         }

                         $this->stdout("Your refund request has been initiated successfully #" . $refund->refund_id . "  \n", Console::FG_RED, Console::BOLD);

                         //return self::EXIT_CODE_NORMAL;
                     }
                 }

                 $rate = 1;//default rate

                 if (isset($refund->order->currency)) {
                     $rate = 1 / $refund->order->currency->rate;// to USD
                 }

                 Yii::$app->eventManager->track('Refunds Processed', array_merge($refund->attributes, [
                     'refund_amount' => $refund->refund_amount,
                     'value' => $refund->refund_amount * $rate,
                     'currency' => 'USD'
                 ]),
                     null,
                     $refund->restaurant_uuid);

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
        $query = Refund::find()
            ->joinWith(['store'])
            ->where(['NOT', ['refund.refund_reference' => null]])
            ->andWhere(['restaurant.is_tap_enable' => 1])
            ->andWhere(['NOT', ['refund.payment_uuid' => null]])
            ->andWhere([
                'IN',
                'refund.refund_status',
                ['PENDING', 'IN_PROGRESS']
            ]);

        foreach ($query->batch() as $refunds) {
            foreach ($refunds as $refund) {
                //todo: what if fatoorah used? instead of tap

                if (!$refund->payment) {
                    continue;
                }

                Yii::$app->tapPayments->setApiKeys(
                    $refund->store->live_api_key,
                    $refund->store->test_api_key,
                    $refund->payment->is_sandbox
                );

                $response = Yii::$app->tapPayments->retrieveRefund($refund->refund_reference);

                if (!array_key_exists('errors', $response->data) && isset($response->data['status'])) {

                    if ($refund->refund_status != $response->data['status']) {
                        //REFUNDED, PENDING, IN_PROGRESS, CANCELLED, FAILED, DECLINED, RESTRICRTED, TIMEDOUT, UNKNOWN

                        if (!in_array($response->data['status'], ['REFUNDED', 'PENDING', 'IN_PROGRESS'])) {
                            $errorMessage = $response->data['status'];//$response->data['errors'][0]['description'];

                            $refund->notifyFailure($errorMessage);
                        }

                        $refund->refund_status = $response->data['status'];

                        if (!$refund->save()) {
                            Yii::error('Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) .
                                ' Data: ' . $refund->attributes . ' Response: ' . serialize($response->data));
                        }
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
        $query = Voucher::find()
            ->andWhere(["!=", "voucher_status", Voucher::VOUCHER_STATUS_EXPIRED])
            ->andWhere(new Expression("valid_until IS NOT NULL"));

        foreach ($query->batch() as $vouchers) {
            foreach ($vouchers as $voucher) {
                if (date('Y-m-d', strtotime('now')) >= date('Y-m-d', strtotime($voucher->valid_until))) {
                    $voucher->voucher_status = Voucher::VOUCHER_STATUS_EXPIRED;
                    $voucher->save();
                }
            }
        }

        $query = BankDiscount::find()
            ->andWhere(["!=", "bank_discount_status", BankDiscount::BANK_DISCOUNT_STATUS_EXPIRED])
            ->andWhere(new Expression("valid_until IS NOT NULL"));

        foreach ($query->batch() as $bankDiscounts) {
            foreach ($bankDiscounts as $bankDiscount) {
                if ($bankDiscount->valid_until && date('Y-m-d', strtotime('now')) >= date('Y-m-d', strtotime($bankDiscount->valid_until))) {
                    $bankDiscount->bank_discount_status = BankDiscount::BANK_DISCOUNT_STATUS_EXPIRED;
                    $bankDiscount->save();
                }
            }
        }
    }

    /**
     * Method called to find old transactions that haven't received callback and force a callback
     */
    public function actionUpdateTransactions()
    {
        //$now = new DateTime('now');

        $query = Payment::find()
            ->where("received_callback = 0")
            ->andWhere(['payment_gateway_name' => 'tap'])
            ->andWhere(['<', 'payment_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 10 MINUTE)')]);

        foreach ($query->batch() as $payments) {
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
        }

        //    $this->stdout("All Payments received callback \n", Console::FG_RED, Console::BOLD);
        //    return self::EXIT_CODE_NORMAL;

        $this->stdout("Payments status updated successfully \n", Console::FG_RED, Console::BOLD);
        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Method called to Send  reminder if order not picked up in 5 minutes
     */
    public function actionSendReminderEmail()
    {
        //$now = new DateTime('now');

        $query = Order::find()
            ->andWhere(['order_status' => Order::STATUS_PENDING])
            ->andWhere(['reminder_sent' => 0])
            ->andWhere(['<', 'order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 5 MINUTE)')]);

        foreach ($query->batch() as $orders) {

            foreach ($orders as $order) {

                $agentAssignments = $order->restaurant->getAgentAssignments()
                    ->where(['reminder_email' => 1])
                    ->all();

                foreach ($agentAssignments as $agentAssignment) {

                    if ($agentAssignment && $agentAssignment->agent) {

                        if ($agentAssignment->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER) {
                            if ($order->order_mode == Order::ORDER_MODE_DELIVERY && $order->delivery_zone_id && $order->deliveryZone->business_location_id != $agentAssignment->business_location_id) {
                                continue;
                            } else if ($order->order_mode == Order::ORDER_MODE_PICK_UP && $order->pickup_location_id != $agentAssignment->business_location_id) {
                                continue;
                            }
                        }

                        $ml = new MailLog();
                        $ml->to = $agentAssignment->agent->agent_email;
                        $ml->from = \Yii::$app->params['noReplyEmail'];
                        $ml->subject = 'Order #' . $order->order_uuid . ' from ' . $order->restaurant->name;
                        $ml->save();

                        $mailer = \Yii::$app->mailer->compose([
                            'html' => 'order-reminder-html',
                        ], [
                            'order' => $order,
                            'agent_name' => $agentAssignment->agent->agent_name
                        ])
                            ->setFrom([\Yii::$app->params['noReplyEmail'] => $order->restaurant->name])
                            ->setReplyTo(\Yii::$app->params['supportEmail'])
                            ->setTo($agentAssignment->agent->agent_email)
                            ->setSubject('Order #' . $order->order_uuid . ' from ' . $order->restaurant->name);

                        if(\Yii::$app->params['elasticMailIpPool'])
                            $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

                        try {
                            $mailer->send();
                        } catch (\Swift_TransportException $e) {
                            Yii::error($e->getMessage(), "email");
                        }
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
     * @return void
     */
    public function actionHour() {
    }

    /**
     * @return void
     */
    public function actionMinute() {

        $query = VendorCampaign::find()
            ->andWhere(['status' => VendorCampaign::STATUS_READY]);

        $total = 0;

        foreach ($query->batch() as $campaigns) {

            $total += sizeof($campaigns);

            foreach ($campaigns as $campaign) {
                $campaign->process();
            }
        }

        $this->stdout( $total . " Campaign processed \n", Console::FG_RED, Console::BOLD);
    }

    /**
     * Method called by cron once a day to update currency
     */
    public function actionDaily()
    {

        //pollTapStatus, as they said it will take 1 day to approve docs, if someone do checkout before that, they might
        // need to process refund etc,... so better enable checkout once accounts approved ... once payout enabled

        $query = Restaurant::find()
            ->andWhere(['!=', 'restaurant.is_deleted', 1])
            /*->andWhere([
                'OR',
                ['is_tap_business_active' => false],
                ['!=', 'tap_merchant_status', 'Active']
            ])*/
            ->andWhere(['tap_merchant_status' => 'New Pending Approval'])
            ->andWhere(['is_tap_created' => true]);

        foreach ($query->batch() as $stores) {
            foreach ($stores as $store) {
                $store->pollTapStatus();
            }
        }

        // GET UPDATED CURRENCY DATA FROM API
        $response = Currency::getDataFromApi();

        $this->stdout(print_r($response, true) . " \n", Console::FG_RED, Console::BOLD);

        //remind failed build

        //$failed = Queue::find()->andWhere (['queue_status' => Queue::QUEUE_STATUS_FAILED])
        //    ->count();

        //if($failed > 0)
        //    Yii::error ($failed . ' Stores failed, need to publish manually');

        //alert inactive stores

        $query = Restaurant::find()
            ->andWhere(['!=', 'restaurant.is_deleted', 1])
            ->andWhere(new Expression("site_id IS NOT NULL"))
            ->inActive()
            ->andWhere(new Expression("warned_delete_at IS NULL AND DATE(restaurant_created_at) < DATE('".
                date('Y-m-d', strtotime("-60 days"))."')"));

        foreach ($query->batch() as $stores) {
            foreach ($stores as $store) {
                $store->alertInActive();
            }
        }

        //remove inactive stores

        $query = Restaurant::find()
            ->andWhere(['!=', 'restaurant.is_deleted', 1])
            ->andWhere(new Expression("site_id IS NOT NULL"))
            ->inActive()
            ->andWhere(new Expression("warned_delete_at IS NOT NULL AND DATE(warned_delete_at) < DATE('".
                date('Y-m-d', strtotime("-7 days"))."')"));

        foreach ($query->batch() as $stores) {
            foreach ($stores as $store) {
                $store->deleteSite();
            }
        }

        //send today's bestselling

            $query = Item::find()
                ->orderBy (['unit_sold' => SORT_DESC])
                ->limit (5);

            $items = [];

            foreach ($query->all() as $item) {
                $items[] = [
                    'unit_sold' => $item->unit_sold,
                    'item_name' => $item->item_name,
                    'item_name_ar' => $item->item_name_ar,
                    "restaurant" => $item->restaurant->name,
                    'item_type' => $item->item_type,
                    'item_uuid' => $item->item_uuid,
                    'restaurant_uuid' => $item->restaurant_uuid,
                ];
            }

            Yii::$app->eventManager->track('Best Selling',  $items);//, null, $item->restaurant_uuid

            //inactive stores

            $inactive = Restaurant::find()
                ->inActive()
                ->count();

            $total = Restaurant::find()
                ->count();

            $data = [
                "inactive" => $inactive,
                "active" => $total - $inactive,
                "total" => $total
            ];

            Yii::$app->eventManager->track('Inactive stores',  $data);
    }

    /**
     * todo: check if we still need this
     * fix store missing netlify site_id (because of failed site upgrade attempt)
     * @return void
     */
    public function actionNetlifyFix() {

        $query = Restaurant::find()
            ->andWhere(["NOT LIKE", "restaurant_domain", ".site"])//hosted only on netlify
            ->andWhere(new Expression("is_deleted=0 and site_id is null and has_deployed=1"));

        $i = 0;

        foreach ($query->batch() as $stores) {

            foreach ($stores as $store) {

                $domain = str_replace(["https://", "http://", "www"], ["", "", ""], $store['restaurant_domain']);

                $response = Yii::$app->netlifyComponent->listSiteData(1, $domain);

                $site_id = null;

                foreach ($response->data as $site) {
                    if (
                        $domain == $site['custom_domain'] ||
                        in_array($domain, $site['domain_aliases'])
                    ) {
                        $site_id = $site['site_id'];
                        continue;
                    }
                }

                if ($site_id) {

                    $i++;
                    //$this->stdout('update restaurant set site_id="' . $site_id . '" where restaurant_uuid="' . $store['restaurant_uuid'] . '";'.PHP_EOL );

                    $store->site_id = $site_id;
                    $store->save(false);

                    Yii::$app->netlifyComponent->upgradeSite($store);

                    $this->stdout($store->restaurant_domain . ' Updated' . PHP_EOL);
                }
            }
        }

        $this->stdout($i . ' Total' . PHP_EOL);
    }
}
