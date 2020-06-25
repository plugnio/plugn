<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Agent */

$this->title = 'Update Agent: ' . $model->agent_name;
$this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->agent_name, 'url' => ['view', 'id' => $model->agent_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agent-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
