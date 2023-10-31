<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ApiLog */

$this->title = Yii::t('app', 'Update Api Log: {name}', [
    'name' => $model->log_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Api Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->log_uuid, 'url' => ['view', 'id' => $model->log_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="api-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
