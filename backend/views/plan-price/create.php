<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlanPrice */

$this->title = Yii::t('app', 'Create Plan Price');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plan Prices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-price-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
