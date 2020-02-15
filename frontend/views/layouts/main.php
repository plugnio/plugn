<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
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
<body>
<?php $this->beginBody() ?>
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse ">
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="navbar-inner">
    <div class="header-seperation">
      <ul class="nav pull-left notifcation-center visible-xs visible-sm">
        <li class="dropdown">
          <a href="#main-menu" data-webarch="toggle-left-side">
            <i class="material-icons">menu</i>
          </a>
        </li>
      </ul>
      <!-- BEGIN LOGO -->
      <a href="index.html">
        <img src="assets/img/logo.png" class="logo" alt="" data-src="assets/img/logo.png" data-src-retina="assets/img/logo2x.png" width="106" height="21" />
      </a>
      <!-- END LOGO -->
    </div>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <div class="header-quick-nav">
      <!-- BEGIN TOP NAVIGATION MENU -->
      <div class="pull-left">
        <ul class="nav quick-section">
          <li class="quicklinks">
            <a href="#" class="" id="layout-condensed-toggle">
              <i class="material-icons">menu</i>
            </a>
          </li>
        </ul>
      </div>
      <div id="notification-list" style="display:none">
        <div style="width:300px">
          <div class="notification-messages info">
            <div class="user-profile">
              <img src="assets/img/profiles/d.jpg" alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35">
            </div>
            <div class="message-wrapper">
              <div class="heading">
                David Nester - Commented on your wall
              </div>
              <div class="description">
                Meeting postponed to tomorrow
              </div>
              <div class="date pull-left">
                A min ago
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="notification-messages danger">
            <div class="iconholder">
              <i class="icon-warning-sign"></i>
            </div>
            <div class="message-wrapper">
              <div class="heading">
                Server load limited
              </div>
              <div class="description">
                Database server has reached its daily capicity
              </div>
              <div class="date pull-left">
                2 mins ago
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="notification-messages success">
            <div class="user-profile">
              <img src="assets/img/profiles/h.jpg" alt="" data-src="assets/img/profiles/h.jpg" data-src-retina="assets/img/profiles/h2x.jpg" width="35" height="35">
            </div>
            <div class="message-wrapper">
              <div class="heading">
                You haveve got 150 messages
              </div>
              <div class="description">
                150 newly unread messages in your inbox
              </div>
              <div class="date pull-left">
                An hour ago
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <!-- END TOP NAVIGATION MENU -->
      <!-- BEGIN CHAT TOGGLER -->
      <div class="pull-right">
        <div class="chat-toggler sm">
          <div class="profile-pic">
            <img src="assets/img/profiles/avatar_small.jpg" alt="" data-src="assets/img/profiles/avatar_small.jpg" data-src-retina="assets/img/profiles/avatar_small2x.jpg" width="35" height="35" />
            <div class="availability-bubble online"></div>
          </div>
        </div>
        <ul class="nav quick-section ">
          <li class="quicklinks">
            <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">
              <i class="material-icons">tune</i>
            </a>
            <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
              <li>
                <a href="user-profile.html"> My Account</a>
              </li>
              <li class="divider"></li>
              <li>
                <a href="login.html"><i class="material-icons">power_settings_new</i>&nbsp;&nbsp;Log Out</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- END CHAT TOGGLER -->
    </div>
    <!-- END TOP NAVIGATION MENU -->
  </div>
  <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div class="page-container row-fluid">
  <!-- BEGIN SIDEBAR -->
  <div class="page-sidebar " id="main-menu">
    <!-- BEGIN MINI-PROFILE -->
    <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
      <div class="user-info-wrapper sm">
        <div class="profile-wrapper sm">
          <img src="assets/img/profiles/avatar.jpg" alt="" data-src="assets/img/profiles/avatar.jpg" data-src-retina="assets/img/profiles/avatar2x.jpg" width="69" height="69" />
          <div class="availability-bubble online"></div>
        </div>
        <div class="user-info sm">
          <div class="username">Fred <span class="semi-bold">Smith</span></div>
        </div>
      </div>
      <!-- END MINI-PROFILE -->
      <!-- BEGIN SIDEBAR MENU -->
      <ul>
        <li class="start  open active "> <a href="index.html"><i class="fa fa-gift"></i> <span class="title">Items</span> <span class="selected"></span> <span class="arrow  open "></span> </a>
          <ul class="sub-menu">
            <li>
                <?= Html::a('Categories', ['category/index']);  ?>
            </li>
            <li>
                <?= Html::a('All Items', ['item/index']);  ?>
            </li>
            <li>
                <?= Html::a('Item options', ['option/index']);  ?>
            </li>
          </ul>
        </li>
      </ul>
      <div class="clearfix"></div>
      <!-- END SIDEBAR MENU -->
    </div>
  </div>
  <a href="#" class="scrollup">Scroll</a>
  <div class="footer-widget">
    <div class="pull-right">
      <a href="lockscreen.html"><i class="material-icons">power_settings_new</i></a></div>
  </div>
  <!-- END SIDEBAR -->
  <!-- BEGIN PAGE CONTAINER-->
  <div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content sm-gutter">
      <?= Breadcrumbs::widget([
          'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
      ]) ?>
      <?= Alert::widget() ?>
      <?= $content ?>
    </div>
  </div>

</div>
<!-- END CONTAINER -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
