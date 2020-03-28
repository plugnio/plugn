<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = $model->agent_name;
$this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index', 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="agent-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->agent_id, 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-primary']) ?>
    </p>
    
    <div class="card">
        <div class="card-body">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'agent_name',
                    'agent_email:email',
                    'agent_created_at',
                    'agent_updated_at',
                ],
                'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>

        </div>
    </div>
</div>
