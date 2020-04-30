<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\DashboardAsset;
use common\widgets\Alert;
use common\models\Restaurant;

DashboardAsset::register($this);

$restaurant_model = Restaurant::findOne($this->params['restaurant_uuid']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title>
            <?= Html::encode($this->title) ?>
        </title>
        <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />


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

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <?=
                        Html::a('<i style="font-size: 21px;" class="fas fa-sign-out-alt"></i>', ['site/logout'], ['class' => 'nav-link',
                            'data' => [
                                'method' => 'post',
                            ]
                        ])
                        ?>
                    </li>
                </ul>


            </nav>

            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->

                <?=
                Html::a('<img src="' . $restaurant_model->getRestaurantLogoUrl() . '"alt="' . $restaurant_model->name . ' Logo" class="brand-image img-circle elevation-3" style="opacity: .8">'
                        . '<span class="brand-text font-weight-light">'
                        . $restaurant_model->name
                        . '</span>'
                        , ['site/index', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'brand-link']);
                ?>

                <!-- Sidebar -->
                <div class="sidebar">

                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/img/avatar.jpg' ?>" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <?= Html::a(Yii::$app->user->identity->agent_name, ['agent/index', 'restaurantUuid' => $this->params['restaurant_uuid']]) ?>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                            <li class="nav-item">

                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-home']) .
                                        Html::tag('p', 'Dashboard'), ['site/index', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'nav-link']
                                )
                                ?>

                            </li>
                            <li class="nav-item">

                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-th']) .
                                        Html::tag('p', 'Categories'), ['category/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
                                )
                                ?>

                            </li>
                            <li class="nav-item">
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-box']) .
                                        Html::tag('p', 'Items'), ['item/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
                                )
                                ?>

                            </li>
                            </li>
                            <li class="nav-item has-treeview">
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-shopping-cart']) .
                                        Html::tag('p', 'Customer orders'), ['order/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
                                )
                                ?>
                            </li>
                            <li class="nav-item has-treeview">
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-money-bill-wave']) .
                                        Html::tag('p', 'Refund'), ['refund/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
                                )
                                ?>
                            </li>
                            <li class="nav-item has-treeview">
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-user']) .
                                        Html::tag('p', 'Customers'), ['customer/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
                                )
                                ?>
                            </li>

                            <li class="nav-item">
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-store']) .
                                        Html::tag('p', 'Store Info'), ['restaurant/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
                                )
                                ?>

                            </li>
                            <li class="nav-item">
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-paint-brush']) .
                                        Html::tag('p', 'Theme'), ['restaurant-theme/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
                                )
                                ?>

                            </li>
                            <li class="nav-item">
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-truck']) .
                                        Html::tag('p', 'Delivery Zone'), ['restaurant-delivery/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
                                )
                                ?>

                            </li>
                            <!--<li class="nav-item">-->
                            <?php
//                                Html::a(
//                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-clock']) .
//                                        Html::tag('p', 'Working Hours'), ['working-hours/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
//                                )
//
                            ?>

                            <!--</li>-->
                            <li class="nav-item">
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-store']) .
                                        Html::tag('p', "Store's Branches"), ['restaurant-branch/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
                                )
                                ?>

                            </li>
                            </li>
                            <?php if (count(Yii::$app->ownedAccountManager->getOwnedRestaurants()) > 1) { ?>
                                <li class="nav-header">Your stores</li>
                                <?php
                                foreach (Yii::$app->ownedAccountManager->getOwnedRestaurants() as $ownedRestaurant) {
                                    if ($ownedRestaurant->restaurant_uuid != $this->params['restaurant_uuid']) {
                                        ?>
                                        <li class="nav-item">
                                            <?=
                                            Html::a(
                                                    Html::img($ownedRestaurant->getRestaurantLogoUrl(), ['class' => 'brand-image img-circle elevation-3', 'style' => 'opacity: .8; margin-right: .5rem; margin-top: -3px; max-height: 33px; width: auto;']) .
                                                    Html::tag('p', $ownedRestaurant->name), ['site/vendor-dashboard', 'id' => $ownedRestaurant->restaurant_uuid], ['class' => 'nav-link']
                                            )
                                            ?>

                                        </li>
                                        <?php
                                    }
                                }
                            }
                            ?>
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
                                    'homeLink' => [
                                        'label' => Yii::t('yii', 'Dashboard'),
                                        'url' => ['site/index', 'id' => $this->params['restaurant_uuid']],
                                    ],
                                    'itemTemplate' => "<li class='breadcrumb-item'><i>{link}</i></li>\n", // template for all links
                                    'activeItemTemplate' => "<li class='breadcrumb-item'><i>{link}</i></li>\n", // template for all links
                                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                    'options' => ['class' => 'breadcrumb float-sm-right']
                                ])
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->
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
