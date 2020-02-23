<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ExtraOption */

$this->title = 'Update Extra Option: ' . $model->extra_option_id;
$this->params['breadcrumbs'][] = ['label' => 'Extra Options', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->extra_option_id, 'url' => ['view', 'id' => $model->extra_option_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="extra-option-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
