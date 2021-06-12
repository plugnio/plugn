<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\AgentAssignment;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant_uuid;

$this->title = 'Assigned Agents';
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
          <?= Html::a('<i class="feather icon-plus"></i> Invite Additional Agent', ['create', 'storeUuid' => $restaurant_uuid], ['class' => 'btn btn-outline-primary']) ?>
        </div>
    </div>
</div>



    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                  if ($model->agent_id != Yii::$app->user->identity->agent_id) {
                $url = Url::to(['view', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'storeUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
              }
            },
            'columns' => [
              ['class' => 'yii\grid\SerialColumn'],
              'agent.agent_name',
              'assignment_agent_email:email',
              [
                  'attribute' => 'role',
                  'format' => 'html',
                  'value' => function ($data) {
                      if($data->role == AgentAssignment::AGENT_ROLE_OWNER)
                        $role = 'Owner';
                      else  if($data->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER)
                        $role = 'Branch Manager';
                      else
                        $role = 'Staff';

                      return $role;
                  },
              ],
              'assignment_created_at',
            ],
            'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table data-list-view'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

  </section>
<!-- Data list view end -->
