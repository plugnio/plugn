<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class DashboardAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      'app-assets/vendors/css/vendors.min.css',
      'app-assets/vendors/css/charts/apexcharts.css',
      'app-assets/vendors/css/extensions/tether-theme-arrows.css',
      'app-assets/vendors/css/extensions/tether.min.css',
      'app-assets/vendors/css/extensions/shepherd-theme-default.css',
      'app-assets/css/bootstrap.css',
      'app-assets/css/bootstrap-extended.css',
      'app-assets/css/colors.css',
      'app-assets/css/components.css',
      'app-assets/css/themes/dark-layout.css',
      'app-assets/css/themes/semi-dark-layout.css',
      'app-assets/css/core/menu/menu-types/vertical-menu.css',
      'app-assets/css/core/colors/palette-gradient.css',
      'app-assets/css/pages/dashboard-analytics.css',
      'app-assets/css/pages/card-analytics.css',
      'app-assets/css/pages/dashboard-ecommerce.css',
      'app-assets/css/plugins/tour/tour.css',
      'assets/css/style.css',
      'https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600',
      'app-assets/css/plugins/file-uploaders/dropzone.css',
      'app-assets/css/pages/data-list-view.css',
      'app-assets/vendors/css/tables/datatable/datatables.min.css',
      'app-assets/vendors/css/file-uploaders/dropzone.min.css',
      'app-assets/vendors/css/tables/ag-grid/ag-grid.css',
      'app-assets/vendors/css/tables/ag-grid/ag-theme-material.css',
      'app-assets/css/pages/app-user.css',
      'app-assets/css/pages/aggrid.css',
      'app-assets/vendors/css/forms/select/select2.min.css',
      'app-assets/vendors/css/ui/prism.min.css',




      'css/fileinput.css',

    ];

    public $js = [
      'app-assets/vendors/js/vendors.min.js',
      'app-assets/vendors/js/charts/apexcharts.min.js',
      'app-assets/vendors/js/extensions/tether.min.js',
      'app-assets/vendors/js/extensions/shepherd.min.js',
      'app-assets/js/core/app-menu.js',
      'app-assets/js/core/app.js',
      'app-assets/js/scripts/components.js',
      'app-assets/js/scripts/pages/dashboard-analytics.js',
      'app-assets/js/scripts/pages/dashboard-ecommerce.js',
      'app-assets/js/scripts/ui/data-list-view.js',
      'app-assets/vendors/js/extensions/dropzone.min.js',
      'app-assets/vendors/js/tables/datatable/datatables.min.js',
      'app-assets/vendors/js/tables/datatable/datatables.buttons.min.js',
      'app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js',
      'app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js',
      'app-assets/vendors/js/tables/datatable/dataTables.select.min.js',
      'app-assets/vendors/js/tables/ag-grid/ag-grid-community.min.noStyle.js',
      'app-assets/js/scripts/pages/app-user.js',
      'app-assets/vendors/js/forms/select/select2.full.min.js',
      'app-assets/js/scripts/forms/select/form-select2.js',



      'plugins/daterangepicker/daterangepicker.js',
      'plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',



          'app-assets/vendors/js/ui/prism.min.js',
          'app-assets/js/scripts/extensions/dropzone.js',

                  'js/scripts.js',

          'js/fileinput.js',


           //
           // 'https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/vendors/js/vendors.min.js?id=0eef70ca571453be304a',
           // 'https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/vendors/js/ui/prism.min.js?id=fde910813cf7141eae50',
           // 'https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/vendors/js/charts/apexcharts.min.js?id=bbb1adadc937c1636f35',
           // 'https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/js/core/app-menu.js?id=d7158b834aaadf39f5a8',
           // 'https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/js/core/app.js?id=56696624259ddf3129cc',
           // 'https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/js/scripts/components.js?id=2ab188ae0fa7b622cdb1',
           // 'https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/js/scripts/customizer.js?id=bd6e1f733770a42402a7',
           // 'https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/js/scripts/footer.js?id=b382ec364916c35eff84',

    ];

    public $depends = [
      'yii\web\YiiAsset',
      'yii\web\JqueryAsset',
    ];

}
