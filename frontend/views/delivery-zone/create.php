<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DeliveryZone */

$this->title = 'Create Delivery Zone';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Zones', 'url' => ['index',  'storeUuid' => $storeUuid , 'id' => $model->delivery_zone_id, 'businessLocationId' => $model->business_location_id]];
$this->params['breadcrumbs'][] = $this->title;
$this->params['restaurant_uuid'] = $storeUuid;

?>
<div class="delivery-zone-create">


    <?= $this->render('_form', [
        'model' => $model,
        'storeUuid' => $storeUuid
    ]) ?>

</div>
