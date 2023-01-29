<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CustomerEmailTemplate */

$this->title = Yii::t('app', 'Update Customer Email Template: {name}', [
    'name' => $model->template_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->template_uuid, 'url' => ['view', 'id' => $model->template_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="customer-email-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
