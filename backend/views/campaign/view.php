<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Campaign */
/* @var $searchModel backend\models\RestaurantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->utm_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="campaign-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->utm_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->utm_uuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <h3>Campaign URL</h3>
    <a target="_blank" href="<?= Yii::$app->params['dashboardAppUrl'] ?>?utm_source=<?= $model->utm_source . '&utm_medium=' .$model->utm_medium . '&utm_campaign='.
        $model->utm_campaign . '&utm_id=' . $model->utm_uuid . '&utm_term=' . $model->utm_term .'&utm_content='.$model->utm_content ?>">
        <?= Yii::$app->params['dashboardAppUrl'] ?>?utm_source=<?= $model->utm_source . '&utm_medium=' .$model->utm_medium . '&utm_campaign='.
        $model->utm_campaign . '&utm_id=' . $model->utm_uuid . '&utm_term=' . $model->utm_term .'&utm_content='.$model->utm_content ?>
    </a>

    <h3>Campaign detail</h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'utm_uuid',
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_content',
            'utm_term',
            'investment',
            'no_of_stores',
            'no_of_orders',
            'total_commission',
            'total_gateway_fee',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <h3>Stores</h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        /*'rowOptions' => function($model){
            if ($model->queue) {
                if ($model->queue->queue_status == \common\models\Queue::QUEUE_STATUS_PENDING) {
                    return ['class' => 'danger'];
                } else if ($model->queue->queue_status == \common\models\Queue::QUEUE_STATUS_HOLD) {
                    return ['style' => 'background:orange', 'title' => 'Hold'];
                }
            }
        },*/
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            'restaurant_uuid',
            'name',
            'restaurant_domain',
             /*todo:
              * Total orders of that store till now

Total revenue made by that store till now

Total Commission made by us from that store till now

Total Payment gateway commission from that store till now
              */
            'restaurant_created_at:date',
        ],
    ]); ?>

</div>
