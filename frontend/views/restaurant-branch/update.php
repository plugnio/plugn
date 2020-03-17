<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantBranch */

$this->title = 'Update Restaurant Branch: ' . $model->branch_name_en;
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->branch_name_en, 'url' => ['view', 'id' => $model->restaurant_branch_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="restaurant-branch-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
