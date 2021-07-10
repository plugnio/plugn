<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Restaurant;
/* @var $this yii\web\View */

$this->title = 'Partner dashboard';
?>
<div class="agent-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Store Name',
                'attribute' => 'name',
                "value" => 'name',
            ],
            [
                'label' => 'Total Earnings',
                "value" => function($data) {
                        return Yii::$app->formatter->asCurrency($data->totalEarnings ? $data->totalEarnings : 0, 'KWD');
                },
            ]
        ],
    ]); ?>

</div>
