<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\ContactForm */

?>
<div class="verify-form">
    <p>Hello Big Boss,</p>

    <h1 style="display: inline;">Company Name:</h1> <p> <?= $model->company_name ?></p>
    <h1 style="display: inline;">Agent Name:</h1>  <p><?= $model->name ?></p>
    <h1 style="display: inline;">Agent Phone:</h1> <p> <?= $model->phone ?></p>
    <h1 style="display: inline;">Agent Email:</h1> <p> <?= $model->email ?></p>

</div>
