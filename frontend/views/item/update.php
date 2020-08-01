<?php

use yii\helpers\Html;
use common\models\ExtraOption;
use common\models\Option;

/* @var $this yii\web\View */
/* @var $model common\models\Item */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Update Item: ' . $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-update">

    <p>
      <?=
      Html::a('Delete', ['delete', 'id' => $model->item_uuid, 'restaurantUuid' => $restaurantUuid], [
          'class' => 'btn btn-danger',
          'data' => [
              'confirm' => 'Are you sure you want to delete this item?',
              'method' => 'post',
          ],
      ])
      ?>
  </p>

    <?= $this->render('_form', [
      'model' => $model,
      'modelOptions' => $modelOptions,
      'restaurantUuid' => $restaurantUuid
    ]) ?>

</div>
