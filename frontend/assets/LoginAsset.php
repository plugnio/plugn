<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class LoginAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

  'https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600',
  'app-assets/vendors/css/vendors.min.css',
  'app-assets/css/bootstrap.css',
  'app-assets/css/bootstrap-extended.css',
  'app-assets/css/colors.css',
  'app-assets/css/components.css',
  'app-assets/css/themes/dark-layout.css',
  'app-assets/css/themes/semi-dark-layout.css',
  'app-assets/css/core/menu/menu-types/vertical-menu.css',
  'app-assets/css/core/colors/palette-gradient.css',
  'app-assets/css/pages/authentication.css',
  'assets/css/style.css',
  'app-assets/css/plugins/forms/wizard.css',
  'css/intlTelInput.css',

    ];
    public $js = [
      'app-assets/vendors/js/vendors.min.js',
      'app-assets/js/core/app-menu.js',
      'app-assets/js/core/app.js',
      'app-assets/js/scripts/components.js',
      // 'app-assets/vendors/js/extensions/jquery.steps.min.js',
      'app-assets/vendors/js/forms/validation/jquery.validate.min.js',
      // 'app-assets/js/scripts/forms/wizard-steps.js',
      'js/intlTelInput-jquery.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

}
