<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */
$this->params['restaurant_uuid'] = $storeUuid;

$this->title = 'Edit Profile';
// $this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index', 'storeUuid' => $storeUuid]];
// $this->params['breadcrumbs'][] = ['label' => $model->agent_name, 'url' => ['index','storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-update">

    <?= $this->render('_form', [
        'model' => $model,
        'storeUuid' => $storeUuid,
    ]) ?>

</div>
