<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\ContactForm */

?>
<div class="verify-form">
    <p>Hello Big Boss,</p>

    <h1>Company Name:</h1> <p> <?= $model->company_name ?></p>
    <h1>Agent Name:</h1>  <p><?= $model->name ?></p>
    <h1>Agent Phone:</h1> <p> <?= $model->phone ?></p>
    <h1>Agent Email:</h1> <p> <?= $model->email ?></p>
    
</div>
