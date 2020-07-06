<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OpeningHour */

$this->title = 'Update Opening Hour: ' . $model->opening_hour_id;
$this->params['breadcrumbs'][] = ['label' => 'Opening Hours', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->opening_hour_id, 'url' => ['view', 'id' => $model->opening_hour_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="opening-hour-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
