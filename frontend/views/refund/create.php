<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Refund */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Create Refund';
$this->params['breadcrumbs'][] = ['label' => 'Refunds', 'url' => ['refund/index','storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="refund-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
