<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MerchantType */

$this->title = Yii::t('app', 'Update Merchant Type: {name}', [
    'name' => $model->merchant_type_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Merchant Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->merchant_type_uuid, 'url' => ['view', 'id' => $model->merchant_type_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="merchant-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
