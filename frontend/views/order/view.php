<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->area_name;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->order_id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->order_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'order_id',
//            'area_id',
            'area_name',
            'area_name_ar',
            'unit_type',
            'block',
            'street',
            'avenue',
            'house_number',
            'special_directions',
            'customer_name',
            'customer_phone_number',
            'customer_email:email',
            'payment_method_id',
            'payment_method_name',
            'order_status',
        ],
    ])
    ?>

    <h2>Items</h2>

    <?=
    GridView::widget([
        'dataProvider' => $orderItems,
        'columns' => [
            'item_name',
            'qty',
            'instructions',
            ['class' => 'yii\grid\ActionColumn', 'controller' => 'option'],
        ],
    ]);
    ?>
    

    <h2>Extra options</h2>

    <?=
    GridView::widget([
        'dataProvider' => $itemsExtraOpitons,
        'columns' => [
            'extra_option_name',
            'extra_option_name_ar',
            ['class' => 'yii\grid\ActionColumn', 'controller' => 'option'],
        ],
    ]);
    ?>

</div>
