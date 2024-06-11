<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TapRequirements */

$this->title = $model->tap_requirements_uuid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tap Requirements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tap-requirements-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->tap_requirements_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->tap_requirements_uuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tap_requirements_uuid',
            'country_id',
            'requirement_en:html',
            [
                'attribute' => 'requirement_ar',
                "format" => "html",
                'contentOptions' => [
                    'class' => 'rtl',
                ]
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
