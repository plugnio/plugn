<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlugnUpdates */

$this->title = Yii::t('app', 'Create Plugn Updates');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plugn Updates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plugn-updates-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
