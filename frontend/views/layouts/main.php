
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
use common\models\AgentAssignment;

DashboardAsset::register($this);

$restaurant_model = Restaurant::findOne($this->params['restaurant_uuid']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!-- <html class="loading" lang="en" data-textdirection="ltr"> -->
<html lang="<?= Yii::$app->language ?>">
    <!-- BEGIN: Head-->

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

    <!-- END: Head-->


    <!-- BEGIN: Body-->

    <body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
        <?php $this->beginBody() ?>

        <!-- BEGIN: Header-->
        <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
            <div class="navbar-wrapper">
                <div class="navbar-container content">
                    <div class="navbar-collapse" id="navbar-mobile">
                        <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                            <ul class="nav navbar-nav">
                                <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon feather icon-menu"></i></a></li>
                            </ul>

                        </div>

                        <ul class="nav navbar-nav float-right">
                            <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                    <div class="user-nav d-sm-flex d-none"><span class="user-name text-bold-600">
                                            <?= Html::tag('span', Yii::$app->user->identity->agent_name, ['agent/index', 'restaurantUuid' => $this->params['restaurant_uuid']]) ?>
                                        </span></div><span>
                                        <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/img/avatar.jpg' ?>" class="round"  alt="avatar" height="40" width="40">

                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">


                                  <?= Html::a(
                                              '<i class="feather icon-user"></i> Edit Profile',
                                               ['agent/index', 'restaurantUuid' => $this->params['restaurant_uuid']],['class' => 'dropdown-item']) ?>



                                    <div class="dropdown-divider">
                                    </div>


                                    <?=
                                       Html::a('<i class="feather icon-power"></i> Logout', ['site/logout'], ['class' => 'dropdown-item',
                                           'data' => [
                                               'method' => 'post',
                                           ]
                                       ])
                                    ?>


                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </nav>
        <ul class="main-search-list-defaultlist-other-list d-none">
            <li class="auto-suggestion d-flex align-items-center justify-content-between cursor-pointer"><a class="d-flex align-items-center justify-content-between w-100 py-50">
                    <div class="d-flex justify-content-start"><span class="mr-75 feather icon-alert-circle"></span><span>No results found.</span></div>
                </a></li>
        </ul>
        <!-- END: Header-->


        <!-- BEGIN: Main Menu-->
        <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mr-auto">
                        <?=
                        Html::a('<img src="' . $restaurant_model->getRestaurantLogoUrl() . '"alt="' . $restaurant_model->name . ' Logo" class="round"  alt="avatar" height="40" width="40" ">'
                                . '<h2 class="brand-text mb-0"  style=" white-space: break-spaces;font-size: 20px;">' . $restaurant_model->name . '</h2>'
                                , ['site/index', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'navbar-brand']);
                        ?>
                    </li>
                    </a>
                  </li>
                </ul>
            </div>
            <div class="shadow-bottom"></div>
            <div class="main-menu-content">



                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">


                    <li class=" nav-item ">

                        <?=
                        Html::a(
                                Html::tag('i', '', ['class' => ' feather icon-home']) .
                                Html::tag('span', 'Dashboard'), ['site/vendor-dashboard', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'menu-title']
                        )
                        ?>
                    </li>


                    <li class=" nav-item"><a><i class="feather icon-shopping-cart"></i><span class="menu-title">Orders</span></a>
                        <ul class="menu-content">
                            <li>
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'feather icon-circle']) .
                                        Html::tag('span', 'Order'), ['order/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'menu-item']
                                )
                                ?>
                            </li>
                            <li>
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'feather icon-circle']) .
                                        Html::tag('span', 'Drafts'), ['order/draft', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'menu-item']
                                )
                                ?>
                            </li>
                            <li>
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'feather icon-circle']) .
                                        Html::tag('span', 'Abandoned checkouts'), ['order/abandoned-checkout', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'menu-item']
                                )
                                ?>
                            </li>
                        </ul>
                    </li>

                    <li class=" nav-item">

                        <?=
                        Html::a(
                                Html::tag('i', '', ['class' => 'feather icon-grid']) .
                                Html::tag('span', 'Categories'), ['category/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'menu-title']
                        )
                        ?>
                    </li>

                    <li class=" nav-item">

                        <?=
                        Html::a(
                                Html::tag('i', '', ['class' => 'fa fa-cubes']) .
                                Html::tag('span', 'Items'), ['item/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'menu-title']
                        )
                        ?>
                    </li>
                    <li class=" nav-item">

                        <?=
                        Html::a(
                                Html::tag('i', '', ['class' => 'feather icon-user']) .
                                Html::tag('span', 'Customers'), ['customer/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'menu-title']
                        )
                        ?>
                    </li>
                    <li class=" nav-item">

                        <?=
                        Html::a(
                                Html::tag('i', '', ['class' => 'fa fa-line-chart']) .
                                Html::tag('span', 'Analytics'), ['restaurant/analytic', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'menu-title']
                        )
                        ?>
                    </li>
                    <li class=" nav-item">


                        <?=
                        Html::a(
                                Html::tag('i', '', ['class' => 'feather icon-settings']) .
                                Html::tag('span', 'Store Info'), ['restaurant/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'menu-title']
                        )
                        ?>
                    </li>

                    <?php if (AgentAssignment::isOwner($restaurant_model->restaurant_uuid)) { ?>
                        <li class=" nav-item">

                            <?=
                            Html::a(
                                    Html::tag('i', '', ['class' => 'fa fa-paint-brush']) .
                                    Html::tag('span', 'Theme'), ['restaurant-theme/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'menu-title']
                            )
                            ?>
                        </li>
                    <?php } ?>

                    <li class=" nav-item">

                        <?=
                        Html::a(
                                Html::tag('i', '', ['class' => 'fa fa-truck']) .
                                Html::tag('span', 'Delivery Zone'), ['restaurant/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'menu-title']
                        )
                        ?>
                    </li>
                    <li class=" nav-item">

                        <?=
                        Html::a(
                                Html::tag('i', '', ['class' => 'feather icon-home']) .
                                Html::tag('span', "Store's Branches"), ['restaurant-branch/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'menu-title']
                        )
                        ?>
                    </li>
                    <?php if (AgentAssignment::isOwner($restaurant_model->restaurant_uuid)) { ?>
                        <li class=" nav-item">

                            <?=
                            Html::a(
                                    Html::tag('i', '', ['class' => 'feather icon-user-plus']) .
                                    Html::tag('span', 'Staff Management'), ['agent-assignment/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'menu-title']
                            )
                            ?>
                        </li>
                    <?php } ?>

                    <?php if (count(Yii::$app->accountManager->getManagedAccounts()) > 1) { ?>
                        <li class=" navigation-header"><span>Your stores</span>

                            <?php
                            foreach (Yii::$app->accountManager->getManagedAccounts() as $managedRestaurant) {
                                if ($managedRestaurant->restaurant_uuid != $this->params['restaurant_uuid']) {
                                    ?>
                                <li class=" nav-item">

                                    <?=
                                    Html::a(
                                            Html::img($managedRestaurant->getRestaurantLogoUrl(), ['class' => 'round', 'style' => 'opacity: .8; margin-right: .5rem; margin-top: -3px; max-height: 33px; width: auto;']) .
                                            Html::tag('span', $managedRestaurant->name), ['site/vendor-dashboard', 'id' => $managedRestaurant->restaurant_uuid], ['class' => 'menu-title']
                                    )
                                    ?>
                                </li>
                                <?php
                            }
                        }
                    }
                    ?>

                </ul>

            </div>
        </div>
        <!-- END: Main Menu-->

        <!-- BEGIN: Content-->
        <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                <div class="content-header row">
                    <div class="content-header-left col-md-9 col-12 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-left mb-0"><?= $this->title ?></h2>
                                <div class="breadcrumb-wrapper col-12">
                                    <?=
                                    Breadcrumbs::widget([
                                        'homeLink' => [
                                            'label' => Yii::t('yii', 'Dashboard'),
                                            'url' => ['site/index', 'id' => $this->params['restaurant_uuid']],
                                        ],
                                        'itemTemplate' => "<li class='breadcrumb-item'><i>{link}</i></li>\n", // template for all links
                                        'activeItemTemplate' => "<li class='breadcrumb-item'><i>{link}</i></li>\n", // template for all links
                                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                        'options' => ['class' => 'breadcrumb']
                                    ])
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="customizer d-none d-md-block">
        <a class="customizer-close" href="javascript:void(0)">
            <i class="feather icon-x"></i>
        </a>
        <a class="customizer-toggle" href="javascript:void(0)">
            <i class="feather icon-settings fa fa-spin fa-fw white"></i>
        </a>
        <div class="customizer-content p-2 ps">
            <h4 class="text-uppercase mb-0">Theme Customizer</h4>
            <small>Customize &amp; Preview in Real Time</small>
            <hr>

            <div id="customizer-theme-colors">
                <h5>Menu Colors</h5>
                <ul class="list-inline unstyled-list">
                <li class="color-box bg-primary selected" data-color="theme-primary"></li>
                <li class="color-box bg-success" data-color="theme-success"></li>
                <li class="color-box bg-danger" data-color="theme-danger"></li>
                <li class="color-box bg-info" data-color="theme-info"></li>
                <li class="color-box bg-warning" data-color="theme-warning"></li>
                <li class="color-box bg-dark" data-color="theme-dark"></li>
                </ul>
            </div>

            <hr>

            <h5 class="mt-1">Theme Layout</h5>
            <div class="theme-layouts">
                <div class="d-flex justify-content-start">
                <div class="mx-50">
                    <fieldset>
                    <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="layoutOptions" value="false" class="layout-name" data-layout="" checked="">
                        <span class="vs-radio">
                        <span class="vs-radio--border"></span>
                        <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Light</span>
                    </div>
                    </fieldset>
                </div>
                <div class="mx-50">
                    <fieldset>
                    <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="layoutOptions" value="false" class="layout-name" data-layout="dark-layout">
                        <span class="vs-radio">
                        <span class="vs-radio--border"></span>
                        <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Dark</span>
                    </div>
                    </fieldset>
                </div>
                <div class="mx-50 semi-dark">
                    <fieldset>
                    <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="layoutOptions" value="false" class="layout-name" data-layout="semi-dark-layout">
                        <span class="vs-radio">
                        <span class="vs-radio--border"></span>
                        <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Semi Dark</span>
                    </div>
                    </fieldset>
                </div>
                </div>
            </div>

            <hr>


            <div id="collapse-sidebar">
                <div class="collapse-sidebar d-flex justify-content-between">
                    <div class="collapse-option-title">
                    <h5 class="pt-25">Collapse Sidebar</h5>
                    </div>
                    <div class="collapse-option-switch">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="collapse-sidebar-switch">
                        <label class="custom-control-label" for="collapse-sidebar-switch"></label>
                    </div>
                    </div>
                </div>
                <hr>
            </div>


            <div id="navbar-type">
                <h5 class="navbar_type">Navbar Type</h5>
                <h5 class="menu_type d-none">Menu Type</h5>
                <div class="navbar-type d-flex justify-content-between">
                    <div class="mx-50">
                    <fieldset>
                        <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="navbarType" value="false" id="navbar-hidden">
                        <span class="vs-radio">
                            <span class="vs-radio--border"></span>
                            <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Hidden</span>
                        </div>
                    </fieldset>
                    </div>
                    <div class="mx-50">
                    <fieldset>
                        <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="navbarType" value="false" id="navbar-static">
                        <span class="vs-radio">
                            <span class="vs-radio--border"></span>
                            <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Static</span>
                        </div>
                    </fieldset>
                    </div>
                    <div class="mx-50">
                    <fieldset>
                        <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="navbarType" value="false" id="navbar-sticky">
                        <span class="vs-radio">
                            <span class="vs-radio--border"></span>
                            <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Sticky</span>
                        </div>
                    </fieldset>
                    </div>
                    <div class="mx-50">
                    <fieldset>
                        <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="navbarType" value="false" id="navbar-floating" checked="">
                        <span class="vs-radio">
                            <span class="vs-radio--border"></span>
                            <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Floating</span>
                        </div>
                    </fieldset>
                    </div>
                </div>
                <hr>
            </div>



            <h5>Footer Type</h5>
            <div class="footer-type d-flex justify-content-start">
                <div class="mx-50">
                <fieldset>
                    <div class="vs-radio-con vs-radio-primary">
                    <input type="radio" name="footerType" value="false" id="footer-hidden">
                    <span class="vs-radio">
                        <span class="vs-radio--border"></span>
                        <span class="vs-radio--circle"></span>
                    </span>
                    <span class="">Hidden</span>
                    </div>
                </fieldset>
                </div>
                <div class="mx-50">
                <fieldset>
                    <div class="vs-radio-con vs-radio-primary">
                    <input type="radio" name="footerType" value="false" id="footer-static" checked="">
                    <span class="vs-radio">
                        <span class="vs-radio--border"></span>
                        <span class="vs-radio--circle"></span>
                    </span>
                    <span class="">Static</span>
                    </div>
                </fieldset>
                </div>
                <div class="mx-50">
                <fieldset>
                    <div class="vs-radio-con vs-radio-primary">
                    <input type="radio" name="footerType" value="false" id="footer-sticky">
                    <span class="vs-radio">
                        <span class="vs-radio--border"></span>
                        <span class="vs-radio--circle"></span>
                    </span>
                    <span class="">Sticky</span>
                    </div>
                </fieldset>
                </div>
            </div>

            <hr>




        <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2020<a class="text-bold-800 grey darken-2" href="https://plugn.io/" target="_blank">Plugn,</a>All rights Reserved</span><span class="float-md-right d-none d-md-block">Hand-crafted & Made with<i class="feather icon-heart pink"></i></span>
            <button class="btn btn-primary btn-icon scroll-top" type="button"><i class="feather icon-arrow-up"></i></button>
        </p>
    </footer>
    <!-- END: Footer-->


    <?php $this->endBody() ?>

</body>
<!-- END: Body-->

</html>
<?php $this->endPage() ?>
