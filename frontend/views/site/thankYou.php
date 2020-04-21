<?php

use common\models\Restaurant;
use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<header id="header" class="ex-2-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>Thank you for contacting us! </h1>
                <p>We have received your message. One of our team members will get in touch with you shortly.</p>
                <?=
                Html::a(
                        Html::button('GO TO HOMEPAGE', ['site/index', 'class' => 'form-control-submit-button', 'style' => 'width:30%']),
                        ['site/index'], ['style' => 'text-decoration: none;'])
                ?>  
            </div>
        </div>
    </div>
</header>

