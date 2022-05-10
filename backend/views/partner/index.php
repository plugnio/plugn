<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PartnerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Partners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Partner', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            // 'partner_auth_key',
            // 'partner_password_hash',
            // 'partner_password_reset_token',
            'partner_email:email',
            [
              'attribute' => 'partner_status',
              'label' => 'Status',
              'value' => 'status',
            ],
            'referral_code',
            'commission',
            [
                'label' => 'IN PROGRESS',
                'value' => function ($data) {
                    return Yii::$app->formatter->asCurrency($data->totalEarnings ? $data->totalEarnings : 0, 'KWD',[
                            \NumberFormatter::MIN_FRACTION_DIGITS => 4,
                        \NumberFormatter::MAX_FRACTION_DIGITS => 4 ]);
                }
            ],
            [
                'label' => 'Pending Payouts',
                'value' => function ($data) {
                    return Yii::$app->formatter->asCurrency($data->pendingPayouts ? $data->pendingPayouts : 0, 'KWD',[
                            \NumberFormatter::MIN_FRACTION_DIGITS => 4, \NumberFormatter::MAX_FRACTION_DIGITS => 4
                    ]);
                }
            ],
            //'partner_created_at',
            //'partner_updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'option',
                'template' => '{create-payout}',
                'buttons' => [
                    'create-payout' => function ($url, $model) {

                        if($model->pendingPayouts > 0 && $model->bank_id && $model->benef_name && $model->partner_iban){

                                  return   Html::a('Create Payout', ['create-payout', 'partner_uuid' => $model->partner_uuid, 'referral_code' => $model->referral_code], [
                                    'class' => 'btn btn-success',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to create a payout?',
                                        'method' => 'post',
                                    ],
                                ]) ;

                        }



                    },
                ],
            ],


            ['class' => 'yii\grid\ActionColumn'],




        ],
    ]); ?>


</div>
