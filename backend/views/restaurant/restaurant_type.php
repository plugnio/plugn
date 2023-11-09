<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */
/* @var $merchantTypes common\models\MerchantType */
/* @var $businessTypes common\models\BusinessType */
/* @var $businessCategories common\models\BusinessCategory */
/* @var $restaurantType common\models\RestaurantType */
/* @var $businessItemTypes common\models\BusinessItemType */
/* @var $restaurantItemTypes common\models\RestaurantItemType */

$this->title = 'Restaurant type';
$this->params['breadcrumbs'][] = ['label' => 'Restaurants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_restaurant_type_form', [
        'model' => $model,
        'restaurantType' => $restaurantType,
        'merchantTypes' => $merchantTypes,
        'businessTypes' => $businessTypes,
        'businessCategories' => $businessCategories,
        'businessItemTypes' => $businessItemTypes,
        'restaurantItemTypes' => $restaurantItemTypes
    ]) ?>

</div>
