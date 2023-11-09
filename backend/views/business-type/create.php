<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessType */

$this->title = Yii::t('app', 'Create Business Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
