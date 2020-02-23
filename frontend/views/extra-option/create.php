<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ExtraOption */

$this->title = 'Create Extra Option';
$this->params['breadcrumbs'][] = ['label' => 'Extra Options', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extra-option-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
