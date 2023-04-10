<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDomainRequest */

$this->title = Yii::t('app', 'Create Restaurant Domain Request');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restaurant Domain Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-domain-request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
