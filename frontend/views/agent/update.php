<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */

$this->title = 'Update Agent: ' . $model->agent_name;
$this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->agent_name, 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agent-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
