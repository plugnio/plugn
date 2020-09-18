<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Payment Settings';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-view">

    <p>
        <?= Html::a('Update', ['update-payment-settings', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>

    </p>
    <div class="card">
        <div class="card-body">
            <div class="box-body table-responsive no-padding">

                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label' => 'Your store accepts payments with',
                            'value' => function ($data) {
                                $paymentMethods = '';

                                foreach ($data->getPaymentMethods()->all() as $key => $paymentMethod) {

                                    if ($key == 0)
                                        $paymentMethods .=  $paymentMethod['payment_method_name'] ;
                                    else
                                        $paymentMethods .= ', ' .  $paymentMethod['payment_method_name'] ;
                                }

                                return $paymentMethods;
                            },
                            'format' => 'raw'
                        ],
                        [
                            'label' => 'Current plan',
                            'format' => 'html',
                            'value' => function ($data) {
                                return $data->platform_fee > 0 ? 'Free plan' : 'Premium Plan';
                            },
                        ]
                    ],
                    'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
                ])
                ?>

            </div>
        </div>
    </div>

</div>
