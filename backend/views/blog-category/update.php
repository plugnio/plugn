<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $category */

$this->title = Yii::t('app', 'Update Category: {name}', [
    'name' => $category["blogCategoryDescriptions"][0]['title']
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $category["blogCategoryDescriptions"][0]['title'], 'url' => ['view', 'id' => $category['ID']]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="addon-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'category' => $category,
    ]) ?>
</div>
