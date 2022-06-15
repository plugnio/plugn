<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BankSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Bank', ['create'], ['class' => 'btn btn-success btn-create']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'bank_name',
            'bank_iban_code',
            'bank_swift_code',
            'bank_address',
            //'bank_transfer_type',
            //'bank_created_at',
            //'bank_updated_at',
            //'deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
