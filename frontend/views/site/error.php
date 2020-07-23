<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <!-- error 404 -->
    <section class="row flexbox-container">
        <div class="col-xl-7 col-md-8 col-12 d-flex justify-content-center">
            <div class="card auth-card bg-transparent shadow-none rounded-0 mb-0 w-100">
                <div class="card-content">
                    <div class="card-body text-center">
                        <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/app-assets/images/pages/404.png' ?>" class="img-fluid align-self-center" alt="branding logo">
                        <h1 class="font-large-2 my-1"> <?= $this->title ?></h1>
                        <p class="p-2">
                            <?= nl2br(Html::encode($message)) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- error 404 end -->



</div>
