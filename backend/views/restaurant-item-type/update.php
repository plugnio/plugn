<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantItemType */

$this->title = Yii::t('app', 'Update Restaurant Item Type: {name}', [
    'name' => $model->rit_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restaurant Item Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rit_uuid, 'url' => ['view', 'id' => $model->rit_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="restaurant-item-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
