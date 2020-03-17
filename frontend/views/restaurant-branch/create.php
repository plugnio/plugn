<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantBranch */

$this->title = 'Create Restaurant Branch';
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-branch-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
