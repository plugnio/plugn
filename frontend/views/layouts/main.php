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
                            <ul class="nav navbar-nav">
                                <div class="bookmark-input search-input">
                                    <div class="bookmark-input-icon"><i class="feather icon-search primary"></i></div>
                                    <input class="form-control input" type="text" placeholder="Explore Vuexy..." tabindex="0" data-search="template-list">
                                    <ul class="search-list search-list-bookmark"></ul>
                                </div>
                                <!-- select.bookmark-select-->
                                <!--   option Chat-->
                                <!--   option email-->
                                <!--   option todo-->
                                <!--   option Calendar-->
                                </li>
                            </ul>
                        </div>
                        <ul class="nav navbar-nav float-right">
                            <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon feather icon-shopping-cart"></i><span class="badge badge-pill badge-primary badge-up cart-item-count">6</span></a>
                                <ul class="dropdown-menu dropdown-menu-media dropdown-cart dropdown-menu-right">
                                    <li class="dropdown-menu-header">
                                        <div class="dropdown-header m-0 p-2">
                                            <h3 class="white"><span class="cart-item-count">6</span><span class="mx-50">Items</span></h3><span class="notification-title">In Your Cart</span>
                                        </div>
                                    </li>
                                    <li class="scrollable-container media-list"><a class="cart-item" href="app-ecommerce-details.html">
                                            <div class="media">
                                                <div class="media-left d-flex justify-content-center align-items-center"><img src="app-assets/images/pages/eCommerce/4.png" width="75" alt="Cart Item"></div>
                                                <div class="media-body"><span class="item-title text-truncate text-bold-500 d-block mb-50">Apple - Apple Watch Series 1 42mm Space Gray Aluminum Case Black Sport Band - Space Gray Aluminum</span><span class="item-desc font-small-2 text-truncate d-block"> Durable, lightweight aluminum cases in silver, space gray,gold, and rose gold. Sport Band in a variety of colors. All the features of the original Apple Watch, plus a new dual-core processor for faster performance. All models run watchOS 3. Requires an iPhone 5 or later to run this device.</span>
                                                    <div class="d-flex justify-content-between align-items-center mt-1"><span class="align-middle d-block">1 x $299</span><i class="remove-cart-item feather icon-x danger font-medium-1"></i></div>
                                                </div>
                                            </div>
                                        </a><a class="cart-item" href="app-ecommerce-details.html">
                                            <div class="media">
                                                <div class="media-left d-flex justify-content-center align-items-center"><img class="mt-1 pl-50" src="app-assets/images/pages/eCommerce/dell-inspirion.jpg" width="100" alt="Cart Item"></div>
                                                <div class="media-body"><span class="item-title text-truncate text-bold-500 d-block mb-50">Apple - Macbook® (Latest Model) - 12" Display - Intel Core M5 - 8GB Memory - 512GB Flash Storage - Space Gray</span><span class="item-desc font-small-2 text-truncate d-block"> MacBook delivers a full-size experience in the lightest and most compact Mac notebook ever. With a full-size keyboard, force-sensing trackpad, 12-inch Retina display,1 sixth-generation Intel Core M processor, multifunctional USB-C port, and now up to 10 hours of battery life,2 MacBook features big thinking in an impossibly compact form.</span>
                                                    <div class="d-flex justify-content-between align-items-center mt-1"><span class="align-middle d-block">1 x $1599.99</span><i class="remove-cart-item feather icon-x danger font-medium-1"></i></div>
                                                </div>
                                            </div>
                                        </a><a class="cart-item" href="app-ecommerce-details.html">
                                            <div class="media">
                                                <div class="media-left d-flex justify-content-center align-items-center"><img src="app-assets/images/pages/eCommerce/7.png" width="88" alt="Cart Item"></div>
                                                <div class="media-body"><span class="item-title text-truncate text-bold-500 d-block mb-50">Sony - PlayStation 4 Pro Console</span><span class="item-desc font-small-2 text-truncate d-block"> PS4 Pro Dynamic 4K Gaming & 4K Entertainment* PS4 Pro gets you closer to your game. Heighten your experiences. Enrich your adventures. Let the super-charged PS4 Pro lead the way.** GREATNESS AWAITS</span>
                                                    <div class="d-flex justify-content-between align-items-center mt-1"><span class="align-middle d-block">1 x $399.99</span><i class="remove-cart-item feather icon-x danger font-medium-1"></i></div>
                                                </div>
                                            </div>
                                        </a><a class="cart-item" href="app-ecommerce-details.html">
                                            <div class="media">
                                                <div class="media-left d-flex justify-content-center align-items-center"><img src="app-assets/images/pages/eCommerce/10.png" width="75" alt="Cart Item"></div>
                                                <div class="media-body"><span class="item-title text-truncate text-bold-500 d-block mb-50">Beats by Dr. Dre - Geek Squad Certified Refurbished Beats Studio Wireless On-Ear Headphones - Red</span><span class="item-desc font-small-2 text-truncate d-block"> Rock out to your favorite songs with these Beats by Dr. Dre Beats Studio Wireless GS-MH8K2AM/A headphones that feature a Beats Acoustic Engine and DSP software for enhanced clarity. ANC (Adaptive Noise Cancellation) allows you to focus on your tunes.</span>
                                                    <div class="d-flex justify-content-between align-items-center mt-1"><span class="align-middle d-block">1 x $379.99</span><i class="remove-cart-item feather icon-x danger font-medium-1"></i></div>
                                                </div>
                                            </div>
                                        </a><a class="cart-item" href="app-ecommerce-details.html">
                                            <div class="media">
                                                <div class="media-left d-flex justify-content-center align-items-center"><img class="mt-1 pl-50" src="app-assets/images/pages/eCommerce/sony-75class-tv.jpg" width="100" alt="Cart Item"></div>
                                                <div class="media-body"><span class="item-title text-truncate text-bold-500 d-block mb-50">Sony - 75" Class (74.5" diag) - LED - 2160p - Smart - 3D - 4K Ultra HD TV with High Dynamic Range - Black</span><span class="item-desc font-small-2 text-truncate d-block"> This Sony 4K HDR TV boasts 4K technology for vibrant hues. Its X940D series features a bold 75-inch screen and slim design. Wires remain hidden, and the unit is easily wall mounted. This television has a 4K Processor X1 and 4K X-Reality PRO for crisp video. This Sony 4K HDR TV is easy to control via voice commands.</span>
                                                    <div class="d-flex justify-content-between align-items-center mt-1"><span class="align-middle d-block">1 x $4499.99</span><i class="remove-cart-item feather icon-x danger font-medium-1"></i></div>
                                                </div>
                                            </div>
                                        </a><a class="cart-item" href="app-ecommerce-details.html">
                                            <div class="media">
                                                <div class="media-left d-flex justify-content-center align-items-center"><img class="mt-1 pl-50" src="app-assets/images/pages/eCommerce/canon-camera.jpg" width="70" alt="Cart Item"></div>
                                                <div class="media-body"><span class="item-title text-truncate text-bold-500 d-block mb-50">Nikon - D810 DSLR Camera with AF-S NIKKOR 24-120mm f/4G ED VR Zoom Lens - Black</span><span class="item-desc font-small-2 text-truncate d-block"> Shoot arresting photos and 1080p high-definition videos with this Nikon D810 DSLR camera, which features a 36.3-megapixel CMOS sensor and a powerful EXPEED 4 processor for clear, detailed images. The AF-S NIKKOR 24-120mm lens offers shooting versatility. Memory card sold separately.</span>
                                                    <div class="d-flex justify-content-between align-items-center mt-1"><span class="align-middle d-block">1 x $4099.99</span><i class="remove-cart-item feather icon-x danger font-medium-1"></i></div>
                                                </div>
                                            </div>
                                        </a></li>
                                    <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center text-primary" href="app-ecommerce-checkout.html"><i class="feather icon-shopping-cart align-middle"></i><span class="align-middle text-bold-600">Checkout</span></a></li>
                                    <li class="empty-cart d-none p-2">Your Cart Is Empty.</li>
                                </ul>
                            </li>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header m-0 p-2">
                                        <h3 class="white">5 New</h3><span class="notification-title">App Notifications</span>
                                    </div>
                                </li>
                                <li class="scrollable-container media-list"><a class="d-flex justify-content-between" href="javascript:void(0)">
                                        <div class="media d-flex align-items-start">
                                            <div class="media-left"><i class="feather icon-plus-square font-medium-5 primary"></i></div>
                                            <div class="media-body">
                                                <h6 class="primary media-heading">You have new order!</h6><small class="notification-text"> Are your going to meet me tonight?</small>
                                            </div><small>
                                                <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">9 hours ago</time></small>
                                        </div>
                                    </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                                        <div class="media d-flex align-items-start">
                                            <div class="media-left"><i class="feather icon-download-cloud font-medium-5 success"></i></div>
                                            <div class="media-body">
                                                <h6 class="success media-heading red darken-1">99% Server load</h6><small class="notification-text">You got new order of goods.</small>
                                            </div><small>
                                                <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">5 hour ago</time></small>
                                        </div>
                                    </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                                        <div class="media d-flex align-items-start">
                                            <div class="media-left"><i class="feather icon-alert-triangle font-medium-5 danger"></i></div>
                                            <div class="media-body">
                                                <h6 class="danger media-heading yellow darken-3">Warning notifixation</h6><small class="notification-text">Server have 99% CPU usage.</small>
                                            </div><small>
                                                <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">Today</time></small>
                                        </div>
                                    </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                                        <div class="media d-flex align-items-start">
                                            <div class="media-left"><i class="feather icon-check-circle font-medium-5 info"></i></div>
                                            <div class="media-body">
                                                <h6 class="info media-heading">Complete the task</h6><small class="notification-text">Cake sesame snaps cupcake</small>
                                            </div><small>
                                                <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">Last week</time></small>
                                        </div>
                                    </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                                        <div class="media d-flex align-items-start">
                                            <div class="media-left"><i class="feather icon-file font-medium-5 warning"></i></div>
                                            <div class="media-body">
                                                <h6 class="warning media-heading">Generate monthly report</h6><small class="notification-text">Chocolate cake oat cake tiramisu marzipan</small>
                                            </div><small>
                                                <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">Last month</time></small>
                                        </div>
                                    </a></li>
                                <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center" href="javascript:void(0)">View all notifications</a></li>
                            </ul>
                            </li>
                            <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                    <div class="user-nav d-sm-flex d-none"><span class="user-name text-bold-600">
                                            <?= Html::tag('span', Yii::$app->user->identity->agent_name, ['agent/index', 'restaurantUuid' => $this->params['restaurant_uuid']]) ?>
                                        </span></div><span>
                                        <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/img/avatar.jpg' ?>" class="round"  alt="avatar" height="40" width="40">

                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="page-user-profile.html"><i class="feather icon-user"></i> Edit Profile</a><a class="dropdown-item" href="app-email.html"><i class="feather icon-mail"></i> My Inbox</a><a class="dropdown-item" href="app-todo.html"><i class="feather icon-check-square"></i> Task</a><a class="dropdown-item" href="app-chat.html"><i class="feather icon-message-square"></i> Chats</a>
                                    <div class="dropdown-divider"></div><a class="dropdown-item" href="auth-login.html"><i class="feather icon-power"></i> Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <ul class="main-search-list-defaultlist d-none">
            <li class="d-flex align-items-center"><a class="pb-25" href="#">
                    <h6 class="text-primary mb-0">Files</h6>
                </a></li>
            <li class="auto-suggestion d-flex align-items-center cursor-pointer"><a class="d-flex align-items-center justify-content-between w-100" href="#">
                    <div class="d-flex">
                        <div class="mr-50"><img src="app-assets/images/icons/xls.png" alt="png" height="32"></div>
                        <div class="search-data">
                            <p class="search-data-title mb-0">Two new item submitted</p><small class="text-muted">Marketing Manager</small>
                        </div>
                    </div><small class="search-data-size mr-50 text-muted">&apos;17kb</small>
                </a></li>
            <li class="auto-suggestion d-flex align-items-center cursor-pointer"><a class="d-flex align-items-center justify-content-between w-100" href="#">
                    <div class="d-flex">
                        <div class="mr-50"><img src="app-assets/images/icons/jpg.png" alt="png" height="32"></div>
                        <div class="search-data">
                            <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd Developer</small>
                        </div>
                    </div><small class="search-data-size mr-50 text-muted">&apos;11kb</small>
                </a></li>
            <li class="auto-suggestion d-flex align-items-center cursor-pointer"><a class="d-flex align-items-center justify-content-between w-100" href="#">
                    <div class="d-flex">
                        <div class="mr-50"><img src="app-assets/images/icons/pdf.png" alt="png" height="32"></div>
                        <div class="search-data">
                            <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital Marketing Manager</small>
                        </div>
                    </div><small class="search-data-size mr-50 text-muted">&apos;150kb</small>
                </a></li>
            <li class="auto-suggestion d-flex align-items-center cursor-pointer"><a class="d-flex align-items-center justify-content-between w-100" href="#">
                    <div class="d-flex">
                        <div class="mr-50"><img src="app-assets/images/icons/doc.png" alt="png" height="32"></div>
                        <div class="search-data">
                            <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web Designer</small>
                        </div>
                    </div><small class="search-data-size mr-50 text-muted">&apos;256kb</small>
                </a></li>
            <li class="d-flex align-items-center"><a class="pb-25" href="#">
                    <h6 class="text-primary mb-0">Members</h6>
                </a></li>
            <li class="auto-suggestion d-flex align-items-center cursor-pointer"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="#">
                    <div class="d-flex align-items-center">
                        <div class="avatar mr-50"><img src="app-assets/images/portrait/small/avatar-s-8.jpg" alt="png" height="32"></div>
                        <div class="search-data">
                            <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI designer</small>
                        </div>
                    </div>
                </a></li>
            <li class="auto-suggestion d-flex align-items-center cursor-pointer"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="#">
                    <div class="d-flex align-items-center">
                        <div class="avatar mr-50"><img src="app-assets/images/portrait/small/avatar-s-1.jpg" alt="png" height="32"></div>
                        <div class="search-data">
                            <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd Developer</small>
                        </div>
                    </div>
                </a></li>
            <li class="auto-suggestion d-flex align-items-center cursor-pointer"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="#">
                    <div class="d-flex align-items-center">
                        <div class="avatar mr-50"><img src="app-assets/images/portrait/small/avatar-s-14.jpg" alt="png" height="32"></div>
                        <div class="search-data">
                            <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital Marketing Manager</small>
                        </div>
                    </div>
                </a></li>
            <li class="auto-suggestion d-flex align-items-center cursor-pointer"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="#">
                    <div class="d-flex align-items-center">
                        <div class="avatar mr-50"><img src="app-assets/images/portrait/small/avatar-s-6.jpg" alt="png" height="32"></div>
                        <div class="search-data">
                            <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web Designer</small>
                        </div>
                    </div>
                </a></li>
        </ul>
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
                        <a class="navbar-brand" href="../../../html/ltr/vertical-menu-template/index.html">
                            <div class="brand-logo"></div>
                            <h2 class="brand-text mb-0"><?=  $restaurant_model->name ?></h2>
                        </a>


                        <?php
                      // echo    Html::a('<img src="' . $restaurant_model->getRestaurantLogoUrl() . '"alt="' . $restaurant_model->name . ' Logo" class="" style=" height: 24px; width: 35px;">'
                      //             . '<div class="brand-logo"></div>'
                      //             . '<h2 class="brand-text mb-0">' .  $restaurant_model->name  . '</h2>'
                      //             , ['site/index', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'navbar-brand']);
                      ?>



                    </li>
                </ul>
            </div>
            <div class="shadow-bottom"></div>
            <div class="main-menu-content">
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">


                    <li class=" nav-item">

                        <?=
                        Html::a(
                                Html::tag('i', '', ['class' => 'feather icon-home']) .
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
                                            Html::tag('span', $managedRestaurant->name), ['site/vendor-dashboard', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'menu-title']
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
        <!-- END: Content-->

        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>

        <?php $this->endBody() ?>

    </body>
    <!-- END: Body-->

</html>
<?php $this->endPage() ?>
