<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AgentAssignment */

$this->title = 'Create Agent Assignment';
$this->params['breadcrumbs'][] = ['label' => 'Agent Assignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-assignment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
