<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessLocation */

$this->params['restaurant_uuid'] = $storeUuid;
$this->title = 'Add Business Location';
$this->params['breadcrumbs'][] = ['label' => 'Business Locations', 'url' => ['index',  'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-location-create">

    <?= $this->render('_form', [
        'model' => $model,
        'storeUuid' => $storeUuid
    ]) ?>

</div>
