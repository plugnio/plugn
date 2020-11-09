<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TapQueue */

$this->title = 'Update Tap Queue: ' . $model->tap_queue_id;
$this->params['breadcrumbs'][] = ['label' => 'Tap Queues', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tap_queue_id, 'url' => ['view', 'id' => $model->tap_queue_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tap-queue-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
