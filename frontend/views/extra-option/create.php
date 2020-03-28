<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ExtraOption */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Create Extra Option';
$option_model = \common\models\Option::findOne($model->option_id);
$this->params['breadcrumbs'][] = ['label' => $option_model->option_name, 'url' => ['option/view', 'id' => $model->option_id, 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extra-option-create">


    <?= $this->render('_form', [
        'model' => $model,
        'restaurantUuid' => $restaurantUuid
    ]) ?>

</div>
