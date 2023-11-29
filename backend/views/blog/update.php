<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $post */

$this->title = Yii::t('app', 'Update Post: {name}', [
    'name' => $post["blogPostDescriptions"][0]['title']
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $post["blogPostDescriptions"][0]['title'], 'url' => ['view', 'id' => $post['ID']]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="addon-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'post' => $post,
    ]) ?>
</div>
