<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant->restaurant_uuid;

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

  <?php if ($dataProvider->getCount() > 0) { ?>

<!-- Data list view starts -->
<div class="action-btns d-none">
    <div class="btn-dropdown mr-1 mb-1">
        <div class="btn-group dropdown actions-dropodown">
          <?= Html::a('Create category', ['create', 'storeUuid' => $restaurant->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>



   <?php if($dataProvider->getCount() == 0 ){  ?>
     <div style="padding-left:14px">
     <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'storeUuid' => $restaurant->restaurant_uuid], ['class' => 'btn btn-outline-primary','style'=>'    padding: 0.85rem 1.7rem;']) ?>
   </div>
   <?php } ?>

    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function($model) {
                $url = Url::to(['category/update', 'id' => $model->category_id, 'storeUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
            'columns' => [
              [
                  'label' => 'Sort #',
                  'attribute' => 'sort_number'
              ],
                'title',
                'title_ar',
                'subtitle',
                'subtitle_ar',
            ],
            'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table  dataTable data-list-view'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

  <?php } else {?>


    <div class="card">
      <div style="padding: 70px 0; text-align: center;">

        <div>
          <img src="https://res.cloudinary.com/plugn/image/upload/v1607881378/emptystate--collections.svg" width="226" alt="" />
        </div>

        <h3>
          Group your products into categories
        </h3>

        <p>
          Use categories to organize your products for your online store.
        </p>
        <?= Html::a('Create category', ['create', 'storeUuid' => $restaurant->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
      </div>
    </div>


  <?php } ?>

</section>
<!-- Data list view end -->
