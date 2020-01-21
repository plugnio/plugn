<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ExtraOptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Extra Options';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extra-option-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Extra Option', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'extra_option_id',
            'option_id',
            'extra_option_name',
            'extra_option_name_ar',
            'price',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
