<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessItemType */

$this->title = $model->business_item_type_uuid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Item Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="business-item-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->business_item_type_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->business_item_type_uuid], [
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
            'business_item_type_uuid',
            'business_item_type_en',
            'business_item_type_ar',
            'business_item_type_subtitle_en:ntext',
            'business_item_type_subtitle_ar:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
