<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TapError */

$this->title = Yii::t('app', 'Update Tap Error: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tap Errors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->tap_error_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tap-error-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
