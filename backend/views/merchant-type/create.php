<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MerchantType */

$this->title = Yii::t('app', 'Create Merchant Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Merchant Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="merchant-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
