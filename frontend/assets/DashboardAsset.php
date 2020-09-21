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
        'app-assets/css/pages/dashboard-analytics.css',
        'app-assets/css/pages/card-analytics.css',
        'app-assets/css/pages/dashboard-ecommerce.css',
        'app-assets/css/plugins/tour/tour.css',
        // 'assets/css/style.css',
        'https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600',
        'app-assets/css/pages/data-list-view.css',
        'app-assets/vendors/css/tables/datatable/datatables.min.css',
        'app-assets/vendors/css/tables/ag-grid/ag-grid.css',
        'app-assets/vendors/css/tables/ag-grid/ag-theme-material.css',
        'app-assets/css/pages/app-user.css',
        'app-assets/css/pages/aggrid.css',
        'app-assets/vendors/css/forms/select/select2.min.css',
        'css/fileinput.css',
        'app-assets/vendors/css/vendors.min.css',
        'app-assets/vendors/css/ui/prism.min.css',
        'app-assets/vendors/css/file-uploaders/dropzone.min.css',
        // 'assets/css/style.css',
        'app-assets/css/bootstrap.css',
        'app-assets/css/bootstrap-extended.css',
        'app-assets/css/colors.css',
        'app-assets/css/components.css',
        'app-assets/css/themes/dark-layout.css',
        'app-assets/css/themes/semi-dark-layout.css',
        'app-assets/css/core/menu/menu-types/vertical-menu.css',
        'app-assets/css/core/colors/palette-gradient.css',
        'app-assets/css/plugins/file-uploaders/dropzone.css',
        'app-assets/vendors/css/editors/quill/quill.snow.css',
        'app-assets/vendors/css/charts/apexcharts.css',
        'app-assets/vendors/css/pickers/pickadate/pickadate.css',
        'app-assets/css/pages/invoice.css',
        'app-assets/vendors/css/pickers/pickadate/pickadate.css',
        'app-assets/vendors/css/pickers/pickadate/pickadate.css',
        'css/bootstrap-duallistbox.css'
    ];

    public $js = [
        'app-assets/vendors/js/vendors.min.js',
        'app-assets/vendors/js/extensions/dropzone.min.js',
        'app-assets/vendors/js/ui/prism.min.js',
        'app-assets/js/core/app-menu.js',
        'app-assets/js/core/app.js',
        'app-assets/js/scripts/components.js',
        'app-assets/js/scripts/extensions/dropzone.js',
        'app-assets/vendors/js/charts/apexcharts.min.js',
        'app-assets/vendors/js/extensions/tether.min.js',
        'app-assets/vendors/js/extensions/shepherd.min.js',
        'app-assets/js/scripts/pages/dashboard-analytics.js',
        'app-assets/js/scripts/pages/dashboard-ecommerce.js',
        'app-assets/js/scripts/ui/data-list-view.js',
        'app-assets/vendors/js/tables/datatable/datatables.min.js',
        'app-assets/vendors/js/tables/datatable/datatables.buttons.min.js',
        'app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js',
        'app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js',
        'app-assets/vendors/js/tables/datatable/dataTables.select.min.js',
        'app-assets/vendors/js/tables/ag-grid/ag-grid-community.min.noStyle.js',
        'app-assets/js/scripts/pages/app-user.js',
        'app-assets/vendors/js/forms/select/select2.full.min.js',
        'app-assets/js/scripts/forms/select/form-select2.js',
        'js/daterangepicker/daterangepicker.js',
        'js/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',
        'js/scripts.js',
        'js/fileinput.js',
        'app-assets/vendors/js/charts/apexcharts.min.js',
        'app-assets/js/scripts/charts/chart-apex.js',
        'app-assets/vendors/js/editors/quill/katex.min.js',
        'app-assets/vendors/js/editors/quill/highlight.min.js',
        'app-assets/vendors/js/editors/quill/quill.min.js',
        'app-assets/vendors/js/extensions/jquery.steps.min.js',
        'app-assets/vendors/js/forms/validation/jquery.validate.min.js',
        'app-assets/js/scripts/pages/invoice.js',
        'app-assets/vendors/js/pickers/pickadate/picker.js',
        'app-assets/vendors/js/pickers/pickadate/picker.date.js',
        'app-assets/vendors/js/pickers/pickadate/picker.time.js',
        'app-assets/vendors/js/pickers/pickadate/legacy.js',
        'app-assets/js/scripts/pickers/dateTime/pick-a-datetime.js',

        // 'https://code.jquery.com/jquery-3.2.1.slim.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js',
        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js',
        'js/jquery.bootstrap-duallistbox.min.js'
    ];

    public $depends = [
      'yii\web\YiiAsset',
      'yii\web\JqueryAsset',
    ];

}
