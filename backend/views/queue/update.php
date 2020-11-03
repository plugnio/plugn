<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Queue */

$this->title = 'Update Queue: ' . $model->queue_id;
$this->params['breadcrumbs'][] = ['label' => 'Queues', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->queue_id, 'url' => ['view', 'id' => $model->queue_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="queue-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
