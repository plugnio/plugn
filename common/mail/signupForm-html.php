<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\ContactForm */
?>
<div class="verify-form">
    <h1>Hello Big Boss,</h1>


    <h2>New Agent has signed up</h2>

    <p class="lead">
        Company Name: <?= $model->company_name ?> <br/>
        Agent Name: <?= $model->name ?> <br/>
        Agent Phone: <?= $model->phone ?> <br/>
        Agent Email: <?= $model->email ?> <br/>
    </p>


</div>
