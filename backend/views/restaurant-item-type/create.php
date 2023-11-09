<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantItemType */

$this->title = Yii::t('app', 'Create Restaurant Item Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restaurant Item Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-item-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
