<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessLocation */

$this->title = 'Update Business Location: ' . $model->business_location_name;
$this->params['restaurant_uuid'] = $store_model->restaurant_uuid;

$this->params['breadcrumbs'][] = ['label' => 'Business Locations', 'url' => ['index',  'storeUuid' => $store_model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="business-location-update">

    <?= $this->render('_form', [
        'model' => $model,
        'store_model' => $store_model
    ]) ?>

</div>
