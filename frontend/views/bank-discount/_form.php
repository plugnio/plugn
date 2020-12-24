<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Bank;
use common\models\BankDiscount;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Voucher */
/* @var $form yii\widgets\ActiveForm */

$currencyCode = $model->currency->code;

$js = "


        $('#bankdiscount-duration').attr('autocomplete','off');
        $('#bankdiscount-duration').attr('style', '  padding-right: 2rem !important; padding-left: 3rem !important; ');

        let discountType = $('.discountType');

        $( window ).on( 'load', function() {
            if ('$model->discount_type' == 1)
                $('#discountAmount').text('%');
            else   if ('$model->discount_type' == 2)
                $('#discountAmount').text('$currencyCode');
            else
               $('#discountAmount').text('$currencyCode');

        });

        discountType.change(function(){
          let selection = $(this).val();

          if (selection == 1)
              $('#discountAmount').text('%');
          else   if (selection == 2)
              $('#discountAmount').text('$currencyCode');

        });
";

$this->registerJs($js);


if (!$model->isNewRecord) {

    echo Html::a('Delete', ['delete', 'id' => $model->bank_discount_id, 'storeUuid' => $model->restaurant_uuid], [
        'class' => 'btn btn-danger  mr-1 mb-1',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]);
}
?>

<div class="card">
    <div class="card-body voucher-form">


        <?php
        $bankQuery = Bank::find()->asArray()->all();
        $bankList = ArrayHelper::map($bankQuery, 'bank_id', 'bank_name');


        $form = ActiveForm::begin([
                    'errorSummaryCssClass' => 'alert alert-danger'
        ]);
        ?>

        <?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>

        <?= $form->field($model, 'bank_id')->dropDownList($bankList, ['prompt' => 'Select...'], ['class' => 'form-control select2'])->label('Bank'); ?>


        <?=
        $form->field($model, 'discount_type')->radioList([BankDiscount::DISCOUNT_TYPE_AMOUNT => 'Amount', BankDiscount::DISCOUNT_TYPE_PERCENTAGE => 'Percentage',], [
            'value' => $model->discount_type !== null ? $model->discount_type : BankDiscount::DISCOUNT_TYPE_AMOUNT,
            'style' => 'display:grid',
            'item' => function($index, $label, $name, $checked, $value) {

                $return = '<label class="vs-radio-con">';
                /* -----> */ if ($checked)
                    $return .= '<input class="discountType"  checked  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
                /* -----> */
                else
                    $return .= '<input  class="discountType" type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
                $return .= '<span class="vs-radio"> <span class="vs-radio--border"></span> <span class="vs-radio--circle"></span> </span>';
                $return .= '<span>' . ucwords($label) . '</span>';
                $return .= '</label>';

                return $return;
            }
        ])->label('Discount Type : ');
        ?>

        <div class="row">
            <div class="col-12 col-sm-6 col-lg-6">


                <?=
                $form->field($model, 'discount_amount', [
                    'options' => ['class' => 'form-group position-relative input-divider-right'],
                    'template' => '
                                  {label}
                                  <div class="form-group position-relative input-divider-right">
                                      {input}
                                  <div class="form-control-position" style="    width: 4.5rem;">' .
                    '<span id="discountAmount"></span>' .
                    '</div> {hint}{error}</div>',
                ])->textInput([
                'autocomplete' => 'off'
                ])
                ?>

            </div>
            <div class="col-12 col-sm-6 col-lg-6">

                <?=
                $form->field($model, 'duration', [
                    'labelOptions' => ['class' => 'control-label'],
                    'template' => '
                        {label}
                     <div class="position-relative has-icon-left">

                          {input}

                       <div class="form-control-position">
                        <i class="feather icon-calendar"></i>
                      </div>
                    </div>'
                ])->widget(DateRangePicker::classname(), [
                    'presetDropdown' => false,
                    'convertFormat' => true,
                    'pluginOptions' => ['locale' => ['format' => 'Y-m-d'],],
                ]);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6 col-lg-4">
                <?= $form->field($model, 'max_redemption')->textInput(['value' => $model->max_redemption ? $model->max_redemption : 0])->label('Max. Redemptions <span style="color: rgba(0,0,0,.45);">(0 = unlimited)</span>') ?>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <?= $form->field($model, 'limit_per_customer')->textInput(['value' => $model->limit_per_customer ? $model->limit_per_customer : 0])->label('Limit Per Customer <span style="color: rgba(0,0,0,.45);">(0 = unlimited)</span>') ?>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <?= $form->field($model, 'minimum_order_amount')->textInput(['value' => $model->minimum_order_amount ? $model->minimum_order_amount : 0])->label('Minimum Order Amount ') ?>
            </div>
        </div>
        <div class="form-group" style="background: #f4f6f9; padding-bottom: 0px; margin-bottom: 0px;  background:#f4f6f9 ">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
