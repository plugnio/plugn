<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TapQueue */

$this->title = 'Create Tap Queue';
$this->params['breadcrumbs'][] = ['label' => 'Tap Queues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tap-queue-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
