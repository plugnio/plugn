<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CurrencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Currencies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="currency-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Currency', ['create'], ['class' => 'btn btn-success btn-create']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'code',
            'currency_symbol',
            'rate',
            'decimal_place',
            'sort_order',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status == 1? 'Active': 'Inactive';
                },
                "filter" => [
                    \common\models\Currency::STATUS_ACTIVE => 'Active',
                    \common\models\Currency::STATUS_INACTIVE => 'Inactive'
                ],
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
