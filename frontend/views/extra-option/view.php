<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ExtraOption */

$this->title = $model->extra_option_name;
$this->params['breadcrumbs'][] = ['label' => $model->option->item->item_name, 'url' => ['item/view', 'id' => $model->option->item->item_uuid]];
$this->params['breadcrumbs'][] = ['label' => $model->option->option_name, 'url' => ['option/view', 'id' => $model->option->option_id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="extra-option-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->extra_option_id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->extra_option_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <div class="card">
        <div class="card-body">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'extra_option_name',
                    'extra_option_name_ar',
                    'extra_option_price:currency',
                ],
                'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>
        </div>
    </div>
    
</div>
