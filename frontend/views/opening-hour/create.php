<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OpeningHour */

$this->title = 'Create Opening Hour';
$this->params['breadcrumbs'][] = ['label' => 'Opening Hours', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="opening-hour-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
