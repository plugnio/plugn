<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ExtraOption */

$this->title = 'Create Extra Option';
$this->params['breadcrumbs'][] = ['label' => 'Extra Options', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extra-option-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
