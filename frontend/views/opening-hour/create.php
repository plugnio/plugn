<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OpeningHour */

$this->title = 'Create Opening Hour';
$this->params['breadcrumbs'][] = ['label' => 'Opening Hours', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['restaurant_uuid'] = $restaurantUuid;



?>
<div class="opening-hour-create">

    <?= $this->render('_form', [
        'models' => $models,
    ]) ?>

</div>
