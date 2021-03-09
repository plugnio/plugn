<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DeliveryZone */

$this->title = 'Update Delivery Zone: ' . $model->delivery_zone_id;
$this->params['breadcrumbs'][] = ['label' => 'Delivery Zones', 'url' => ['index',  'storeUuid' => $storeUuid , 'id' => $model->delivery_zone_id, 'businessLocationId' => $model->business_location_id]];
$this->params['breadcrumbs'][] = 'Update';
$this->params['restaurant_uuid'] = $storeUuid;

?>
<div class="delivery-zone-update">

    <?= $this->render('_form', [
        'model' => $model,
        'storeUuid' => $storeUuid
    ]) ?>

</div>
