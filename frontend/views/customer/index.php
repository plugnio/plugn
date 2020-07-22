<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Customers';
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
             <?= Html::a('<i class="fa fa-file-excel-o"></i> Export to excel', ['export-to-excel','restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-success']) ?>
          </div>
      </div>
  </div>

    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                $url = Url::to(['customer/view', 'id' => $model->customer_id, 'restaurantUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
              'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                  'customer_name',
                  'customer_phone_number',
                  'customer_email:email',
                  [
                      'label' => 'Number of orders',
                      "format" => "raw",
                      "value" => function($model) {
                          return  $model->getOrders()->count();
                      }
                  ],
                  'customer_created_at',
                  [
                      'header' => 'Actions',
                      'class' => 'yii\grid\ActionColumn',
                      'template' => ' {view} {update} {delete}',
                      'buttons' => [
                          'view' => function ($url, $model) {
                              return Html::a(
                                              '<span style="margin-right: 20px;" class="feather icon-eye"></span>', ['view', 'id' => $model->customer_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                          'title' => 'View',
                                          'data-pjax' => '0',
                                              ]
                              );
                          },
                          'delete' => function ($url, $model) {
                              return Html::a(
                                              '<span style="margin-right: 20px;color: red;" class="feather icon-trash"></span>', ['delete', 'id' => $model->customer_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                          'title' => 'Delete',
                                          'data' => [
                                              'confirm' => 'Are you absolutely sure ? You will lose all the information about this category with this action.',
                                              'method' => 'post',
                                          ],
                              ]);
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
