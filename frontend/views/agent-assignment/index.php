<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\AgentAssignment;

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
          <?= Html::a('<i class="feather icon-plus"></i> Invite Additional Agent', ['create', 'restaurantUuid' => $restaurant_uuid], ['class' => 'btn btn-outline-primary']) ?>
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
              'agent.agent_name',
              'assignment_agent_email:email',
              [
                  'attribute' => 'role',
                  'format' => 'html',
                  'value' => function ($data) {
                      return $data->role == AgentAssignment::AGENT_ROLE_OWNER ? 'Owner' : 'Staff';
                  },
              ],
              'assignment_created_at',
              [
                  'class' => 'yii\grid\ActionColumn',
                  'template' => ' {view} {update} {delete}',
                  'visible' => AgentAssignment::isOwner($restaurant_uuid) ? true : false,
                  'buttons' => [
                      'view' => function ($url, $model) {
                          if ($model->agent_id != Yii::$app->user->identity->agent_id) {
                              return Html::a(
                                              '<span style="margin-right: 20px;" class="nav-icon fa fa-eye"></span>', ['view', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                          'title' => 'View',
                                          'data-pjax' => '0',
                                              ]
                              );
                          }
                      },
                      'update' => function ($url, $model) {
                          if ($model->agent_id != Yii::$app->user->identity->agent_id) {
                              return Html::a(
                                              '<span style="margin-right: 20px;" class="nav-icon fa fa-edit"></span>', ['update', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                          'title' => 'Update',
                                          'data-pjax' => '0',
                                              ]
                              );
                          }
                      },
                      'delete' => function ($url, $model) {
                          if ($model->agent_id != Yii::$app->user->identity->agent_id) {
                              return Html::a(
                                              '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', ['delete', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                          'title' => 'Delete',
                                          'data' => [
                                              'confirm' => 'Are you absolutely sure ? You will lose all the information about this agent with this action.',
                                              'method' => 'post',
                                          ],
                              ]);
                          }
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
