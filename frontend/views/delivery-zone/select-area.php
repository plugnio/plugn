<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DeliveryZone */

$this->title = 'Delivery Zone';
// $this->params['breadcrumbs'][] = $this->title;
$this->params['restaurant_uuid'] = $storeUuid;

?>
<div>

    <div class="card">

      <div class="card-header">
        <h4>Would you like to select specific areas or you want to delivery all over <?= $selectedCountry ?> </h4>
      </div>

      <div class="card-content">
      <div class="card-body">

        <?= Html::a('Deliver all over '. $selectedCountry ,
          ['deliver-all-areas', 'storeUuid' => $storeUuid, 'deliveryZoneId' => $deliveryZoneId],
          ['class' => 'btn btn-outline-primary', 'style' => 'margin-bottom : 15px']);
        ?>

        <?= Html::a('Select specific area ',
          ['update-areas', 'storeUuid' => $storeUuid, 'id' => $deliveryZoneId],
          ['class' => 'btn btn-outline-primary', 'style' => 'margin-bottom : 15px']);
        ?>


      </div>
      </div>

    </div>

</div>
