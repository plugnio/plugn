<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\TapRequirements */
/* @var $form yii\widgets\ActiveForm */

$countryArray = \yii\helpers\ArrayHelper::map(\agent\models\Country::find()->all(), "country_id", "country_name");

?>

<div class="tap-requirements-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'country_id')->widget(Select2::classname(), [
        'data' => $countryArray,
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Country');
    ?>

    <?= $form->field($model, 'requirement_en')->widget(\bizley\quill\Quill::class, []) ?>

    <?= $form->field($model, 'requirement_ar')->widget(\bizley\quill\Quill::class, []) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
