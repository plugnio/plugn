<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantType */

$this->title = Yii::t('app', 'Update Restaurant Type: {name}', [
    'name' => $model->restaurant_type_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restaurant Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->restaurant_type_uuid, 'url' => ['view', 'id' => $model->restaurant_type_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="restaurant-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
