<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessType */

$this->title = Yii::t('app', 'Update Business Type: {name}', [
    'name' => $model->business_type_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->business_type_uuid, 'url' => ['view', 'id' => $model->business_type_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="business-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
