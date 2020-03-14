<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WorkingDay */

$this->title = 'Create Working Day';
$this->params['breadcrumbs'][] = ['label' => 'Working Days', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="working-day-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
