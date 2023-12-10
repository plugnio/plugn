<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BlockedIP */

$this->title = Yii::t('app', 'Update Blocked Ip: {name}', [
    'name' => $model->ip_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blocked Ips'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ip_uuid, 'url' => ['view', 'id' => $model->ip_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="blocked-ip-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
