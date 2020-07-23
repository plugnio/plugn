<?php

use common\models\Restaurant;
use yii\helpers\Html;
?>

<!-- Header -->
<header id="header" class="header">
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-5">
                    <div class="text-container">
                        <h1>Start selling online. Your own website.</h1>
                        <p class="p-large">Within 24 hours. Zero commission. Online payment gateway and delivery integration included.</p>
                        <?=
                        Html::a('SIGN UP', ['site/signup'], ['class' => 'btn-solid-reg page-scroll']);
                        ?>
                    </div> <!-- end of text-container -->
                </div> <!-- end of col -->
                <div class="col-lg-6 col-xl-7">
                    <div class="image-container">
                        <div class="img-wrapper">
                            <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/images/header-software-app.png' ?>" class="img-fluid"  alt="alternative">
                        </div> <!-- end of img-wrapper -->
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of header-content -->
</header> <!-- end of header -->
<!-- end of header -->


<!-- Customers -->
<!-- <div class="slider-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <!-- Image Slider -->
                <!-- <div class="slider-container">
                    <div class="swiper-container image-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img class="img-fluid" src="images/customer-logo-1.png" alt="alternative">
                            </div>
                            <div class="swiper-slide">
                                <img class="img-fluid" src="images/customer-logo-2.png" alt="alternative">
                            </div>
                            <div class="swiper-slide">
                                <img class="img-fluid" src="images/customer-logo-3.png" alt="alternative">
                            </div>
                            <div class="swiper-slide">
                                <img class="img-fluid" src="images/customer-logo-4.png" alt="alternative">
                            </div>
                            <div class="swiper-slide">
                                <img class="img-fluid" src="images/customer-logo-5.png" alt="alternative">
                            </div>
                            <div class="swiper-slide">
                                <img class="img-fluid" src="images/customer-logo-6.png" alt="alternative">
                            </div>
                        </div> <!-- end of swiper-wrapper -->
                    <!-- </div> <!-- end of swiper container -->
                <!-- </div> <!-- end of slider-container -->
                <!-- end of image slider -->

            <!-- </div> <!-- end of col -->
        <!-- </div> <!-- end of row -->
    <!-- </div> <!-- end of container -->
<!-- </div> <!-- end of slider-1 -->
<!-- end of customers -->


<!-- Description -->
<div class="cards-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="above-heading">THE PERFECT SOLUTION</div>
                <h2 class="h2-heading">All the features you need to start selling online</h2>
            </div> <!-- end of col -->
        </div> <!-- end of row -->
        <div class="row">
            <div class="col-lg-12">

                <!-- Card -->
                <div class="card">
                    <div class="card-image">
                        <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/images/description-1.png' ?>" class="img-fluid"  alt="alternative">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">KNET/creditcard payment gateway and delivery tracking</h4>
                    </div>
                </div>
                <!-- end of card -->

                <!-- Card -->
                <div class="card">
                    <div class="card-image">
                        <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/images/description-2.png' ?>" class="img-fluid"  alt="alternative">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Your own website. Your branding.</h4>
                    </div>
                </div>
                <!-- end of card -->

                <!-- Card -->
                <div class="card">
                    <div class="card-image">
                        <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/images/description-3.png' ?>" class="img-fluid"  alt="alternative">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Zero Commission. All your sales are yours</h4>
                    </div>
                </div>
                <!-- end of card -->

            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of cards-1 -->
<!-- end of description -->


<!-- Details -->
<div id="details" class="basic-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="text-container">
                    <h2>Now Is The Time To Start Selling Online</h2>

                    <?=
                    Html::a('SIGN UP', ['site/signup'], ['class' => 'btn-solid-reg page-scroll']);
                    ?>

                </div> <!-- end of text-container -->
            </div> <!-- end of col -->
            <div class="col-lg-6">
                <div class="image-container">
                    <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/images/details.png' ?>" class="img-fluid"  alt="alternative">
                </div> <!-- end of image-container -->
            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of basic-1 -->
<!-- end of details -->


<!-- Footer -->

<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="footer-col first">
                    <h4>About Plugn</h4>
                    <p class="p-small">We're passionate about designing and developing one of the best e-commerce apps in the market</p>
                </div>
            </div> <!-- end of col -->
            <div class="col-md-4">
                <div class="footer-col last">
                    <h4 id='contact'>Contact</h4>
                    <ul class="list-unstyled li-space-lg p-small">
                        <li class="media">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="media-body">Ahmed AlJaber St. Crystal Tower, 24th floor</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-envelope"></i>
                            <div class="media-body"><a class="black" href="mailto:contact@plugn.io">contact@plugn.io</a> <i class="fas fa-globe"></i><a class="black" href="https://plugn.io">https://plugn.io</a></div>
                        </li>
                    </ul>
                </div>
            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of footer -->
<!-- end of footer -->


<!-- Copyright -->
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p class="p-small">Copyright © 2020 Plugn</p>
            </div> <!-- end of col -->
        </div> <!-- enf of row -->
    </div> <!-- end of container -->
</div> <!-- end of copyright -->
<!-- end of copyright -->
