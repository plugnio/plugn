<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Option */
$this->params['restaurant_uuid'] = $storeUuid;
        
$this->title = 'Update Option: ' . $model->option_name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['item/index', 'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = ['label' => $model->item->item_name, 'url' => ['item/view', 'id' => $model->item->item_uuid ,'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = ['label' => $model->option_name, 'url' => ['view', 'id' => $model->option_id, 'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="option-update">

    <?= $this->render('_form', [
        'model' => $model,
        'storeUuid' => $storeUuid
    ]) ?>

</div>
