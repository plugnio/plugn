<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AgentAssignment */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Agent Info: ' . $model->agent->agent_name;
$this->params['breadcrumbs'][] = ['label' => 'Assigned Agents', 'url' => ['index', 'storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = ['label' => $model->agent->agent_name, 'url' => ['view', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agent-assignment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
