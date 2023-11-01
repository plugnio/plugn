<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ApiLog */

$this->title = Yii::t('app', 'Create Api Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Api Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
