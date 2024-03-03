<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MailLog */

$this->title = "Mail log stats";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mail Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mail-log-stats">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=  \backend\components\ChartWidget::widget([
        'id' => "mail_log" ,
        'color' => "red",
        'chartdata' => $chart_data,
        'type' => "line",
        'title'=> "",
        'currency_code'=> ""
    ]); ?>

</div>
