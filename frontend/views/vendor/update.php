<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vendor */

$this->title = 'Update Vendor: ' . $model->vendor_name;
$this->params['breadcrumbs'][] = ['label' => 'Vendors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->vendor_name, 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendor-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
