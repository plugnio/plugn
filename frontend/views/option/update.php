<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Option */

$this->title = 'Update Option: ' . $model->option_name;
$this->params['breadcrumbs'][] = ['label' => $model->option_name, 'url' => ['view', 'id' => $model->option_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="option-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
