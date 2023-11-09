<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessItemType */

$this->title = Yii::t('app', 'Update Business Item Type: {name}', [
    'name' => $model->business_item_type_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Item Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->business_item_type_uuid, 'url' => ['view', 'id' => $model->business_item_type_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="business-item-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
