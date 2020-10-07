
            

            var facebookPixilId = null;
            var googleAnalyticsId = null;
            var storeName = 'JUICED';
            var storeUuid= 'rest_84bc571c-07ed-11eb-bd14-78c05c8a9e7d';
            var storeTagline= 'JUICED';
            var storeLogo= '';
            var storeDomain= '';
            var storeThemeColor = '#3880ff';
            var storeAppId = '';
            var storeCustomCss = null;


            var fs = require('fs');
const request = require('request');


console.log('Pre build');





  if(facebookPixilId){
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
      <noscript><img height='1' width='1' style='display:none'
        src='https://www.facebook.com/tr?id= .  facebookPixilId . &ev=PageView&noscript=1'
      /></noscript>
      <!-- End Facebook Pixel Code -->
    `;
  }


  var googleAnalyticsCode = '';

  if(googleAnalyticsId){
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

  <meta name='description' content='` + storeName + ` | ` + storeTagline + ` '>

  <meta name='viewport' content='viewport-fit=cover, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no'/>
  <meta name='format-detection' content='telephone=no'/>
  <meta name='msapplication-tap-highlight' content='no'/>

  <link rel='icon' type='image/png' href='https://res.cloudinary.com/plugn/image/upload/w_16,h_16/restaurants/` + storeUuid + `/logo/` + storeLogo + `'/>
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
  <meta property='og:site_name' content='` + storeName + ` | ` + storeTagline + `'/>
  <meta property='og:image' itemprop='image primaryImageOfPage' content='https://res.cloudinary.com/plugn/image/upload/w_300,h_300/restaurants/` + storeUuid + `/logo/` + storeLogo + `'/>
  <meta name='twitter:card' content='summary'/>
  <meta name='twitter:domain' content='` + storeDomain + ` '/>
  <meta name='twitter:title' property='og:title' itemprop='name' content='` + storeName + `  | ` + storeTagline + ` '/>
  <meta name='twitter:description' property='og:description' itemprop='description | description' content='` + storeName + `'/>


  <link rel='manifest' href='manifest.webmanifest'>
  <meta name='theme-color' content='` + storeThemeColor + `'>



  `+ facebookPixilCode + `
  


  <script src='https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.4/bluebird.min.js'></script>
  <script src='https://secure.gosell.io/js/sdk/tap.min.js'></script>


</head>

<body>
  <app-root></app-root>


  `+ googleAnalyticsCode + `



  <noscript>Please enable JavaScript to continue using this application.</noscript>
</body>

</html>
      `;


  fs.writeFileSync('src/index.html', htmlFile);






  var capacitorConfig = `
  {
    'appId':  '` + storeAppId + `',
    'appName':  '` + storeName + `',
    'bundledWebRuntime': false,
    'npmClient': 'npm',
    'webDir': 'www',
    'plugins': {
      'SplashScreen': {
        'launchShowDuration': 0
      }
    },
    'cordova': {
      'preferences': {
        'ScrollEnabled': 'false',
        'android-minSdkVersion': '19',
        'BackupWebStorage': 'none',
        'SplashMaintainAspectRatio': 'true',
        'FadeSplashScreenDuration': '300',
        'SplashShowOnlyFirstTime': 'false',
        'SplashScreen': 'screen',
        'SplashScreenDelay': '3000'
      }
    }
  }
  
      `;


  fs.writeFileSync('capacitor.config.json', capacitorConfig);



                



  fs.appendFileSync('src/global.scss', storeCustomCss);





  var dir = 'src/assets/icons';

  if (!fs.existsSync(dir)) {
    fs.mkdirSync(dir);
  }


  var download = function (uri, filename, callback) {
    request.head(uri, function (err, res, body) {
      request(uri).pipe(fs.createWriteStream(filename)).on('close', callback);
    });
  };

  download('https://res.cloudinary.com/plugn/image/upload/w_72,h_72/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-72x72.png', function () { });
  download('https://res.cloudinary.com/plugn/image/upload/w_96,h_96/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-96x96.png', function () { });
  download('https://res.cloudinary.com/plugn/image/upload/w_128,h_128/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-128x128.png', function () { });
  download('https://res.cloudinary.com/plugn/image/upload/w_144,h_144/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-144x144.png', function () { });
  download('https://res.cloudinary.com/plugn/image/upload/w_152,h_152/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-152x152.png', function () { });
  download('https://res.cloudinary.com/plugn/image/upload/w_192,h_192/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-192x192.png', function () { });
  download('https://res.cloudinary.com/plugn/image/upload/w_384,h_384/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-384x384.png', function () { });
  download('https://res.cloudinary.com/plugn/image/upload/w_512,h_512/restaurants/' + storeUuid + '/logo/' + storeLogo, 'src/assets/icons/icon-512x512.png', function () { });

  
  var manifestFile = `
   {
    'name': '` + storeName + `',
    'short_name': '` + storeName + `',
    'theme_color': '` + storeThemeColor + `',
    'background_color': '#fafafa',
    'display': 'standalone',
    'scope': './',
    'start_url': './',
    'icons': [
        {
        'src': 'assets/icons/icon-72x72.png',
        'sizes': '72x72',
        'type': 'image/png',
        'purpose': 'maskable any'
      },
      {
        'src': 'assets/icons/icon-96x96.png',
        'sizes': '96x96',
        'type': 'image/png',
        'purpose': 'maskable any'
      },
      {
        'src': 'assets/icons/icon-128x128.png',
        'sizes': '128x128',
        'type': 'image/png',
        'purpose': 'maskable any'
      },
      {
        'src': 'assets/icons/icon-144x144.png',
        'sizes': '144x144',
        'type': 'image/png',
        'purpose': 'maskable any'
      },
      {
        'src': 'assets/icons/icon-152x152.png',
        'sizes': '152x152',
        'type': 'image/png',
        'purpose': 'maskable any'
      },
      {
        'src': 'assets/icons/icon-192x192.png',
        'sizes': '192x192',
        'type': 'image/png',
        'purpose': 'maskable any'
      },
      {
        'src': 'assets/icons/icon-384x384.png',
        'sizes': '384x384',
        'type': 'image/png',
        'purpose': 'maskable any'
      },
      {
        'src': 'assets/icons/icon-512x512.png',
        'sizes': '512x512',
        'type': 'image/png',
        'purpose': 'maskable any'
      }
    ]
}
   `;

  fs.writeFileSync('src/manifest.webmanifest', manifestFile);




  var environmentFile = `export const environment = {
    production: false,
    envName: 'prod',
    apiEndpoint: 'https://api.plugn.io/v1',
    restaurantUuid : `+ storeUuid +`
  };`;
  
  fs.writeFileSync('src/environments/environment.'+  storeUuid  + '.ts', environmentFile);



  var angularFile = `{
    "$schema": './node_modules/@angular/cli/lib/config/schema.json',
    'version': 1,
    'defaultProject': 'app',
    'newProjectRoot': 'projects',
    'projects': {
      'app': {
        'root': '',
        'sourceRoot': 'src',
        'projectType': 'application',
        'prefix': 'app',
        'schematics': {},
        'architect': {
          'build': {
            'builder': '@angular-devkit/build-angular:browser',
            'options': {
              'outputPath': 'www',
              'index': 'src/index.html',
              'main': 'src/main.ts',
              'polyfills': 'src/polyfills.ts',
              'tsConfig': 'tsconfig.app.json',
              'assets': [
                {
                  'glob': '**/*',
                  'input': 'src/assets',
                  'output': 'assets'
                },
                {
                  'glob': '**/*.svg',
                  'input': 'node_modules/ionicons/dist/ionicons/svg',
                  'output': './svg'
                },
                'src/manifest.webmanifest',
                'src/_redirects'
              ],
              'styles': [
                {
                  'input': 'src/theme/variables.scss'
                },
                {
                  'input': 'src/global.scss'
                }
              ],
              'scripts': []
            },
            'configurations': {
                '`+ storeName +`': {
                  'fileReplacements': [
                    {
                      'replace': 'src/environments/environment.ts',
                      'with': 'src/environments/environment.`+ storeName +`.ts'
                    }
                  ],
                  'optimization': true,
                  'outputHashing': 'all',
                  'sourceMap': false,
                  'extractCss': true,
                  'namedChunks': false,
                  'aot': true,
                  'extractLicenses': true,
                  'vendorChunk': false,
                  'buildOptimizer': true,
                  'budgets': [
                    {
                      'type': 'initial',
                      'maximumWarning': '2mb',
                      'maximumError': '5mb'
                    }
                  ],
                'serviceWorker': true,
                'ngswConfigPath': 'ngsw-config.json'
                },
              'ci': {
                'progress': false
              }
            }
          },
          'serve': {
            'builder': '@angular-devkit/build-angular:dev-server',
            'options': {
              'browserTarget': 'app:build'
            },
            'configurations': {
              '`+ storeName +`': {
                'browserTarget': 'app:build:`+ storeName +`'
              },
              'ci': {
                'progress': false
              }
            }
          },
          'extract-i18n': {
            'builder': '@angular-devkit/build-angular:extract-i18n',
            'options': {
              'browserTarget': 'app:build'
            }
          },
          'test': {
            'builder': '@angular-devkit/build-angular:karma',
            'options': {
              'main': 'src/test.ts',
              'polyfills': 'src/polyfills.ts',
              'tsConfig': 'tsconfig.spec.json',
              'karmaConfig': 'karma.conf.js',
              'styles': [],
              'scripts': [],
              'assets': [
                {
                  'glob': 'favicon.ico',
                  'input': 'src/',
                  'output': '/'
                },
                {
                  'glob': '**/*',
                  'input': 'src/assets',
                  'output': '/assets'
                },
                'src/manifest.webmanifest'
              ]
            },
            'configurations': {
              'ci': {
                'progress': false,
                'watch': false
              }
            }
          },
          'lint': {
            'builder': '@angular-devkit/build-angular:tslint',
            'options': {
              'tsConfig': [
                'tsconfig.app.json',
                'tsconfig.spec.json',
                'e2e/tsconfig.json'
              ],
              'exclude': ['**/node_modules/**']
            }
          },
          'e2e': {
            'builder': '@angular-devkit/build-angular:protractor',
            'options': {
              'protractorConfig': 'e2e/protractor.conf.js',
              'devServerTarget': 'app:serve'
            },
            'configurations': {
              'production': {
                'devServerTarget': 'app:serve:production'
              },
              'ci': {
                'devServerTarget': 'app:serve:ci'
              }
            }
          },
          'ionic-cordova-build': {
            'builder': '@ionic/angular-toolkit:cordova-build',
            'options': {
              'browserTarget': 'app:build'
            },
            'configurations': {
              'production': {
                'browserTarget': 'app:build:production'
              }
            }
          },
          'ionic-cordova-serve': {
            'builder': '@ionic/angular-toolkit:cordova-serve',
            'options': {
              'cordovaBuildTarget': 'app:ionic-cordova-build',
              'devServerTarget': 'app:serve'
            },
            'configurations': {
              'production': {
                'cordovaBuildTarget': 'app:ionic-cordova-build:production',
                'devServerTarget': 'app:serve:production'
              }
            }
          }
        }
      }
    },
    'cli': {
      'defaultCollection': '@ionic/angular-toolkit'
    },
    'schematics': {
      '@ionic/angular-toolkit:component': {
        'styleext': 'scss'
      },
      '@ionic/angular-toolkit:page': {
        'styleext': 'scss'
      }
    }
  }`;
  
  
  fs.writeFileSync('angular.json', angularFile);



  var buildFileJs = `
  #!/usr/bin/env bash
  
  ng build -c=`+storeName;

  fs.writeFileSync('build.sh', buildFileJs);


        