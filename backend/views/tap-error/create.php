<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TapError */

$this->title = Yii::t('app', 'Create Tap Error');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tap Errors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tap-error-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
