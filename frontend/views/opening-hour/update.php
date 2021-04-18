<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OpeningHour */

$this->title = 'Update Opening Hour: ';
$this->params['breadcrumbs'][] = ['label' => 'Opening Hours', 'url' => ['index', 'storeUuid' => $storeUuid]];
// $this->params['breadcrumbs'][] = ['label' => $model->opening_hour_id, 'url' => ['view', 'id' => $model->opening_hour_id]];
$this->params['breadcrumbs'][] = 'Update';
$this->params['restaurant_uuid'] = $storeUuid;

?>
<div class="opening-hour-update">

    <?= $this->render('_form', [
      'modelDetails' => $modelDetails,
      'storeUuid' => $storeUuid
    ]) ?>

</div>
