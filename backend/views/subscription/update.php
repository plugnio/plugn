<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Subscription */

$this->title = 'Update Subscription: ' . $model->restaurant->name;
$this->params['breadcrumbs'][] = ['label' => 'Subscriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->restaurant->name, 'url' => ['view', 'id' => $model->subscription_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subscription-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
