<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Voucher;
use kartik\daterange\DateRangePicker;


/* @var $this yii\web\View */
/* @var $model common\models\Voucher */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="card">
<div class="card-body voucher-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Free Delivery']) ?>

    <?= $form->field($model, 'title_ar')->textInput(['maxlength' => true, 'placeholder' => 'توصيل مجاني']) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true,'placeholder' => '50OffOnFirstOrder']) ?>

    <?=
    $form->field($model, 'discount_type')->radioList([Voucher::DISCOUNT_TYPE_AMOUNT => 'Amount', Voucher::DISCOUNT_TYPE_PERCENTAGE => 'Percentage',], [
        'style' => 'display:grid',
        'item' => function($index, $label, $name, $checked, $value) {

            $return = '<label class="vs-radio-con">';
            /* -----> */ if ($checked)
                $return .= '<input checked  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
            /* -----> */
            else
                $return .= '<input  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
            $return .= '<span class="vs-radio"> <span class="vs-radio--border"></span> <span class="vs-radio--circle"></span> </span>';
            $return .= '<span>' . ucwords($label) . '</span>';
            $return .= '</label>';

            return $return;
        }
        ])->label('Discount Type : ');
    ?>


    <?= $form->field($model, 'discount_amount')->textInput() ?>

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

    <?= $form->field($model, 'max_redemption')->textInput(['value' => 0])->label('Max. Redemptions <span style="color: rgba(0,0,0,.45);">(0 = unlimited)</span>') ?>

    <?= $form->field($model, 'limit_per_customer')->textInput(['value' => 0])->label('Max. Redemptions <span style="color: rgba(0,0,0,.45);">(0 = unlimited)</span>') ?>


    <?= $form->field($model, 'minimum_order_amount')->textInput(['value' => 0])->label('Max. Redemptions <span style="color: rgba(0,0,0,.45);">(0 = unlimited)</span>') ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
