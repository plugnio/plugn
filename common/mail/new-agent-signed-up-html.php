<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */
?>
<div class="verify-form">
    <h1>Dear Ahmad,</h1>


    <h2>New Agent has signed up</h2>

    <p class="lead">
        Company Name: <?= $model->name ?> <br/>
        Agent Name: <?= $model->owner_first_name . ' ' . $model->owner_last_name ?> <br/>
        Agent Phone: <?= $model->owner_number ?> <br/>
        Agent Email: <?= $model->owner_email ?> <br/>
    </p>


</div>
