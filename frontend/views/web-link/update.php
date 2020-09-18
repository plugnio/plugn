<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WebLink */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Web Link: ' . $model->getWebLinkType();
$this->params['breadcrumbs'][] = ['label' => 'Web Links', 'url' => ['index', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update ' . $model->getWebLinkType();
?>
<div class="web-link-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
