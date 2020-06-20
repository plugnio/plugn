<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Manage Categories';
$this->params['breadcrumbs'][] = $this->title;
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


    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
              ['class' => 'yii\grid\SerialColumn'],


                'title',
                'title_ar',
                'subtitle',
                'subtitle_ar',
                [
                    'label' => 'Sort #',
                    'format' => 'html',
                    'value' => function ($data) {
                        return $data->sort_number;
                    },
                ],
                [
                    'header' => 'Actions',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="feather icon-edit"></span>', ['update', 'id' => $model->category_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                        'title' => $url,
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;color: red;" class="feather icon-trash"></span>', ['delete', 'id' => $model->category_id, 'restaurantUuid' => $model->restaurant_uuid], [
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
