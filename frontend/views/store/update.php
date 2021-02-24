<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Store: ' . $model->name;
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="restaurant-update">

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
