<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */
$this->params['restaurant_uuid'] = $storeUuid;

$this->title = 'Edit Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-update">

    <?= $this->render('_form', [
        'model' => $model,
        'agentAssignment' => $agentAssignment,
        'storeUuid' => $storeUuid,
    ]) ?>

</div>
