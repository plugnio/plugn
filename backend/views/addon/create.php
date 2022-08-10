<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Addon */

$this->title = Yii::t('app', 'Create Addon');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Addons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="addon-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
