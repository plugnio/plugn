<?php

use yii\helpers\Html;
use common\models\ExtraOption;
use common\models\Option;

/* @var $this yii\web\View */
/* @var $model common\models\Item */

$this->params['restaurant_uuid'] = $storeUuid;

$this->title = 'Update Item: ' . $modelItem->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index', 'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-update">

    <p>
      <?=
      Html::a('Delete', ['delete', 'id' => $modelItem->item_uuid, 'storeUuid' => $storeUuid], [
          'class' => 'btn btn-danger',
          'data' => [
              'confirm' => 'Are you sure you want to delete this item?',
              'method' => 'post',
          ],
      ])
      ?>
  </p>

    <?= $this->render('_form', [
                    'modelItem' => $modelItem,
                    'categoryQuery' => $categoryQuery,
                    'modelsOption' => (empty($modelsOption)) ? [new Option] : $modelsOption,
                    'modelsExtraOption' => (empty($modelsExtraOption)) ? [[new ExtraOption]] : $modelsExtraOption,
                    'storeUuid' => $storeUuid
    ]) ?>

</div>
