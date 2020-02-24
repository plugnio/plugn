<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\DashboardAsset;
use common\widgets\Alert;

DashboardAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <?php $this->beginBody() ?>

        <div class="wrap">

            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                    </li>

                </ul>

            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="index3.html" class="brand-link">
                    <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                         style="opacity: .8">
                    <span class="brand-text font-weight-light">
                        <?= Yii::$app->user->identity->restaurant->name ?>
                    </span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">
                                <?= Yii::$app->user->identity->vendor_name ?>
                            </a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item has-treeview">
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-box']) .
                                        Html::tag('p', '
                                      Items
                                      <i class="fas fa-angle-left right"></i>'), [''], ['class' => 'nav-link']
                                )
                                ?>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">

                                        <?=
                                        Html::a(
                                                Html::tag('i', '', ['class' => 'nav-icon fas fa-th']) .
                                                Html::tag('p', 'Categories'), ['category/index'], ['class' => 'nav-link']
                                        )
                                        ?>

                                    </li>
                                    <li class="nav-item">
                                        <?=
                                        Html::a(
                                                Html::tag('i', '', ['class' => 'nav-icon fas fa-box']) .
                                                Html::tag('p', 'All Items'), ['item/index'], ['class' => 'nav-link']
                                        )
                                        ?>

                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                   <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-shopping-cart']) .
                                        Html::tag('p','Customer orders'), ['order/index'], ['class' => 'nav-link']
                                )
                                ?>
                            </li>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1><?= $this->title ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <?=
                                Breadcrumbs::widget([
                                    'itemTemplate' => "<li class='breadcrumb-item'><i>{link}</i></li>\n", // template for all links
                                    'activeItemTemplate' => "<li class='breadcrumb-item'><i>{link}</i></li>\n", // template for all links
                                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                    'options' => ['class' => 'breadcrumb float-sm-right']
                                ])
                                ?>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <?= Alert::widget() ?>
                        <?= $content ?>
                    </div>
                </section>
            </div>

        </div>
    </div>


    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
