<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AgentAssignment */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Invite Additional Agent';
$this->params['breadcrumbs'][] = ['label' => 'Assigned Agents', 'url' => ['index', 'storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-assignment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
