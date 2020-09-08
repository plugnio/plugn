<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\WebLinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Web Links';
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
                <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>



    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                $url = Url::to(['web-link/update', 'id' => $model->web_link_id, 'restaurantUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
            'columns' => [
              ['class' => 'yii\grid\SerialColumn'],
              [
                  'label' => 'Type',
                  "format" => "raw",
                  "value" => function($model) {
                      return $model->getWebLinkType();
                  }
              ],
              'url:url',
            ],
            'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table data-list-view'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

  </section>
<!-- Data list view end -->
