<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlugnUpdates */

$this->title = Yii::t('app', 'Update Plugn Updates: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plugn Updates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->update_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="plugn-updates-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
