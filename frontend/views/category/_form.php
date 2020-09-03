<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card category-form">
    <div class="card-body">

        <?php
        $form = ActiveForm::begin([
                    'enableClientScript' => false,
                    'errorSummaryCssClass' => 'alert alert-danger'
        ]);
        ?>

        <?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>

        <div class="row">
            <div class="col-12 col-sm-6 col-lg-6">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'e.g. Meal Deals or Sushi Sets or Soft Drinks']) ?>
            </div>

            <div class="col-12 col-sm-6 col-lg-6">

                <?= $form->field($model, 'title_ar')->textInput(['maxlength' => true, 'placeholder' => 'e.g. Meal Deals or Sushi Sets or Soft Drinks']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-lg-6">

                <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-12 col-sm-6 col-lg-6">

                <?= $form->field($model, 'subtitle_ar')->textInput(['maxlength' => true]) ?>
            </div>
        </div>


        <?= $form->field($model, 'sort_number')->textInput() ?>

        <?php
        echo $form->field($model, 'image')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*', 'multiple' => false
            ],
            'pluginOptions' => [
                'showRemove' => false,
                'showUpload' => false,
                'showZoom' => false,
                'initialPreview' => $model->category_image ? $model->getCategoryImage() : false,
                'initialPreviewAsData' => true,
                'allowedFileExtensions' => ['jpg', 'png', 'jpeg'],
                'overwriteInitial' => true,
                'uploadAsync' => true,
                'showUploadedThumbs' => true,
                'maxFileCount' => 1,
                'initialPreviewShowDelete' => false,
                'maxFileSize' => 30000
            ]
        ])->label(false);
        ?>

        <div class="form-group" style="background: #f4f6f9; margin-bottom: 0px; padding-bottom: 0px; background:#f4f6f9 ">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
