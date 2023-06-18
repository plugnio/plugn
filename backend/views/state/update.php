<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\State */

$this->title = Yii::t('app', 'Update State: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'States'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->state_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="state-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
