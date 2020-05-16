<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Category;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\CategoryItem;
use kartik\file\FileInput;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model common\models\Item */
/* @var $form yii\widgets\ActiveForm */


$js = "

  $(function () {

  })
  
  $(function () {
    // Summernote
    $('.textarea').summernote()
  })
  
$(function () {

    $('#item-image').change(function () {
        filePreview(this);
    });


    //Initialize Select2 Elements
    $('.select2').select2()


    $('.select2').select2({
        placeholder: 'e.g. Burger, Summer collection'
    });


    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })
  
    $(document).ready(function () {
      bsCustomFileInput.init();
    });

";





$this->registerJs($js);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
    function filePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#uploadForm + img').remove();
                $('#uploadForm').after('<img style="margin-left: auto; margin-right: auto; display: block;" src="' + e.target.result + '" width="500" height="300"/>');
                $('.file-drop-zone').css('display', 'none');

            };
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>

<div class="item-form">

    <?php
    
    $categoryQuery = Category::find()->where(['restaurant_uuid' => $model->restaurant_uuid])->asArray()->all();
    $categoryArray = ArrayHelper::map($categoryQuery, 'category_id', 'title');

    $itemCategoryValues = [];

    if ($model->item_uuid != null) {

        $itemCategoryValues = CategoryItem::find()
                ->select('category_id')
                ->asArray()
                ->where(['item_uuid' => $model->item_uuid])
                ->all();

        $itemCategoryValues = ArrayHelper::getColumn($itemCategoryValues, 'category_id');
    }

    $form = ActiveForm::begin([
                    'id' => 'dynamic-form',
//                'enableClientScript' => false,
    ]);
    ?>





    <div class="card">
        <div class="card-body">

            <?=
            $form->field($model, 'items_category[]')->dropDownList($categoryArray, [
                'class' => 'select2',
                'multiple' => 'multiple',
                'value' => $itemCategoryValues
            ]);
            ?>

            <?= $form->field($model, 'item_name')->textInput(['maxlength' => true, 'placeholder' => 'e.g. The Famous Burger, Short sleeve t-shirt']) ?>

            <?= $form->field($model, 'item_name_ar')->textInput(['maxlength' => true, 'placeholder' => 'e.g. The Famous Burger']) ?>


            <?= $form->field($model, 'item_description')->textarea(['class' => 'textarea', 'style' => 'style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"']) ?>

            <?= $form->field($model, 'item_description_ar')->textarea(['class' => 'textarea', 'style' => 'style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"']) ?>

            <?= $form->field($model, 'sort_number')->textInput(['type' => 'number']) ?>

            <?= $form->field($model, 'stock_qty')->textInput(['type' => 'number']) ?>

        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <h5 style="margin-bottom: 20px;">
                Media
            </h5>

            <?=
            $form->field($model, 'image', [
                'template' => "{label}"
                . "            <div class='file-preview'>"
                . "              <div class='clearfix' id='uploadForm'> </div>"
                . "              <div class=' file-drop-zone clearfix'><div class='file-drop-zone-title'>Item image will be displayed here after you upload it</div>"
                . "              <div class='file-preview-thumbnails clearfix'> </div>"
                . "            </div>"
                . "            </div>"
                . "             <div class='input-group'>"
                . "                 <div class='custom-file'>"
                . "                  {input}"
                . "                  <label class='custom-file-label' for='exampleInputFile'>Choose image</label>"
                . "              </div>"
                . "             </div>"
            ])->fileInput([
                'multiple' => false,
                'accept' => 'image/*',
                'class' => 'custom-file-input',
            ])->label(false)
            ?>

        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <h5 style="margin-bottom: 20px;">
                Price
            </h5>


            <?=
            $form->field($model, 'item_price', [
                'template' => "{label}"
                . "<div class='input-group'> <div class='input-group-prepend'> <span class='input-group-text'>KWD</span> </div>{input}"
                . "</div>"
                . "{error}{hint}"
            ])->textInput([
                'type' => 'number',
                'value' => $model->item_price != null ? $model->item_price : \Yii::$app->formatter->asDecimal(0, 2),
                'class' => 'form-control'
            ])->label(false)
            ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 style="margin-bottom: 20px;">
               Options
            </h5>
            
            <div class="item-form">


                <div class="padding-v-md">
                    <div class="line line-dashed"></div>
                </div>

                <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper',
                    'widgetBody' => '.container-items',
                    'widgetItem' => '.option-item',
                    'min' => 0,
                    'insertButton' => '.add-option',
                    'deleteButton' => '.remove-option',
                    'model' => $modelsOption[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                        'option_name',
                        'option_name_ar',
                        'min_qty',
                        'max_qty',
                    ],
                ]); ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Options</th>
                            <th style="width: 450px;">Extra Options</th>
                            <th class="text-center" style="width: 90px;">
                                <button type="button" class="add-option btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="container-items">
                    <?php foreach ($modelsOption as $indexOption => $modelOption): ?>
                        <tr class="option-item">
                            <td class="vcenter">
                                <?php
                                    // necessary for update action.
                                    if (! $modelOption->isNewRecord) {
                                        echo Html::activeHiddenInput($modelOption, "[{$indexOption}]option_id");
                                    }
                                ?>
                                <?= $form->field($modelOption, "[{$indexOption}]option_name")->label(false)->textInput(['maxlength' => true,'placeholder' => 'Option name in English']) ?>
                                <?= $form->field($modelOption, "[{$indexOption}]option_name_ar")->label(false)->textInput(['maxlength' => true,'placeholder' => 'Option name in Arabic']) ?>
                                <?= $form->field($modelOption, "[{$indexOption}]min_qty")->label(false)->textInput(['maxlength' => true,'placeholder' => 'Minimum']) ?>
                                <?= $form->field($modelOption, "[{$indexOption}]max_qty")->label(false)->textInput(['maxlength' => true,'placeholder' => 'Maximum']) ?>
                            </td>
                            <td>
                                <?= $this->render('_form-extra-options', [
                                    'form' => $form,
                                    'indexOption' => $indexOption,
                                    'modelsExtraOption' => $modelsExtraOption[$indexOption],
                                ]) ?>
                            </td>
                            <td class="text-center vcenter" style="width: 90px; verti">
                                <button type="button" class="remove-option btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
                            </td>
                        </tr>
                     <?php endforeach; ?>
                    </tbody>
                </table>
                <?php DynamicFormWidget::end(); ?>


            </div>
            
        </div>
    </div>



    <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px; padding-bottom: 15px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
