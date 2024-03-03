<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MailLog */

$this->title = Yii::t('app', 'Update Mail Log: {name}', [
    'name' => $model->mail_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mail Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mail_uuid, 'url' => ['view', 'id' => $model->mail_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="mail-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
