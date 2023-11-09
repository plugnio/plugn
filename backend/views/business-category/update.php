<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessCategory */

$this->title = Yii::t('app', 'Update Business Category: {name}', [
    'name' => $model->business_category_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->business_category_uuid, 'url' => ['view', 'id' => $model->business_category_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="business-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
