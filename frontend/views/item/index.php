<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

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
        <?= Html::a('Add Item', ['create', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-success']) ?>

    </p>
</div>


<div class="card">
    <?php

    // $initialPreviewArray = $dataProvider->query->getItemImages()->asArray()->one();
    // $initialPreviewArray = ArrayHelper::getColumn($initialPreviewArray, 'product_file_name');
    // $initialPreviewArray[$key] = "https://res.cloudinary.com/plugn/image/upload/restaurants/rest_b57f0b55-8d4c-11ea-8b7f-06d4853caaaa/items/". $file_name;


    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'sort_number',
            [
                'attribute' => 'Image',
                'format' => 'html',
                'value' => function ($data) {
                    $itemItmage = $data->getItemImages()->one();
                    if ($itemItmage) {
                        return Html::img("https://res.cloudinary.com/plugn/image/upload/c_scale,h_105,w_105/restaurants/". $data->restaurant->restaurant_uuid ."/items/" . $itemItmage->product_file_name);
                    }
                },
            ],
            'item_name',
//            'item_name_ar',
//            'item_description',
//            'item_description_ar',
            'stock_qty',
            'item_price:currency',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {view} {update} {delete}',
                'buttons' => [

                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>',
                            ['update', 'id' => $model->item_uuid, 'restaurantUuid' => $model->restaurant_uuid],
                            [
                                    'title' => 'Update',
                                    'data-pjax' => '0',
                                        ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                            '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>',
                            ['delete','id' => $model->item_uuid, 'restaurantUuid' => $model->restaurant_uuid],
                            [
                                    'title' => 'Delete',
                                    'data' => [
                                        'confirm' => 'Are you absolutely sure ? You will lose all the information about this item with this action.',
                                        'method' => 'post',
                                    ],
                        ]
                        );
                    },
                ],
            ],
        ],
        'layout' => '{summary}<div class="card-body"><div class="box-body table-responsive no-padding">{items}<div class="card-footer clearfix">{pager}</div></div>',
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
        'summaryOptions' => ['class' => "card-header"],
    ]);
    ?>


</div>
