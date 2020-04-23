<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\LandingAsset;
use common\widgets\Alert;

LandingAsset::register($this);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- SEO Meta Tags -->
        <meta name="description" content="Plugn is the best ecommerce platform that has everything you need to sell online">
        <meta name="author" content="Pogi">

        <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
        <meta property="og:site_name" content="Plugn" /> <!-- website name -->
        <meta property="og:site" content="https://plugn.io" /> <!-- website link -->
        <meta property="og:title" content="Plugn is the best ecommerce platform to sell online"/> <!-- title shown in the actual shared post -->
        <meta property="og:description" content="ecommerce platform that has everything you need to sell online" /> <!-- description shown in the actual shared post -->
        <meta property="og:image" content="" /> <!-- image link, make sure it's jpg -->
        <meta property="og:url" content="https://plugn.io" /> <!-- where do you want your post to link to -->
        <meta property="og:type" content="article" />

        <!-- Website Title -->

        <?php $this->registerCsrfMetaTags() ?>
        <title>Plugn: the best ecommerce platform to sell online</title>

        <!-- Styles --!>
        <?php $this->head() ?>

        <!-- Favicon  -->
        <link rel="apple-touch-icon" sizes="180x180"  href="<?php echo Yii::$app->request->baseUrl; ?>/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon-16x16.png" sizes="16x16">
        <link rel="mask-icon" href="<?php echo Yii::$app->request->baseUrl; ?>/safari-pinned-tab.svg" color="#5bbad5">


        <meta name="theme-color" content="#ffffff">
    </head>

    <body class="hold-transition login-page">
        <!-- Preloader -->
        <div class="spinner-wrapper">
            <div class="spinner">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
        </div>
        <!-- end of preloader -->


        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
            <div class="container">

                <!-- Text Logo - Use this if you don't have a graphic logo -->
                <?=
                Html::a(
                        Html::img('images/logo-full.svg', ['alt' => '']), ['site/index'], ['class' => 'navbar-brand logo-image']
                );
                ?>

                <!-- Mobile Menu Toggle Button -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-awesome fas fa-bars"></span>
                    <span class="navbar-toggler-awesome fas fa-times"></span>
                </button>
                <!-- end of mobile menu toggle button -->

                <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="#header">HOME <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="#contact">CONTACT</a>
                        </li>

                    </ul>
                    <span class="nav-item">
<?=
Html::a('LOG IN', ['site/login'], ['class' => 'btn-outline-sm']);
?>
                    </span>
                </div>
            </div> <!-- end of container -->
        </nav> <!-- end of navbar -->
        <!-- end of navigation -->

<?php $this->beginBody() ?>
        <?= Alert::widget() ?>
        <?= $content ?> 
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
