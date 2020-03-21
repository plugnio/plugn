<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Option */

$this->title = 'Create Option';
$item_model = \common\models\Item::findOne($model->item_uuid);
$this->params['breadcrumbs'][] = ['label' => $item_model->item_name, 'url' => ['item/view', 'id' => $model->item_uuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="option-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
