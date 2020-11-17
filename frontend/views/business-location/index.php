<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\BusinessLocation;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BusinessLocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Business Locations';
$this->params['restaurant_uuid'] = $restaurantUuid;


$js = "
$(function () {
  $('.summary').insertAfter('.top');
});
";
$this->registerJs($js);

?>

<!-- Data list view starts -->
  <section id="data-list-view" class="data-list-view-header">


    <!-- Data list view starts -->
    <div class="action-btns d-none">
        <div class="btn-dropdown mr-1 mb-1">
            <div class="btn-group dropdown actions-dropodown">
              <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>




       <?php if($dataProvider->getCount() == 0 ){  ?>
         <div style="padding-left:14px">
         <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-outline-primary','style'=>'    padding: 0.85rem 1.7rem;']) ?>
       </div>
       <?php } ?>

      <?=
      GridView::widget([
          'dataProvider' => $dataProvider,
          'rowOptions' => function($model) {
              $url = Url::to(['business-location/update', 'id' => $model->business_location_id, 'restaurantUuid' => $model->restaurant_uuid]);

              return [
                  'onclick' => "window.location.href='{$url}'"
              ];
          },
          'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

              'business_location_name',
              'business_location_name_ar',
              [
                  'label' => 'Support Delivery',
                  'value' => function ($data) {
                      return $data->support_delivery ? 'Yes' : 'No';
                  },
                  'format' => 'raw'
              ],

              [
                  'label' => 'Support Pick up',
                  'value' => function ($data) {
                      return $data->support_pick_up ? 'Yes' : 'No';
                  },
                  'format' => 'raw'
              ]

          ],

          'layout' => '{summary}<div class="card-body"><div class="box-body table-responsive no-padding">{items}{pager}</div></div>',
          'tableOptions' => ['class' => 'table data-list-view'],
      ]);
      ?>


  </section>
  <!-- Data list view end -->
