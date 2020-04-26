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
   
        <script>
            
            var soundForNewOrders = new Audio("data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xA4Tvh9Rz/y8QADBwMWgQAZG/ILNAARQ4GLTcDeIIIhxGOBAuD7hOfBB3/94gcJ3w+o5/5eIAIAAAVwWgQAVQ2ORaIQwEMAJiDg95G4nQL7mQVWI6GwRcfsZAcsKkJvxgxEjzFUgfHoSQ9Qq7KNwqHwuB13MA4a1q/DmBrHgPcmjiGoh//EwC5nGPEmS4RcfkVKOhJf+WOgoxJclFz3kgn//dBA+ya1GhurNn8zb//9NNutNuhz31f////9vt///z+IdAEAAAK4LQIAKobHItEIYCGAExBwe8jcToF9zIKrEdDYIuP2MgOWFSE34wYiR5iqQPj0JIeoVdlG4VD4XA67mAcNa1fhzA1jwHuTRxDUQ//iYBczjHiTJcIuPyKlHQkv/LHQUYkuSi57yQT//uggfZNajQ3Vmz+Zt//+mm3Wm3Q576v////+32///5/EOgAAADVghQAAAAA//uQZAUAB1WI0PZugAAAAAoQwAAAEk3nRd2qAAAAACiDgAAAAAAABCqEEQRLCgwpBGMlJkIz8jKhGvj4k6jzRnqasNKIeoh5gI7BJaC1A1AoNBjJgbyApVS4IDlZgDU5WUAxEKDNmmALHzZp0Fkz1FMTmGFl1FMEyodIavcCAUHDWrKAIA4aa2oCgILEBupZgHvAhEBcZ6joQBxS76AgccrFlczBvKLC0QI2cBoCFvfTDAo7eoOQInqDPBtvrDEZBNYN5xwNwxQRfw8ZQ5wQVLvO8OYU+mHvFLlDh05Mdg7BT6YrRPpCBznMB2r//xKJjyyOh+cImr2/4doscwD6neZjuZR4AgAABYAAAABy1xcdQtxYBYYZdifkUDgzzXaXn98Z0oi9ILU5mBjFANmRwlVJ3/6jYDAmxaiDG3/6xjQQCCKkRb/6kg/wW+kSJ5//rLobkLSiKmqP/0ikJuDaSaSf/6JiLYLEYnW/+kXg1WRVJL/9EmQ1YZIsv/6Qzwy5qk7/+tEU0nkls3/zIUMPKNX/6yZLf+kFgAfgGyLFAUwY//uQZAUABcd5UiNPVXAAAApAAAAAE0VZQKw9ISAAACgAAAAAVQIygIElVrFkBS+Jhi+EAuu+lKAkYUEIsmEAEoMeDmCETMvfSHTGkF5RWH7kz/ESHWPAq/kcCRhqBtMdokPdM7vil7RG98A2sc7zO6ZvTdM7pmOUAZTnJW+NXxqmd41dqJ6mLTXxrPpnV8avaIf5SvL7pndPvPpndJR9Kuu8fePvuiuhorgWjp7Mf/PRjxcFCPDkW31srioCExivv9lcwKEaHsf/7ow2Fl1T/9RkXgEhYElAoCLFtMArxwivDJJ+bR1HTKJdlEoTELCIqgEwVGSQ+hIm0NbK8WXcTEI0UPoa2NbG4y2K00JEWbZavJXkYaqo9CRHS55FcZTjKEk3NKoCYUnSQ0rWxrZbFKbKIhOKPZe1cJKzZSaQrIyULHDZmV5K4xySsDRKWOruanGtjLJXFEmwaIbDLX0hIPBUQPVFVkQkDoUNfSoDgQGKPekoxeGzA4DUvnn4bxzcZrtJyipKfPNy5w+9lnXwgqsiyHNeSVpemw4bWb9psYeq//uQZBoABQt4yMVxYAIAAAkQoAAAHvYpL5m6AAgAACXDAAAAD59jblTirQe9upFsmZbpMudy7Lz1X1DYsxOOSWpfPqNX2WqktK0DMvuGwlbNj44TleLPQ+Gsfb+GOWOKJoIrWb3cIMeeON6lz2umTqMXV8Mj30yWPpjoSa9ujK8SyeJP5y5mOW1D6hvLepeveEAEDo0mgCRClOEgANv3B9a6fikgUSu/DmAMATrGx7nng5p5iimPNZsfQLYB2sDLIkzRKZOHGAaUyDcpFBSLG9MCQALgAIgQs2YunOszLSAyQYPVC2YdGGeHD2dTdJk1pAHGAWDjnkcLKFymS3RQZTInzySoBwMG0QueC3gMsCEYxUqlrcxK6k1LQQcsmyYeQPdC2YfuGPASCBkcVMQQqpVJshui1tkXQJQV0OXGAZMXSOEEBRirXbVRQW7ugq7IM7rPWSZyDlM3IuNEkxzCOJ0ny2ThNkyRai1b6ev//3dzNGzNb//4uAvHT5sURcZCFcuKLhOFs8mLAAEAt4UWAAIABAAAAAB4qbHo0tIjVkUU//uQZAwABfSFz3ZqQAAAAAngwAAAE1HjMp2qAAAAACZDgAAAD5UkTE1UgZEUExqYynN1qZvqIOREEFmBcJQkwdxiFtw0qEOkGYfRDifBui9MQg4QAHAqWtAWHoCxu1Yf4VfWLPIM2mHDFsbQEVGwyqQoQcwnfHeIkNt9YnkiaS1oizycqJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");

             async function CheckPendingOrders() {
                 const url = 'https://api.plugn.io/v1/order/check-for-pending-orders/<?= $restaurant_model->restaurant_uuid . "'" ?>;
                 fetch(url)
                     .then(res => res.json())
                     .then(data => {
                         if(data == true){
                             console.log('play');
                             soundForNewOrders.play();
                         } else if (data == false){
                               console.log('pause');
                            if (soundForNewOrders.duration > 0 && !soundForNewOrders.paused) {
                                soundForNewOrders.pause();
                            }
                     }
                 }).catch(err => {
                         console.error('Error: ', err);
                     });
                 }

             setInterval(function() {

                 CheckPendingOrders();

             }, 1000);
        
        </script>

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

                        <button>Play sound for new orders</button>
                    </div>

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
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-shopping-cart']) .
                                        Html::tag('p', 'Payments'), ['payment/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
                                )
                                ?>
                            </li>
                            <li class="nav-item has-treeview">
                                <?=
                                Html::a(
                                        Html::tag('i', '', ['class' => 'nav-icon fas fa-shopping-cart']) .
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
                                        Html::tag('p', 'Restaurant Info'), ['restaurant/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
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
                                        Html::tag('p', "Restaurant's Branches"), ['restaurant-branch/index', 'restaurantUuid' => $this->params['restaurant_uuid']], ['class' => 'nav-link']
                                )
                                ?>

                            </li>
                            </li>
                            <?php if (count(Yii::$app->ownedAccountManager->getOwnedRestaurants()) > 1) { ?>
                                <li class="nav-header">EXAMPLES</li>
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