<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Vendor */

$this->title = 'Update Vendor: ' . $model->vendor_name;
$this->params['breadcrumbs'][] = ['label' => 'Vendors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->vendor_name, 'url' => ['view', 'id' => $model->vendor_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
