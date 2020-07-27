<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Voucher;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\VoucherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Vouchers';
$this->params['breadcrumbs'][] = $this->title;

$js = "
$(function () {
  $('.summary').insertAfter('.top');
});
";
$this->registerJs($js);
?>
<section id="data-list-view" class="data-list-view-header">

    <!-- Data list view starts -->
    <div class="action-btns d-none">
        <div class="btn-dropdown mr-1 mb-1">
            <div class="btn-group dropdown actions-dropodown">
                <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>



    <?php if ($dataProvider->getCount() == 0) { ?>
        <div style="padding-left:14px">
            <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-outline-primary', 'style' => '    padding: 0.85rem 1.7rem;']) ?>
        </div>
    <?php } ?>


    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                $url = Url::to(['voucher/update', 'id' => $model->voucher_id, 'restaurantUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'code',
                [
                    'label' => 'Redeemed',
                    "format" => "raw",
                    "value" => function($model) {
                        return $model->getCustomerVouchers()->count();
                    }
                ],
                'max_redemption',
                [
                    'label' => 'Amount',
                    "format" => "raw",
                    "value" => function($model) {
                        return $model->discount_type == Voucher::DISCOUNT_TYPE_PERCENTAGE ? $model->discount_amount . '%' : $model->discount_amount;
                    }
                ],
                'minimum_order_amount',
                [
                    'attribute' => 'voucher_status',
                    "format" => "raw",
                    "value" => function($model) {
                        if ($model->voucher_status == Voucher::VOUCHER_STATUS_ACTIVE) {
                            return '<div class="chip chip-success mr-1">
                                          <div class="chip-body">
                                              <span style="white-space: pre;" class="chip-text">' . $model->voucherStatus . '</span>
                                          </div>
                                      </div>';
                        } else if ($model->voucher_status == Voucher::VOUCHER_STATUS_EXPIRED) {
                            return '<div class="chip chip-danger mr-1">
                                          <div class="chip-body">
                                              <span class="chip-text" style="white-space: pre;">' . $model->voucherStatus . '</span>
                                          </div>
                                      </div>';
                        }
                    }
                ],
                [
                    'attribute' => 'valid_from',
                    "format" => "raw",
                    "value" => function($model) {
                        return $model->valid_from ? date('Y-m-d', strtotime($model->valid_from)) : null;
                    }
                ],
                [
                    'attribute' => 'valid_until',
                    "format" => "raw",
                    "value" => function($model) {
                        return $model->valid_until ? date('Y-m-d', strtotime($model->valid_until)) : null;
                    }
                ],
                [
                    'header' => 'Actions',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a(
                                           $model->voucher_status == Voucher::VOUCHER_STATUS_ACTIVE ? 'Deactivate' : 'Activate' , ['change-voucher-status', 'id' => $model->voucher_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                        'title' => $model->voucher_status == Voucher::VOUCHER_STATUS_ACTIVE ? 'Deactivate' : 'Activate',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                    ],
                ],
            ],

            'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table data-list-view']
        ]);
        ?>


</section>
