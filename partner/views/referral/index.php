<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Restaurant;
/* @var $this yii\web\View */

$this->title = 'Referrals';
?>
<div class="agent-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Store Name',
                "value" => 'name',
            ],
            [
                'label' => 'Plugn plan',
                "value" => function($data) {
                      if($data->plan)
                        return $data->plan->name;
                },
            ],
            [
                'label' => 'Referral date',
                "value" => function($data) {
                        return Yii::$app->formatter->asDate($data->restaurant_created_at,  'php:M d, Y');
                },
            ],
            [
                'label' => 'Commission',
                "value" => function($data) {
                        return Yii::$app->formatter->format($data->partner->commission, ['percent']) ;
                },
            ],


            // [
            //     'label' => 'Total Earnings',
            //     "value" => function($data) {
            //             return Yii::$app->formatter->asCurrency($data->totalEarnings ? $data->totalEarnings : 0, 'KWD');
            //     },
            // ]
        ],
    ]); ?>

</div>
