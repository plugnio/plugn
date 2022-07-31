<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\RestaurantPaymentMethodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Restaurant Payment Methods';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-payment-method-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Restaurant Payment Method', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'restaurant_uuid',
                'label' => 'Restaurant',
                'value' => function($data) {
                    return  $data->restaurant->name;
                }
            ],
            [
                'attribute' => 'payment_method_id',
                'label' => 'Payment Method',
                'value' => function($data) {
                    return  $data->paymentMethod->payment_method_name;
                },
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\PaymentMethod::find()->all(),'payment_method_id','payment_method_name'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All']
            ],
            'status',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
