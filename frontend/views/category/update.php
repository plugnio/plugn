<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->params['restaurant_uuid'] = $storeUuid;

$this->title = 'Update Category: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index', 'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="category-update">

  <p>
      <?=
      Html::a('Delete', ['delete', 'id' => $model->category_id, 'storeUuid' => $model->restaurant_uuid], [
          'class' => 'btn btn-danger',
          'data' => [
              'confirm' => 'Are you sure you want to delete this category?',
              'method' => 'post',
          ],
      ])
      ?>
  </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
