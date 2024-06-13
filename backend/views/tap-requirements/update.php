<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TapRequirements */

$this->title = Yii::t('app', 'Update Tap Requirements: {name}', [
    'name' => $model->tap_requirements_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tap Requirements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tap_requirements_uuid, 'url' => ['view', 'id' => $model->tap_requirements_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tap-requirements-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
