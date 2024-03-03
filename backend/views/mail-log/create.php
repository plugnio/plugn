<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MailLog */

$this->title = Yii::t('app', 'Create Mail Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mail Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
