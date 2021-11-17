<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */
$this->params['restaurant_uuid'] = $model->restaurant->restaurant_uuid;

$this->title = 'Order Item: ' . $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['order/view','id' => $model->order_uuid, 'storeUuid' =>$model->restaurant->restaurant_uuid ]];
$this->params['breadcrumbs'][] = ['label' => 'Update', 'url' => ['order/update','id' => $model->order_uuid, 'storeUuid' =>$model->restaurant->restaurant_uuid ]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);



$js = "
$(function () {
  $('.summary').insertAfter('.top');
});
";
$this->registerJs($js);
?>


<div class="order-item-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->order_item_id, 'storeUuid' =>$model->restaurant->restaurant_uuid ], ['class' => 'btn btn-primary  mr-1 mb-1']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->order_item_id, 'storeUuid' =>$model->restaurant->restaurant_uuid], [
            'class' => 'btn btn-danger  mr-1 mb-1',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="card">
        <div class="card-body">
            <?=
                DetailView::widget([
                  'model' => $model,
                  'attributes' => [
                    'order_item_id',
                  [
                      'attribute' => 'order_uuid',
                      "format" => "raw",
                      "value" => function ($model) {
                      return '#' . $model->order_uuid;
                      }
                  ],
                    'item_uuid',
                    'item_name',
                    'item_name_ar',
                    [
                        'attribute' => 'item_price',
                        "value" => function($data) {
                                return Yii::$app->formatter->asCurrency($data->item_price, $data->currency->code, [
                                    \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                ]);
                        },
                    ],
                    'qty',
                    'customer_instruction',
                  ],
                  'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
                ])
            ?>

        </div>
    </div>


    <?php if($model->item->getExtraOptions()->count() > 0){ ?>
         <section id="data-list-view" class="data-list-view-header">

           <h2>Extra Options</h2>


         <!-- Data list view starts -->
         <div class="action-btns">
             <div class="btn-dropdown mr-1 mb-1">
                 <div class="btn-group dropdown actions-dropodown">
                   <?= Html::a('<i class="feather icon-plus"></i> Add New', ['order-item-extra-option/create', 'id' => $model->order_item_id, 'storeUuid' =>$model->restaurant->restaurant_uuid], ['class' => 'btn btn-outline-primary']) ?>
                 </div>
             </div>
         </div>


             <!-- DataTable starts -->
             <div class="table-responsive">

                 <?=
                 GridView::widget([
                     'dataProvider' => $orderItemsExtraOpiton,
                     'columns' => [
                       ['class' => 'yii\grid\SerialColumn'],
                       'extra_option_name',
                       'extra_option_name_ar',
                       [
                           'attribute' => 'extra_option_price',
                           "value" => function($data) {
                                   return Yii::$app->formatter->asCurrency($data->extra_option_price, $data->currency->code, [
                                       \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                   ]);
                           },
                       ],
                       [
                           'class' => 'yii\grid\ActionColumn',
                           'controller' => 'option',
                           'template' => '{delete}',
                           'buttons' => [
                               'delete' => function ($url, $model) {
                                   return Html::a(
                                       '<span style="margin-right: 20px; color: red;" class="feather icon-trash"></span>',
                                       ['order-item-extra-option/delete', 'id' => $model->order_item_extra_option_id ,'storeUuid' =>$model->restaurant->restaurant_uuid],
                                       [
                                               'title' => 'Delete',
                                               'data' => [
                                                   'confirm' => 'Are you absolutely sure ? You will lose all the information about this option with this action.',
                                                   'method' => 'post',
                                               ],
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

       <?php  } ?>
