<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;

$css ="
    td{
        vertical-align: middle !important;
        text-align: center;
      }";

$this->registerCss($css);


?>

<div class="page-title"> <i class="icon-custom-left"></i>
    <p>
        <?= Html::a('Create Item', ['create'], ['class' => 'btn btn-success']) ?>

    </p>
</div>


<div class="card">
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'sort_number',
            [
                'attribute' => 'Image',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img("https://res.cloudinary.com/plugn/image/upload/c_scale,h_105,w_105/restaurants/Lavina/items/" . $data->item_image);
                },
            ],
            'item_name',
//            'item_name_ar',
//            'item_description',
//            'item_description_ar',
            'stock_qty',
            'price:currency',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a(
                                        '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', $url, [
                                    'title' => 'View',
                                    'data-pjax' => '0',
                                        ]
                        );
                    },
                    'update' => function ($url) {
                        return Html::a(
                                        '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', $url, [
                                    'title' => 'Update',
                                    'data-pjax' => '0',
                                        ]
                        );
                    },
                    'delete' => function ($url) {
                        return Html::a(
                                        '<span style="margin-right: 20px;" class="nav-icon fas fa-trash"></span>', $url, [
                                    'title' => 'Delete',
                                    'data' => [
                                        'confirm' => 'Are you absolutely sure ? You will lose all the information about this item with this action.',
                                        'method' => 'post',
                                    ],
                        ]);
                    },
                ],
            ],
        ],
        'layout' => '{summary}<div class="card-body">{items}{pager}</div>',
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
        'summaryOptions' => ['class' => "card-header"],
    ]);
    ?>


</div>
