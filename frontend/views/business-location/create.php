<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessLocation */

$this->params['restaurant_uuid'] = $store->restaurant_uuid;
$this->title = 'Add Business Location';
$this->params['breadcrumbs'][] = ['label' => 'Business Locations', 'url' => ['index',  'storeUuid' => $store->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-location-create">

    <?= $this->render('_form', [
        'model' => $model,
        'store' => $store
    ]) ?>

</div>
