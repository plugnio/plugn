<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Store Branches';
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
          <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-outline-primary']) ?>
        </div>
    </div>
</div>




    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
              ['class' => 'yii\grid\SerialColumn'],
              'branch_name_en',
              'branch_name_ar',
              [
                'header' => 'Action',

                  'class' => 'yii\grid\ActionColumn',
                  'template' => ' {update} {delete}',
                  'buttons' => [
                      'update' => function ($url, $model) {
                          return Html::a(
                                          '<span style="margin-right: 20px;" class="nav-icon fa fa-edit"></span>', ['view', 'id' => $model->restaurant_branch_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                      'title' => 'Update',
                                      'data-pjax' => '0',
                                          ]
                          );
                      },
                      'delete' => function ($url, $model) {
                          return Html::a(
                                          '<span style="margin-right: 20px;color: red;" class="nav-icon fa fa-trash"></span>', ['delete', 'id' => $model->restaurant_branch_id, 'restaurantUuid' => $model->restaurant_uuid], [
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
