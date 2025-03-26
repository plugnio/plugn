<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscription $model */

$this->title = Yii::t('app', 'Create Store Domain Subscription');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Store Domain Subscriptions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-domain-subscription-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
