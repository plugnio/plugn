<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\touchspin\TouchSpin;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Inventory';
$this->params['breadcrumbs'][] = $this->title;

$js = "
$(function () {
  $('.summary').insertAfter('.top');
});


    $(document).on('wheel', 'input[type=number]', function (e) {
        $(this).blur();
    });

";
$this->registerJs($js);
?>



<section id="data-list-view" class="data-list-view-header">


    <?php echo $this->render('_inventory-search', ['model' => $searchModel, 'restaurant_uuid' => $restaurant_model->restaurant_uuid]); ?>


        <!-- Data list view starts -->
        <div class="action-btns d-none">
            <div class="btn-dropdown mr-1 mb-1">
                <div class="btn-group dropdown actions-dropodown">
                    <?= Html::a('<i class="fa fa-file-excel-o"></i> Export to Excel', ['export-to-excel', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>



    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
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
                    'attribute' => 'sku',
                    'format' => 'raw',
                    'value' => function ($item) {
                        return $item->sku ? $item->sku : '(not set)';
                    },
                ],
                [
                    'label' => '	Available',
                    'format' => 'raw',
                    'value' => function ($item) {
                        return $item->stock_qty;
                    },
                ],
                [
                    'header' => 'Edit quantity available',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {update}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                          return $this->render('_update-inventory', ['model' => $model]);
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
