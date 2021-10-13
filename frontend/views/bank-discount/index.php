<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\BankDiscount;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BankDiscountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;


$this->title = 'Bank Discounts';
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
                <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'storeUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>



    <?php if ($dataProvider->getCount() == 0) { ?>
        <div style="padding-left:14px">
            <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'storeUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-outline-primary', 'style' => '    padding: 0.85rem 1.7rem;']) ?>
        </div>
    <?php } ?>


    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                $url = Url::to(['bank-discount/update', 'id' => $model->bank_discount_id, 'storeUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'bank.bank_name',
                [
                    'label' => 'Redeemed',
                    "format" => "raw",
                    "value" => function($model) {
                        return $model->getCustomerBankDiscounts()->count();
                    }
                ],
                [
                    'label' => 'Amount',
                    "format" => "raw",
                    "value" => function($model) {
                        return $model->discount_type == BankDiscount::DISCOUNT_TYPE_PERCENTAGE ? $model->discount_amount . '%' : $model->discount_amount;
                    }
                ],
                'minimum_order_amount',
                [
                    'attribute' => 'bank_dscount_status',
                    "format" => "raw",
                    "value" => function($model) {
                        if ($model->bank_discount_status == BankDiscount::BANK_DISCOUNT_STATUS_ACTIVE) {
                            return '<div class="chip chip-success mr-1">
                                            <div class="chip-body">
                                                <span style="white-space: pre;" class="chip-text">' . $model->bankDiscountStatus . '</span>
                                            </div>
                                        </div>';
                        } else if ($model->bank_discount_status == BankDiscount::BANK_DISCOUNT_STATUS_EXPIRED) {
                            return '<div class="chip chip-danger mr-1">
                                            <div class="chip-body">
                                                <span class="chip-text" style="white-space: pre;">' . $model->bankDiscountStatus . '</span>
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
                    'template' => ' {update}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a(
                                            $model->bank_discount_status == BankDiscount::BANK_DISCOUNT_STATUS_ACTIVE ? 'Deactivate' : 'Activate', ['change-bank-discount-status', 'id' => $model->bank_discount_id, 'storeUuid' => $model->restaurant_uuid], [
                                        'title' => $model->bank_discount_status == BankDiscount::BANK_DISCOUNT_STATUS_EXPIRED ? 'Deactivate' : 'Activate',
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
