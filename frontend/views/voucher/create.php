<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Voucher */

$this->params['restaurant_uuid'] = $storeUuid;

$this->title = 'Create Voucher';
$this->params['breadcrumbs'][] = ['label' => 'Vouchers', 'url' => ['index', 'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voucher-create">

    <?= $this->render('_form', [
        'model' => $model,
        'storeUuid' => $storeUuid
    ]) ?>

</div>
