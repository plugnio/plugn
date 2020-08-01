<?php

use yii\helpers\Html;
use common\models\ExtraOption;
use common\models\Option;

/* @var $this yii\web\View */
/* @var $model common\models\Item */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Add Item';
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index', 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="item-create">

    <?=
    $this->render('_form', [
        'model' => $model,
        'modelOptions' => $modelOptions,
        'restaurantUuid' => $restaurantUuid
    ])
    ?>

</div>
