<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['restaurant_uuid'] = $storeUuid;

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
    <div class="action-btns">
        <div class="btn-dropdown mr-1 mb-1">
            <div class="btn-group dropdown actions-dropodown">
                <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'storeUuid' => $storeUuid], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>



    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                $url = Url::to(['restaurant-branch/view', 'id' => $model->restaurant_branch_id, 'storeUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
            'columns' => [
              ['class' => 'yii\grid\SerialColumn'],
              'branch_name_en',
              'branch_name_ar',

            ],
            'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table dataTable data-list-view'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

  </section>
<!-- Data list view end -->
