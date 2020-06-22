<?php

use yii\helpers\Html;
use yii\grid\GridView;

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

    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
              'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                  'customer_name',
                  'customer_phone_number',
                  'customer_email:email',
                  'customer_created_at:datetime',
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
