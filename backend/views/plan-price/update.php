<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlanPrice */

$this->title = Yii::t('app', 'Update Plan Price: {name}', [
    'name' => $model->plan_price_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plan Prices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->plan_price_id, 'url' => ['view', 'id' => $model->plan_price_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="plan-price-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
