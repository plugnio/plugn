<?php

use common\models\Restaurant;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Please verify your email';
$this->params['breadcrumbs'][] = $this->title;

?>

<section class="row flexbox-container">
    <div class="col-xl-8 col-10 d-flex justify-content-center">
        <div class="card bg-authentication rounded-0 mb-0">
            <div class="card-header pb-1">
                <div class="card-title">
                    <h4 class="mb-0">Please verify your email </h4>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body pt-0">
                <p>Check your email inbox on <?= $email ?>.</p>
                <?=
                Html::a(
                    Html::button('GO TO HOMEPAGE', ['site/index', 'class' => 'btn btn-primary', 'style' => 'max-width: 50%;']),
                    ['site/index'], ['style' => 'text-decoration: none;'])
                ?>
                </div>
            </div>
        </div>
    </div>
</section>

