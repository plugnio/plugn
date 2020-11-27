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
use common\models\Plan;
use common\models\Order;
use common\models\Subscription;
use common\models\OpeningHour;
use common\models\ExtraOption;
use common\models\ItemImage;
use common\models\RestaurantTheme;
use \DateTime;
use yii\helpers\Console;
use yii\db\Expression;

/**
 * All Cron actions related to this project
 */
class CronController extends \yii\console\Controller {

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
          $queue->save();

        $restaurant = $queue->restaurant;

        $dirName = "store";
        if(!file_exists($dirName))
          $createStoreFolder = mkdir($dirName);

        $myFolder = mkdir( $dirName . "/" . $queue->restaurant->store_branch_name);
        $myfile =  fopen($dirName . "/" .   $queue->restaurant->store_branch_name . "/build.js", "w") or die("Unable to open file!");


        $apiEndpoint = Yii::$app->params['apiEndpoint'] . '/v1';

        $txt = "

                var fs = require('fs');
                const request = require('request');

                var apiEndPoint;

                var storebranchName = '$restaurant->store_branch_name';

                switch (storebranchName) {
                  case 'develop':
                      apiEndPoint = 'http://localhost/~Saoud/plugn-vendor/vendor-yii2/api/web/v1'
                      break;

                  default:
                      apiEndPoint = '$apiEndpoint'
                }


                var url = apiEndPoint + '/restaurant/get-restaurant-data/' + storebranchName;

                request(url, function(err, res, body) {

                  var response = JSON.parse(body);

                  overwriteIndexHtml(response);
                  overwriteCapacitorConfig(response.app_id, response.name);
                  overwriteGlobalScss(response.custom_css, response.name);
                  overwriteManifest(response.restaurant_uuid, response.name, response.theme_color, response.logo);
                  overwriteEnvironment(response.restaurant_uuid, storebranchName);
                  overwriteAngularFile(storebranchName);
                });

                function overwriteIndexHtml(store) {

                  console.log(store);

                  var facebookPixilId = store.facebook_pixil_id;
                  var googleAnalyticsId = store.google_analytics_id;
                  var storeName = store.name;
                  var storeUuid = store.restaurant_uuid;
                  var storeTagline = store.tagline;
                  var storeLogo = store.logo;
                  var storeDomain = store.restaurant_domain;
                  var storeThemeColor = store.theme_color;

                  var storeContent = store.name;

                  if (store.tagline)
                    storeContent = storeContent + ' | ' + store.tagline;


                  var buildFileJs = `
                              #!/usr/bin/env bash
                              ng build -c=` + storebranchName;
                  fs.writeFileSync('build.sh', buildFileJs);
                  fs.chmod('build.sh', 0o775, (err) => {
                      if (err) throw err;
                      fs.writeFileSync('build.sh', buildFileJs);
                  });

                  var facebookPixilCode = '';
                  if (facebookPixilId) {
                      facebookPixilCode = `

                              <!-- Facebook Pixel Code -->
                              <script>
                                 !function(f,b,e,v,n,t,s)
                                 {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                                 n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                                 if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                                 n.queue=[];t=b.createElement(e);t.async=!0;
                                 t.src=v;s=b.getElementsByTagName(e)[0];
                                 s.parentNode.insertBefore(t,s)}(window, document,'script',
                                 'https://connect.facebook.net/en_US/fbevents.js');
                                 fbq('init', '` + facebookPixilId + `');
                                 fbq('track', 'PageView');
                              </script>
                              <noscript>
                                      <img height='1' width='1' style='display:none'
                                 src='https://www.facebook.com/tr?id= .  facebookPixilId . &ev=PageView&noscript=1'
                                 />
                              </noscript>
                              <!-- End Facebook Pixel Code -->
                              `;
                  }
                  var googleAnalyticsCode = '';
                  if (googleAnalyticsId) {
                      googleAnalyticsCode = `

                              <script>
                                 (function (i, s, o, g, r, a, m) {
                                   i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
                                     (i[r].q = i[r].q || []).push(arguments)
                                   }, i[r].l = 1 * new Date(); a = s.createElement(o),
                                     m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
                                 })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
                              </script>
                              `;
                  }
                  var htmlFile = `

                              <!DOCTYPE html>
                              <html lang='en' dir='ltr'>
                                      <head>
                                              <meta charset='utf-8'/>
                                              <title>` + storeName + `</title>
                                              <base href='/'/>
                                              <meta name='description' content='` + storeContent + ` '>
                                                      <meta name='viewport' content='viewport-fit=cover, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no'/>
                                                      <meta name='format-detection' content='telephone=no'/>
                                                      <meta name='msapplication-tap-highlight' content='no'/>
                                                      <link rel='icon' type='image/png' href='https://res.cloudinary.com/plugn/image/upload/w_100,h_100/restaurants/` + storeUuid + `/logo/` + storeLogo + `'/>
                                                      <link rel='apple-touch-icon' href='https://res.cloudinary.com/plugn/image/upload/w_300,h_300,b_rgb:ffffff/restaurants/` + storeUuid + `/logo/` + storeLogo + `'/>
                                                      <link rel='apple-touch-startup-image' href='https://res.cloudinary.com/plugn/image/upload/w_200,h_200,b_rgb:ffffff/restaurants/` + storeUuid + `/logo/` + storeLogo + `'/>
                                                      <!-- add to homescreen for ios -->
                                                      <meta name='mobile-web-app-capable' content='yes' />
                                                      <meta name='apple-touch-fullscreen' content='yes' />
                                                      <meta name='apple-mobile-web-app-title' content='Expo' />
                                                      <meta name='apple-mobile-web-app-capable' content='yes' />
                                                      <meta name='apple-mobile-web-app-status-bar-style' content='default' />
                                                      <!-- Meta tags for social media -->
                                                      <meta property='og:type' content='website'/>
                                                      <meta property='og:url' content='` + storeDomain + `'/>
                                                      <meta property='og:site_name' content='` + storeContent + `'/>
                                                      <meta property='og:image' itemprop='image primaryImageOfPage' content='https://res.cloudinary.com/plugn/image/upload/w_300,h_300/restaurants/` + storeUuid + `/logo/` + storeLogo + `'/>
                                                      <meta name='twitter:card' content='summary'/>
                                                      <meta name='twitter:domain' content='` + storeDomain + ` '/>
                                                      <meta name='twitter:title' property='og:title' itemprop='name' content='` + storeName + `  | ` + storeTagline + ` '/>
                                                      <meta name='twitter:description' property='og:description' itemprop='description | description' content='` + storeName + `'/>
                                                      <link rel='manifest' href='manifest.webmanifest'>
                                                              <meta name='theme-color' content='` + storeThemeColor + `'>
                                    ` + facebookPixilCode + `

                                                                      <script src='https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.4/bluebird.min.js'></script>
                                                                      <script src='https://secure.gosell.io/js/sdk/tap.min.js'></script>
                                                              </head>
                                                              <body>
                                                                      <app-root></app-root>
                                    ` + googleAnalyticsCode + `

                                                                      <noscript>Please enable JavaScript to continue using this application.</noscript>
                                                              </body>
                                                      </html>
                              `;
                  fs.writeFileSync('src/index.html', htmlFile);
                }


                function overwriteCapacitorConfig(storeAppId, storeName) {
                  var capacitorConfig = `
                              {
                                  " . '"appId"' . ":  " . '"` + storeAppId + `"' . ",
                                  " . '"appName"' . ":  " . '"` + storeName + `"' . ",
                                  " . '"bundledWebRuntime"' . ": false,
                                  " . '"npmClient"' . ":  " . '"npm"' . ",
                                  " . '"webDir"' . ":  " . '"www"' . ",
                                  " . '"plugnins"' . ":  " . '"www"' . ": {
                                  " . '"SplashScreen"' . ": {
                                  " . '"launchShowDuration"' . ": 0
                              }
                              },
                              " . '"cordova"' . ": {
                                  " . '"preferences"' . ": {
                                  " . '"ScrollEnabled"' . ":  " . '"false"' . ",
                                  " . '"android-minSdkVersion"' . ":  " . '"19"' . ",
                                  " . '"BackupWebStorage"' . ":  " . '"none"' . ",
                                  " . '"SplashMaintainAspectRatio"' . ":  " . '"true"' . ",
                                  " . '"FadeSplashScreenDuration"' . ":  " . '"300"' . ",
                                  " . '"SplashShowOnlyFirstTime"' . ":  " . '"false"' . ",
                                  " . '"SplashScreen"' . ":  " . '"screen"' . ",
                                  " . '"SplashScreenDelay"' . ":  " . '"3000"' . "
                                  }
                               }
                              }
                              `;
                  fs.writeFileSync('capacitor.config.json', capacitorConfig);
                }

                function overwriteGlobalScss(storeCustomCss) {

                  if (storeCustomCss)
                      fs.appendFileSync('src/global.scss', storeCustomCss);
                  var dir = 'src/assets/icons';
                  if (!fs.existsSync(dir)) {
                      fs.mkdirSync(dir);
                  }
                }

                function overwriteManifest(storeUuid, storeName, storeThemeColor, storeLogo) {


                  var download = function(uri, filename, callback) {
                      request.head(uri, function(err, res, body) {
                          request(uri).pipe(fs.createWriteStream(filename)).on('close', callback);
                      });
                  };
                  download('https://res.cloudinary.com/plugn/image/upload/w_72,h_72/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-72x72.png', function() {});
                  download('https://res.cloudinary.com/plugn/image/upload/w_96,h_96/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-96x96.png', function() {});
                  download('https://res.cloudinary.com/plugn/image/upload/w_128,h_128/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-128x128.png', function() {});
                  download('https://res.cloudinary.com/plugn/image/upload/w_144,h_144/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-144x144.png', function() {});
                  download('https://res.cloudinary.com/plugn/image/upload/w_152,h_152/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-152x152.png', function() {});
                  download('https://res.cloudinary.com/plugn/image/upload/w_192,h_192/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-192x192.png', function() {});
                  download('https://res.cloudinary.com/plugn/image/upload/w_384,h_384/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-384x384.png', function() {});
                  download('https://res.cloudinary.com/plugn/image/upload/w_512,h_512/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-512x512.png', function() {});
                  var manifestFile = `
                              {
                              " . '"name"' . ": " . '"` + storeName + `"' . ",
                              " . '"short_name"' . ": " . '"` + storeName + `"' . ",
                              " . '"theme_color"' . ": " . '"` + storeThemeColor + `"' . ",
                              " . '"background_color"' . ": " . '"#fafafa"' . ",
                              " . '"display"' . ": " . '"standalone"' . ",
                              " . '"scope"' . ": " . '"./"' . ",
                              " . '"start_url"' . ": " . '"./"' . ",
                              " . '"icons"' . ": [
                              {
                                  " . '"src"' . ": " . '"assets/icons/icon-72x72.png"' . ",
                                  " . '"sizes"' . ": " . '"72x72"' . ",
                                  " . '"type"' . ": " . '"image/png"' . ",
                                  " . '"purpose"' . ":" . '"maskable any"' . "
                              },
                              {
                                  " . '"src"' . ": " . '"assets/icons/icon-96x96.png"' . ",
                                  " . '"sizes"' . ": " . '"96x96"' . ",
                                  " . '"type"' . ": " . '"image/png"' . ",
                                  " . '"purpose"' . ": " . '"maskable any"' . "
                              },
                              {
                                  " . '"src"' . ": " . '"assets/icons/icon-128x128.png"' . ",
                                  " . '"sizes"' . ": " . '"128x128"' . ",
                                  " . '"type"' . ": " . '"image/png"' . ",
                                  " . '"purpose"' . ": " . '"maskable any"' . "
                              },
                              {
                                  " . '"src"' . ": " . '"assets/icons/icon-144x144.png"' . ",
                                  " . '"sizes"' . ": " . '"144x144"' . ",
                                  " . '"type"' . ": " . '"image/png"' . ",
                                  " . '"purpose"' . ": " . '"maskable any"' . "
                              },
                              {
                                  " . '"src"' . ": " . '"assets/icons/icon-152x152.png"' . ",
                                  " . '"sizes"' . ": " . '"152x152"' . ",
                                  " . '"type"' . ": " . '"image/png"' . ",
                                  " . '"purpose"' . ": " . '"maskable any"' . "
                              },
                              {
                                  " . '"src"' . ": " . '"assets/icons/icon-192x192.png"' . ",
                                  " . '"sizes"' . ": " . '"192x192"' . ",
                                  " . '"type"' . ": " . '"image/png"' . ",
                                  " . '"purpose"' . ": " . '"maskable any"' . "
                              },
                              {
                                  " . '"src"' . ": " . '"assets/icons/icon-384x384.png"' . ",
                                  " . '"sizes"' . ": " . '"384x384"' . ",
                                  " . '"type"' . ": " . '"image/png"' . ",
                                  " . '"purpose"' . ": " . '"maskable any"' . "
                              },
                              {
                                  " . '"src"' . ": " . '"assets/icons/icon-512x512.png"' . ",
                                  " . '"sizes"' . ": " . '"512x512"' . ",
                                  " . '"type"' . ": " . '"image/png"' . ",
                                  " . '"purpose"' . ": " . '"maskable any"' . "
                              }
                              ]
                              }
                              `;
                  fs.writeFileSync('src/manifest.webmanifest', manifestFile);
                }


                function overwriteEnvironment(storeUuid, storebranchName) {

                  var environmentFile = `export const environment = {
                              production: true,
                              envName: 'prod',
                              apiEndpoint: '$apiEndpoint',
                              restaurantUuid : '` + storeUuid + `'
                              };`;
                  fs.writeFileSync('src/environments/environment.' + storebranchName + '.ts', environmentFile);

                }

                function overwriteAngularFile(storebranchName) {

                  var angularFile = `{

                                  " . '"$schema"' . ": " . '"./node_modules/@angular/cli/lib/config/schema.json"' . ",
                                  " . '"version"' . ": 1,
                                  " . '"defaultProject"' . ": " . '"app"' . ",
                                  " . '"newProjectRoot"' . ": " . '"projects"' . ",
                                  " . '"projects"' . ": {
                                  " . '"app"' . ": {
                                  " . '"root"' . ": " . '""' . ",
                                  " . '"sourceRoot"' . ": " . '"src"' . ",
                                  " . '"projectType"' . ": " . '"application"' . ",
                                  " . '"prefix"' . ": " . '"app"' . ",
                                  " . '"schematics"' . ": {},
                                  " . '"architect"' . ": {
                                  " . '"build"' . ": {
                                  " . '"builder"' . ": " . '"@angular-devkit/build-angular:browser"' . ",
                                  " . '"options"' . ": {
                                  " . '"outputPath"' . ": " . '"www"' . ",
                                  " . '"index"' . ": " . '"src/index.html"' . ",
                                  " . '"main"' . ": " . '"src/main.ts"' . ",
                                  " . '"polyfills"' . ": " . '"src/polyfills.ts"' . ",
                                  " . '"tsConfig"' . ": " . '"tsconfig.app.json"' . ",
                              " . '"assets"' . ": [
                              {
                                  " . '"glob"' . ": " . '"**/*"' . ",
                                  " . '"input"' . ": " . '"src/assets"' . ",
                                  " . '"output"' . ": " . '"assets"' . "
                              },
                              {
                                  " . '"glob"' . ": " . '"**/*.svg"' . ",
                                  " . '"input"' . ": " . '"node_modules/ionicons/dist/ionicons/svg"' . ",
                                  " . '"output"' . ": " . '"./svg"' . "
                              },
                                  " . '"src/manifest.webmanifest"' . ",
                                  " . '"src/_redirects"' . "
                              ],
                              " . '"styles"' . ": [
                              {
                               " . '"input"' . ": " . '"src/theme/variables.scss"' . "
                              },
                              {
                               " . '"input"' . ": " . '"src/global.scss"' . "
                              }
                              ],
                               " . '"scripts"' . ": []
                              },
                                  " . '"configurations"' . ": {
                                  " . '"` + storebranchName + `"' . ": {
                                  " . '"fileReplacements"' . ": [
                              {
                                  " . '"replace"' . ": " . '"src/environments/environment.ts"' . ",
                                  " . '"with"' . ": " . '"src/environments/environment.` + storebranchName + `.ts"' . "
                              }
                              ],
                                  " . '"optimization"' . ": true,
                                  " . '"outputHashing"' . ": " . '"all"' . ",
                                  " . '"sourceMap"' . ": false,
                                  " . '"extractCss"' . ": true,
                                  " . '"namedChunks"' . ": false,
                                  " . '"aot"' . ": true,
                                  " . '"extractLicenses"' . ": true,
                                  " . '"vendorChunk"' . ": false,
                                  " . '"buildOptimizer"' . ": true,
                              " . '"budgets"' . ": [
                              {
                                  " . '"type"' . ": " . '"initial"' . ",
                                  " . '"maximumWarning"' . ": " . '"2mb"' . ",
                                  " . '"maximumError"' . ": " . '"5mb"' . "
                              }
                              ],
                                  " . '"serviceWorker"' . ": true,
                                  " . '"ngswConfigPath"' . ": " . '"ngsw-config.json"' . "
                              },
                                  " . '"ci"' . ": {
                                  " . '"progress"' . ": false
                              }
                              }
                              },
                                  " . '"serve"' . ": {
                                  " . '"builder"' . ": " . '"@angular-devkit/build-angular:dev-server"' . ",
                                  " . '"options"' . ": {
                                  " . '"browserTarget"' . ": " . '"app:build"' . "
                              },
                                  " . '"configurations"' . ": {
                                  " . '"` + storebranchName + `"' . ": {
                                  " . '"browserTarget"' . ": " . '"app:build:` + storebranchName + `"' . "
                              },
                                  " . '"ci"' . ": {
                                  " . '"progress"' . ": false
                              }
                              }
                              },
                                  " . '"extract-i18n"' . ": {
                                  " . '"builder"' . ": " . '"@angular-devkit/build-angular:extract-i18n"' . ",
                                  " . '"options"' . ": {
                                  " . '"browserTarget"' . ": " . '"app:build"' . "
                              }
                              },
                                  " . '"test"' . ": {
                                  " . '"builder"' . ": " . '"@angular-devkit/build-angular:karma"' . ",
                                  " . '"options"' . ": {
                                  " . '"main"' . ": " . '"src/test.ts"' . ",
                                  " . '"polyfills"' . ": " . '"src/polyfills.ts"' . ",
                                  " . '"tsConfig"' . ": " . '"tsconfig.spec.json"' . ",
                                  " . '"karmaConfig"' . ": " . '"karma.conf.js"' . ",
                                  " . '"styles"' . ": [],
                                  " . '"scripts"' . ": [],
                                  " . '"assets"' . ": [
                              {
                                  " . '"glob"' . ": " . '"favicon.ico"' . ",
                                  " . '"input"' . ": " . '"src/"' . ",
                                  " . '"output"' . ": " . '"/"' . "
                              },
                              {
                                  " . '"glob"' . ": " . '"* */*"' . ",
                                  " . '"input"' . ": " . '"src/assets"' . ",
                                  " . '"output"' . ": " . '"/assets"' . "
                              },
                                  " . '"src/manifest.webmanifest"' . "
                              ]
                              },
                                  " . '"configurations"' . ": {
                                  " . '"ci"' . ": {
                                  " . '"progress"' . ": false,
                                  " . '"watch"' . ": false
                              }
                              }
                              },
                                  " . '"lint"' . ": {
                                  " . '"builder"' . ": " . '"@angular-devkit/build-angular:tslint"' . ",
                                  " . '"options"' . ": {
                                  " . '"tsConfig"' . ": [
                                  " . '"tsconfig.app.json"' . ",
                                  " . '"tsconfig.spec.json"' . ",
                                  " . '"e2e/tsconfig.json"' . "
                              ],
                                  " . '"exclude"' . ": [" . '"**/node_modules/**"' . "]
                              }
                              },
                                  " . '"e2e"' . ": {
                                  " . '"builder"' . ": " . '"@angular-devkit/build-angular:protractor"' . ",
                                  " . '"options"' . ": {
                                  " . '"protractorConfig"' . ": " . '"e2e/protractor.conf.js"' . ",
                                  " . '"devServerTarget"' . ": " . '"app:serve"' . "
                              },
                                  " . '"configurations"' . ": {
                                  " . '"production"' . ": {
                                  " . '"devServerTarget"' . ": " . '"app:serve:production"' . "
                              },
                                  " . '"ci"' . ": {
                                  " . '"devServerTarget"' . ": " . '"app:serve:ci"' . "
                              }
                              }
                              },
                                  " . '"ionic-cordova-build"' . ": {
                                  " . '"builder"' . ": " . '"@ionic/angular-toolkit:cordova-build"' . ",
                                  " . '"options"' . ": {
                                  " . '"browserTarget"' . ": " . '"app:build"' . "
                              },
                                  " . '"configurations"' . ": {
                                  " . '"production"' . ": {
                                  " . '"browserTarget"' . ": " . '"app:build:production"' . "
                              }
                              }
                              },
                                  " . '"ionic-cordova-serve"' . ": {
                                  " . '"builder"' . ": " . '"@ionic/angular-toolkit:cordova-serve"' . ",
                                  " . '"options"' . ": {
                                  " . '"cordovaBuildTarget"' . ": " . '"app:ionic-cordova-build"' . ",
                                  " . '"devServerTarget"' . ": " . '"app:serve"' . "
                              },
                                  " . '"configurations"' . ": {
                                  " . '"production"' . ": {
                                  " . '"cordovaBuildTarget"' . ": " . '"app:ionic-cordova-build:production"' . ",
                                  " . '"devServerTarget"' . ": " . '"app:serve:production"' . "
                               }
                               }
                                  }
                               }
                                  }
                              },
                                  " . '"cli"' . ": {
                                  " . '"defaultCollection"' . ": " . '"@ionic/angular-toolkit"' . "
                              },
                                  " . '"schematics"' . ": {
                                  " . '"@ionic/angular-toolkit:component"' . ": {
                                  " . '"styleext"' . ": " . '"scss"' . "
                              },
                                  " . '"@ionic/angular-toolkit:page"' . ": {
                                  " . '"styleext"' . ": " . '"scss"' . "
                               }
                                  }
                              }`;

                  fs.writeFileSync('angular.json', angularFile);
                }
        ";


        fwrite($myfile, $txt);

        fclose($myfile);

        $queue->queue_status = Queue::QUEUE_STATUS_COMPLETE;
        $queue->save(false);

        $this->stdout("File has been created! \n", Console::FG_RED, Console::BOLD);
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
