<?php

namespace console\controllers;

use Yii;
use common\models\Restaurant;
use common\models\OrderItem;
use common\models\Queue;
use common\models\TapQueue;
use common\models\Voucher;
use common\models\BankDiscount;
use common\models\Payment;
use common\models\Item;
use common\models\City;
use common\models\Plan;
use common\models\Area;
use common\models\Order;
use common\models\Subscription;
use common\models\OpeningHour;
use common\models\CountryPaymentMethod;
use common\models\Country;
use common\models\ExtraOption;
use common\models\ItemImage;
use common\models\AreaDeliveryZone;
use common\models\DeliveryZone;
use common\models\RestaurantTheme;
use common\models\BusinessLocation;
use common\models\RestaurantBranch;
use \DateTime;
use yii\helpers\Console;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

/**
 * All Cron actions related to this project
 */
class CronController extends \yii\console\Controller {


    public function actionQatar(){
      $jsonString = file_get_contents('qatar.json');
      $data = json_decode($jsonString, true);

      foreach ($data as $key => $area) {

          $area_name = str_replace(' (' . $area['cityTitleEn'] . ')', '', $area['titleEn']);

          if( !$city_model = City::find()->where(['city_name' => $area['cityTitleEn']])->one() ) {

            $city_model = new City();

            $city_model->country_id = 125;//Qatar
            $city_model->city_name = $area['cityTitleEn'];
            $city_model->city_name_ar = $area['cityTitleAr'];
            $city_model->save(false);
          }

          if( !Area::find()->where(['area_name' => $area['titleEn']])->exists() ){
            $area_model = new Area();
            $area_model->city_id = $city_model->city_id;
            $area_model->area_name = $area_name;
            $area_model->area_name_ar = $area['titleAr'];
            $area_model->latitude = $area['lat'];
            $area_model->longitude = $area['lng'];
            $area_model->save(false);
          }


      }

      $this->stdout("Thank you Big Boss \n", Console::FG_RED, Console::NORMAL);
      return self::EXIT_CODE_NORMAL;
    }

    public function actionKsa(){
      $jsonString = file_get_contents('ksa.json');
      $data = json_decode($jsonString, true);

      foreach ($data as $key => $area) {

          $area_name = str_replace(' (' . $area['cityTitleEn'] . ')', '', $area['titleEn']);

          if( !$city_model = City::find()->where(['city_name' => $area['cityTitleEn']])->one() ) {

            $city_model = new City();

            $city_model->country_id = 129;//KSA
            $city_model->city_name = $area['cityTitleEn'];
            $city_model->city_name_ar = $area['cityTitleAr'];
            $city_model->save(false);
          }

          if( !Area::find()->where(['area_name' => $area['titleEn']])->exists() ){
            $area_model = new Area();
            $area_model->city_id = $city_model->city_id;
            $area_model->area_name = $area_name;
            $area_model->area_name_ar = $area['titleAr'];
            $area_model->latitude = $area['lat'];
            $area_model->longitude = $area['lng'];
            $area_model->save(false);
          }


      }

      $this->stdout("Thank you Big Boss \n", Console::FG_RED, Console::NORMAL);
      return self::EXIT_CODE_NORMAL;
    }

    public function actionEgypt(){
      $jsonString = file_get_contents('egypt.json');
      $data = json_decode($jsonString, true);

      foreach ($data as $key => $area) {

          $area_name = str_replace(' (' . $area['cityTitleEn'] . ')', '', $area['titleEn']);
          $area_name_ar = str_replace(' (' . $area['cityTitleAr'] . ')', '', $area['titleAr']);

          if( !$city_model = City::find()->where(['city_name' => $area['cityTitleEn']])->one() ) {

            $city_model = new City();

            $city_model->country_id = 49;//Egypt
            $city_model->city_name = $area['cityTitleEn'];
            $city_model->city_name_ar = $area['cityTitleAr'];
            $city_model->save(false);
          }

          if( !Area::find()->where(['area_name' => $area['titleEn']])->exists() ){
            $area_model = new Area();
            $area_model->city_id = $city_model->city_id;
            $area_model->area_name = $area_name;
            $area_model->area_name_ar = $area_name_ar;
            $area_model->latitude = $area['lat'];
            $area_model->longitude = $area['lng'];
            $area_model->save(false);
          }


      }

      $this->stdout("Thank you Big Boss \n", Console::FG_RED, Console::NORMAL);
      return self::EXIT_CODE_NORMAL;
    }

    public function actionBahrain(){
      $jsonString = file_get_contents('bahrain.json');
      $data = json_decode($jsonString, true);

      foreach ($data as $key => $area) {

          $area_name = str_replace(' (' . $area['cityTitleEn'] . ')', '', $area['titleEn']);

          if( !$city_model = City::find()->where(['city_name' => $area['cityTitleEn']])->one() ) {

            $city_model = new City();

            $city_model->country_id = 12;//BH
            $city_model->city_name = $area['cityTitleEn'];
            $city_model->city_name_ar = $area['cityTitleAr'];
            $city_model->save(false);
          }

          if( !Area::find()->where(['area_name' => $area['titleEn']])->exists() ){
            $area_model = new Area();
            $area_model->city_id = $city_model->city_id;
            $area_model->area_name = $area_name;
            $area_model->area_name_ar = $area['titleAr'];
            $area_model->latitude = $area['lat'];
            $area_model->longitude = $area['lng'];
            $area_model->save(false);
          }


      }

      $this->stdout("Thank you Big Boss \n", Console::FG_RED, Console::NORMAL);
      return self::EXIT_CODE_NORMAL;
    }

    public function actionUae(){
      $jsonString = file_get_contents('uae.json');
      $data = json_decode($jsonString, true);

      foreach ($data as $key => $area) {

          $area_name = str_replace(' (' . $area['cityTitleEn'] . ')', '', $area['titleEn']);

          if( !$city_model = City::find()->where(['city_name' => $area['cityTitleEn']])->one() ) {

            $city_model = new City();

            $city_model->country_id = 162;//UAE
            $city_model->city_name = $area['cityTitleEn'];
            $city_model->city_name_ar = $area['cityTitleAr'];
            $city_model->save(false);
          }

          if( !Area::find()->where(['area_name' => $area['titleEn']])->exists() ){
            $area_model = new Area();
            $area_model->city_id = $city_model->city_id;
            $area_model->area_name = $area_name;
            $area_model->area_name_ar = $area['titleAr'];
            $area_model->latitude = $area['lat'];
            $area_model->longitude = $area['lng'];
            $area_model->save(false);
          }


      }

      $this->stdout("Thank you Big Boss \n", Console::FG_RED, Console::NORMAL);
      return self::EXIT_CODE_NORMAL;
    }

    public function actionOman(){
      $jsonString = file_get_contents('oman.json');
      $data = json_decode($jsonString, true);

      foreach ($data as $key => $area) {

          $area_name = str_replace(' (' . $area['cityTitleEn'] . ')', '', $area['titleEn']);

          if( !$city_model = City::find()->where(['city_name' => $area['cityTitleEn']])->one() ) {

            $city_model = new City();

            $city_model->country_id = 116;//Oman
            $city_model->city_name = $area['cityTitleEn'];
            $city_model->city_name_ar = $area['cityTitleAr'];
            $city_model->save(false);
          }

          if( !Area::find()->where(['area_name' => $area['titleEn']])->exists() ){
            $area_model = new Area();
            $area_model->city_id = $city_model->city_id;
            $area_model->area_name = $area_name;
            $area_model->area_name_ar = $area['titleAr'];
            $area_model->latitude = $area['lat'];
            $area_model->longitude = $area['lng'];
            $area_model->save(false);
          }


      }

      $this->stdout("Thank you Big Boss \n", Console::FG_RED, Console::NORMAL);
      return self::EXIT_CODE_NORMAL;
    }


    public function actionIndex(){
        $restaurants = Restaurant::find()->where(['IS NOT', 'phone_number', null])->all();

        foreach ($restaurants as  $restaurant) {
          if($restaurant){

            if($restaurant->phone_number)
              $restaurant->phone_number = str_replace(' ', '',"+965" . $restaurant->phone_number);

            if($restaurant->owner_number)
               $restaurant->owner_number = str_replace(' ', '',"+965" . $restaurant->owner_number);

            $restaurant->save(false);
          }

        }

        $customers = \common\models\Customer::find()->all();


        foreach ($customers as  $customer) {
          if($customer){

            $customer->customer_phone_number = str_replace(' ', '',"+965" . $customer->customer_phone_number);

            $customer->save(false);
          }

        }


        $countries = Country::find()->all();

        foreach ($countries as $country) {
          $country_payment_method = new CountryPaymentMethod();
          $country_payment_method->country_id = $country->country_id;
          $country_payment_method->payment_method_id = 2; //Credit card
          $country_payment_method->save(false);
        }

        $country_payment_method = new CountryPaymentMethod();
        $country_payment_method->country_id = 84; //kuwait
        $country_payment_method->payment_method_id = 1; //knet
        $country_payment_method->save(false);

        $country_payment_method = new CountryPaymentMethod();
        $country_payment_method->country_id = 129; //KSA
        $country_payment_method->payment_method_id = 4; //Mada
        $country_payment_method->save(false);

        $country_payment_method = new CountryPaymentMethod();
        $country_payment_method->country_id = 12; //Bahrain
        $country_payment_method->payment_method_id = 5; //Benefit
        $country_payment_method->save(false);


        $this->stdout("Thank you Big Boss \n", Console::FG_RED, Console::NORMAL);
        return self::EXIT_CODE_NORMAL;
    }


    public function actionMigration(){

        $restaurantBranches = RestaurantBranch::find()->all();
        foreach ($restaurantBranches as $key => $branch) {

          $store = Restaurant::findOne($branch->restaurant_uuid);

          if(!BusinessLocation::find()->where(['restaurant_uuid' => $branch->restaurant_uuid, 'business_location_name' =>  $branch->branch_name_en , 'business_location_name_ar' =>  $branch->branch_name_ar])->exists()){
            $businessLocation = new BusinessLocation;
            $businessLocation->country_id = 84;
            $businessLocation->restaurant_uuid = $branch->restaurant_uuid;
            $businessLocation->business_location_name = $branch->branch_name_en;
            $businessLocation->business_location_name_ar = $branch->branch_name_ar;
            $businessLocation->support_pick_up = $store->support_pick_up ? 1 : 0;
            $businessLocation->save();
          }

        }


        $stores = Restaurant::find()->all();
        foreach ($stores as $key => $store) {

          if(
            $store->restaurant_uuid == 'rest_6a55139f-f340-11ea-808a-0673128d0c9c' ||
            $store->restaurant_uuid == 'rest_1276d589-f41c-11ea-808a-0673128d0c9c' ||
            $store->restaurant_uuid == 'rest_aa69124d-2346-11eb-b97d-0673128d0c9c' ||
            $store->restaurant_uuid == 'rest_f6bc4e4a-e7c6-11ea-808a-0673128d0c9c' ||
            $store->restaurant_uuid == 'rest_5d657108-c91f-11ea-808a-0673128d0c9c'
          ){
            $store->hide_request_driver_button = 0;
            $store->save(false);
          }


          if( $deliveryZones = $store->getRestaurantDeliveryAreas()->all()  ){


            if(!$businessLocation = BusinessLocation::find()->where(['restaurant_uuid' => $store->restaurant_uuid])->one()){
              $businessLocation = new BusinessLocation;
              $businessLocation->restaurant_uuid = $store->restaurant_uuid;
              $businessLocation->country_id = 84;
              $businessLocation->business_location_name = 'Main branch';
              $businessLocation->business_location_name_ar = 'الفرع الرئيسي';
              $businessLocation->support_pick_up = $store->support_pick_up ? 1 : 0;
              $businessLocation->save();

            }



            foreach ($deliveryZones as $key => $deliveryZone) {

                if(!$delivery_zone_model = $store->getDeliveryZones()->where(
                  [
                    'delivery_time' => $deliveryZone->delivery_time,
                    'delivery_fee' => $deliveryZone->delivery_fee,
                    'min_charge'   => $deliveryZone->min_charge
                  ]
                )->one()){
                  $delivery_zone_model = new DeliveryZone;
                  $delivery_zone_model->business_location_id = $businessLocation->business_location_id;
                  $delivery_zone_model->restaurant_uuid = $store->restaurant_uuid;
                  $delivery_zone_model->country_id = 84;
                  $delivery_zone_model->delivery_time = $deliveryZone->delivery_time;
                  $delivery_zone_model->delivery_fee = $deliveryZone->delivery_fee;
                  $delivery_zone_model->min_charge = $deliveryZone->min_charge ? $deliveryZone->min_charge : 0 ;
                  $delivery_zone_model->time_unit = 'min';

                  if(!$delivery_zone_model->save()){
                    die(var_dump($delivery_zone_model->errors) . var_dump($deliveryZone) );
                  }
                }


                $area_model = Area::findOne($deliveryZone->area_id);

                if($area_model){

                  if(!$area_delivery_zone_model = $store->getAreaDeliveryZones()->where(
                    [
                      'restaurant_uuid' => $store->restaurant_uuid,
                      'delivery_zone_id' => $delivery_zone_model->delivery_zone_id,
                      'area_id'   => $area_model->area_id
                    ]
                  )->one()){
                    $area_delivery_zone_model = new AreaDeliveryZone;
                    $area_delivery_zone_model->restaurant_uuid = $store->restaurant_uuid;
                    $area_delivery_zone_model->delivery_zone_id = $delivery_zone_model->delivery_zone_id;
                    $area_delivery_zone_model->country_id = 84;
                    $area_delivery_zone_model->city_id = $area_model->city_id;
                    $area_delivery_zone_model->area_id = $area_model->area_id;


                    if(!$area_delivery_zone_model->save()){
                      die(var_dump($area_delivery_zone_model->errors) . var_dump($area_delivery_zone_model) );
                    }
                  }


                }




            }


          }



          foreach ($store->getOrders()->all() as $key => $order) {

            if($order->order_mode == 1 && $areaDeliveryArea = $store->getAreaDeliveryZones()->where(['area_id' => $order->area_id])->one()){
              $order->delivery_zone_id = $areaDeliveryArea->delivery_zone_id;
            }


            if($order->order_mode == 2 && $order->restaurant_branch_id && $businessLocation = $store->getBusinessLocations()->where(['business_location_name' => $order->restaurantBranch->branch_name_en])->one()){
              $order->pickup_location_id = $businessLocation->business_location_id;
            }

            $order->customer_phone_number = '+965' . $order->customer_phone_number;


            $order->save(false);


          }


        }

        $this->stdout("Thank you Big Boss \n", Console::FG_RED, Console::NORMAL);
        return self::EXIT_CODE_NORMAL;

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



    // public function actionNotifyAgentsForSubscriptionThatWillExpireSoon(){

      // $now = new DateTime('now');
      // $subscriptions = Subscription::find()
      //         ->where(['subscription_status' => Subscription::STATUS_ACTIVE])
      //         ->andWhere(['notified_email' => 0])
      //         ->andWhere(['not', ['subscription_end_at' => null]])
      //
      //         ->andWhere(['<=' ,'subscription_end_at', date('Y-m-d H:i:s', strtotime('+5 days'))])
      //
      //         ->all();


      // foreach ($subscriptions as $subscription) {
      //   echo (json_encode(($subscription->subscription_end_at)) . "\r\n");


        // foreach ($subscription->restaurant->getOwnerAgent()->all() as $agent ) {
        //   $result = \Yii::$app->mailer->compose([
        //               'html' => 'subscription-will-expire-soon-html',
        //                   ], [
        //               'subscription' => $subscription,
        //               'agent_name' => $agent->agent_name,
        //           ])
        //           ->setFrom([\Yii::$app->params['supportEmail']])
        //           ->setTo($agent->agent_email)
        //           ->setSubject('Your Subscription is Expiring')
        //           ->send();
        //
        //     if($result){
        //       $subscription->notified_email = 1;
        //       $subscription->save(false);
        //     }
        // }
      // }

      // $this->stdout("Email sent to all agents of employer that have applicants will expire soon \n", Console::FG_RED, Console::NORMAL);
      // return self::EXIT_CODE_NORMAL;

       // $origin =  new DateTime(date('Y-m-d'));
       // $target =  new DateTime(date('Y-m-d', strtotime($sub->subscription_end_at)));
       // $interval = $origin->diff($target);
       // echo $interval->format('%a');

    // }

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

      $now = new DateTime('now');
            $queue = Queue::find()
                    ->joinWith('restaurant')
                    ->andWhere(['queue_status' => Queue::QUEUE_STATUS_PENDING])
                    ->orderBy(['queue_created_at' => SORT_ASC])
                    ->one();

            if($queue && $queue->restaurant_uuid){

              $queue->queue_status = Queue::QUEUE_STATUS_CREATING;
              if($queue->save()){
                \Yii::$app->netlifyComponent->createSite(parse_url($queue->restaurant->restaurant_domain)['host'], $queue->restaurant->store_branch_name);
              }

            $restaurant = $queue->restaurant;

            // $dirName = "store";
            // if(!file_exists($dirName))
            //   $createStoreFolder = mkdir($dirName);


            //
            // if (!file_exists( $dirName . "/" . $queue->restaurant->store_branch_name )) {
            //   $myFolder = mkdir( $dirName . "/" . $queue->restaurant->store_branch_name);
            // }

            // $buildJsFile =  fopen($dirName . "/" .   $queue->restaurant->store_branch_name . "/build.js", "w") or die("Unable to open file!");
            // fwrite($buildJsFile, Yii::$app->fileGeneratorComponent->createBuildJsFile(Yii::$app->params['apiEndpoint'] . '/v2' ));
            // fclose($buildJsFile);




        $this->stdout("File has been created! \n", Console::FG_RED, Console::BOLD);
      }

    }

        public function actionUpdateSitemap() {

          $stores = Restaurant::find()
                  ->where(['sitemap_require_update' => 1])
                  ->andWhere(['version' => 2])
                  ->all();

            if($stores){
              foreach ($stores as $key => $store) {

                if($store){

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

                $getSitemapXmlSHA = Yii::$app->githubComponent->getFileSHA('sitemap.xml', $store->store_branch_name,);

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
                ->andWhere(['<', 'payment.payment_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 15 MINUTE)')]);

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
     * Method called to Send  reminder if order not picked up in 5 minutes
     */
    public function actionSendReminderEmail() {

        $now = new DateTime('now');
        $orders = Order::find()
                ->where(['order_status' => Order::STATUS_PENDING])
                ->andWhere(['reminder_sent' => 0])
                ->andWhere(['<', 'order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 1 MINUTE)')])
                ->all();

        if ($orders) {

            foreach ($orders as $order) {

                foreach ($order->restaurant->getAgents()->where(['reminder_email' => 1])->all() as $agent) {


                    if ($agent) {
                        $result = \Yii::$app->mailer->compose([
                                    'html' => 'order-reminder-html',
                                        ], [
                                    'order' => $order,
                                    'agent_name' => $agent->agent_name
                                ])
                                ->setFrom([\Yii::$app->params['supportEmail'] => $order->restaurant->name])
                                ->setTo($agent->agent_email)
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
