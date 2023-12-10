<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BlockedIP */

$this->title = Yii::t('app', 'Create Blocked Ip');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blocked Ips'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blocked-ip-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
