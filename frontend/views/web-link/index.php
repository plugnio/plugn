<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\WebLink;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\WebLinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $storeUuid;

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
                $url = Url::to(['web-link/update', 'id' => $model->web_link_id, 'storeUuid' => $model->restaurant_uuid]);

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
              [
                'attribute' => 'url',
                  "format" => "raw",
                  "value" => function($model) {
                    switch ($model->web_link_type) {

                      case WebLink::WEB_LINK_TYPE_FACEBOOK:
                        return '<a href="https://www.facebook.com/' .$model->url . '" >@' . $model->url .'</a>';
                      break;

                      case WebLink::WEB_LINK_TYPE_INSTAGRAM:
                        return '<a href="https://www.instagram.com/' .$model->url . '" >@' . $model->url .'</a>';
                      break;

                      case WebLink::WEB_LINK_TYPE_TWITTER:
                        return '<a href="https://www.twitter.com/' .$model->url . '" >@' . $model->url .'</a>';
                      break;

                      case WebLink::WEB_LINK_TYPE_SNAPCHAT:
                        return '<a href="https://www.snapchat.com/add/' .$model->url . '" >@' . $model->url .'</a>';
                      break;

                      case WebLink::WEB_LINK_TYPE_WHATSAPP:
                        return '<a href="https://wa.me/965' .$model->url . '" >+965' . $model->url .'</a>';
                      break;

                      case WebLink::WEB_LINK_TYPE_EMAIL:
                        return '<a href="mailto:' .$model->url . '" >' . $model->url .'</a>';
                      break;

                      default:
                        return $model->url;
                        break;
                    }
                      return $model->getWebLinkType();
                  }
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
