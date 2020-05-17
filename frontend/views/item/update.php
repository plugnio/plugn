<?php

use yii\helpers\Html;
use common\models\ExtraOption;
use common\models\Option;

/* @var $this yii\web\View */
/* @var $model common\models\Item */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Update Item: ' . $modelItem->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index', 'restaurantUuid' => $modelItem->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-update">

    <?= $this->render('_form', [
                    'modelItem' => $modelItem,
                    'modelsOption' => (empty($modelsOption)) ? [new Option] : $modelsOption,
                    'modelsExtraOption' => (empty($modelsExtraOption)) ? [[new ExtraOption]] : $modelsExtraOption,
                    'restaurantUuid' => $restaurantUuid
    ]) ?>

</div>
