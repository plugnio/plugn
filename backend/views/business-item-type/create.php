<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessItemType */

$this->title = Yii::t('app', 'Create Business Item Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Item Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-item-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
