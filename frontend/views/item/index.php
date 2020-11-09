<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Item;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;

$js = "
$(function () {
  $('.summary').insertAfter('.top');
});
";
$this->registerJs($js);
?>



<section id="data-list-view" class="data-list-view-header">



    <!-- Data list view starts -->
    <div class="action-btns d-none">
        <div class="btn-dropdown mr-1 mb-1">
            <div class="btn-group dropdown actions-dropodown">
                <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>



    <?php echo $this->render('_search', ['model' => $searchModel, 'restaurant_uuid' => $restaurant_model->restaurant_uuid]); ?>

    <?php if ($dataProvider->getCount() == 0) { ?>
        <div style="padding-left:14px">
            <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-outline-primary', 'style' => '    padding: 0.85rem 1.7rem;']) ?>
        </div>
    <?php } ?>


    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                $url = Url::to(['item/update', 'id' => $model->item_uuid, 'restaurantUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
            'columns' => [
                [
                    'label' => 'Image',
                    'format' => 'raw',
                    'value' => function ($item) {

                            $itemItmage = $item->getItemImages()->one();

                            if ($itemItmage)
                              return  Html::img("https://res.cloudinary.com/plugn/image/upload/c_scale,h_60,w_60/restaurants/" . $item->restaurant->restaurant_uuid . "/items/" . $itemItmage->product_file_name, ['style' => 'border-radius: 3px;margin-right: 20px;']);
                            else
                                return Html::img("https://res.cloudinary.com/plugn/image/upload/c_scale,h_60,w_60/no-image.jpg", ['style' => 'border-radius: 3px;margin-right: 20px;']);


                    },
                ],
                [
                           'label' => 'Item name',
                           'format' => 'html',
                           'value' => function ($data) {
                               return $data->item_name;
                           },
                ],
                [
                    'label' => 'Category name',
                    'value' => function ($data) {
                        $categoryName = '';

                        foreach ($data->category as $key => $category) {
                            if ($key == 0) {
                                $categoryName .= $category['title'];
                            } else {
                                $categoryName .= ', ' . $category['title'];
                            }
                        }

                        if ($categoryName == '')
                            return '(not set)';

                        return $categoryName;
                    },
                    'format' => 'raw'
                ],
                'unit_sold',
                'sort_number',
                [
                    'attribute' => 'item_price',
                    "value" => function($data) {
                            return Yii::$app->formatter->asCurrency($data->item_price, $data->currency->code);
                    },
                ],
                [
                    'attribute' => 'item_status',
                    "format" => "raw",
                    "value" => function($model) {
                        if ($model->item_status == Item::ITEM_STATUS_PUBLISH) {
                            return '
                                              <span class="text-success"> Published </span>';
                        } else if ($model->item_status == Item::ITEM_STATUS_UNPUBLISH) {
                            return '<span class="text-danger"> Unpublished </span>';
                        }
                    }
                ],
                [
                    'header' => 'Action',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a(
                                           $model->item_status == Item::ITEM_STATUS_PUBLISH ? '<div class="chip chip-danger mr-1">
                                                         <div class="chip-body">
                                                             <span class="chip-text" style="white-space: pre;"> Unpublish</span>
                                                         </div>
                                                     </div>' : '<div class="chip chip-success mr-1">
                                                                   <div class="chip-body">
                                                                       <span class="chip-text" style="white-space: pre;"> Publish </span>
                                                                   </div>
                                                               </div>' , ['change-item-status', 'id' => $model->item_uuid, 'restaurantUuid' => $model->restaurant_uuid], [
                                        'title' => $model->item_status == Item::ITEM_STATUS_PUBLISH ? 'Unpublish' : 'Publish',
                                        'data-pjax' => '0',
                                              'class' =>  $model->item_status == Item::ITEM_STATUS_UNPUBLISH ?  'text-success' : 'text-danger',
                                              'style' => 'padding: 10px;'
                                            ]
                            );
                        },
                    ],
                ],
            ],
            'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table data-list-view'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

</section>
<!-- Data list view end -->
