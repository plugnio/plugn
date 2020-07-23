<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Manage Categories';
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


   <?php if($dataProvider->getCount() == 0 ){  ?>
     <div style="padding-left:14px">
     <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-outline-primary','style'=>'    padding: 0.85rem 1.7rem;']) ?>
   </div>
   <?php } ?>

    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                $url = Url::to(['category/update', 'id' => $model->category_id, 'restaurantUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
            'columns' => [
              [
                  'label' => 'Sort #',
                  'format' => 'html',
                  'value' => function ($data) {
                      return $data->sort_number;
                  },
              ],
                'title',
                'title_ar',
                'subtitle',
                'subtitle_ar',
            ],
            'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table data-list-view'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

  </section>
<!-- Data list view end -->
