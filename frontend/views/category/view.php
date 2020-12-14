<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->params['restaurant_uuid'] = $storeUuid;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index', 'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->category_id, 'storeUuid' => $storeUuid], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->category_id, 'storeUuid' => $storeUuid], [
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
                    'sort_number',
                    'title',
                    'title_ar',
                    'subtitle',
                    'subtitle_ar',
                ],
            'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>
        </div>
    </div>

</div>
