<?php

use common\models\Restaurant;
use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<header id="header" class="ex-2-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>Please verify your email </h1>
                <p>Check your email inbox on <?= $email ?>.</p>
                <?=
                Html::a(
                    Html::button('GO TO HOMEPAGE', ['site/index', 'class' => 'form-control-submit-button', 'style' => 'max-width: 50%;']),
                    ['site/index'], ['style' => 'text-decoration: none;'])
                ?>
            </div>
        </div>
    </div>
</header>

