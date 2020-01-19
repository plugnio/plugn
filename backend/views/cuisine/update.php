<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Cuisine */

$this->title = 'Update Cuisine: ' . $model->cuisine_id;
$this->params['breadcrumbs'][] = ['label' => 'Cuisines', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cuisine_id, 'url' => ['view', 'id' => $model->cuisine_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cuisine-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
