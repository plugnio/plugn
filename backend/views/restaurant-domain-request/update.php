<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDomainRequest */

$this->title = Yii::t('app', 'Update Restaurant Domain Request: {name}', [
    'name' => $model->request_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restaurant Domain Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->request_uuid, 'url' => ['view', 'id' => $model->request_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="restaurant-domain-request-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
