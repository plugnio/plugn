<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VendorCampaignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Vendor Campaigns');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-campaign-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Vendor Campaign'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'campaign_uuid',
            'template_uuid',
            'progress',
            [
                'attribute' => 'status',
                'filter' => [
                    0 => 'Draft',
                    1 => "In Process",
                    2 => "Completed",
                    3 => "In Queue"
                ],
                'value' => function($model) {
                    return $model->statusName;
                }
            ],
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
