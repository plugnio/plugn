<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PrebuiltEmailTemplate */

$this->title = Yii::t('app', 'Update Prebuilt Email Template: {name}', [
    'name' => $model->template_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Prebuilt Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->template_uuid, 'url' => ['view', 'id' => $model->template_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="prebuilt-email-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
