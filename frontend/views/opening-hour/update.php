<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OpeningHour */

$this->title = $day;
$this->params['breadcrumbs'][] = ['label' => 'Opening Hours', 'url' => ['index', 'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = 'Update';
$this->params['restaurant_uuid'] = $storeUuid;

?>
<div class="opening-hour-update">

    <?= $this->render('_form', [
      'openingHours' => $openingHours,
      'day' => $day,
      'storeUuid' => $storeUuid
    ]) ?>

</div>
