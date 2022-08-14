<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Addon */

$this->title = Yii::t('app', 'Update Addon: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Addons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->addon_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="addon-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
