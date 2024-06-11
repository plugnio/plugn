<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TapRequirements */

$this->title = Yii::t('app', 'Create Tap Requirements');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tap Requirements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tap-requirements-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
