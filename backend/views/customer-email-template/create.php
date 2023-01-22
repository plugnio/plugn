<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CustomerEmailTemplate */

$this->title = Yii::t('app', 'Create Customer Email Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-email-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
