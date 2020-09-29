<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Items';
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
                $url = Url::to(['item/update', 'id' => $model->item_uuid, 'restaurantUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
            'columns' => [
              [
                  'attribute' => 'Image',
                  'format' => 'html',
                  'value' => function ($data) {
                      $itemItmage = $data->getItemImages()->one();
                      if ($itemItmage) {
                          return Html::img("https://res.cloudinary.com/plugn/image/upload/c_scale,h_105,w_105/restaurants/" . $data->restaurant->restaurant_uuid . "/items/" . $itemItmage->product_file_name);
                      }
                  },
              ],

              [
                  'label' => 'Item Title',
                  'format' => 'html',
                  'value' => function ($data) {
                      return $data->item_name;
                  },
              ],
              [
                'label' => 'Category Name',
                  'value' => function ($data) {
                      $categoryName = '';

                      foreach ($data->category as $key => $category) {
                          if ($key == 0) {
                              $categoryName .= $category['title'];
                          } else {
                              $categoryName .= ', ' .  $category['title'];
                          }
                      }

                      if( $categoryName == '')
                        return '(not set)';

                      return $categoryName;
                  },
                  'format' => 'raw'
              ],
              'unit_sold',
              'sort_number',
              'item_price:currency',

            ],
            'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table data-list-view'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

  </section>
  <!-- Data list view end -->
