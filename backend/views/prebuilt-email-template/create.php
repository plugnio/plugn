<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PrebuiltEmailTemplate */

$this->title = Yii::t('app', 'Create Prebuilt Email Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Prebuilt Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prebuilt-email-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
