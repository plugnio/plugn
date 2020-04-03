<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Category;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\CategoryItem;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Item */
/* @var $form yii\widgets\ActiveForm */


$js = "

$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

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

<div class="item-form">

    <?php
    $categoryQuery = Category::find()->asArray()->all();
    $categoryArray = ArrayHelper::map($categoryQuery, 'category_id', 'category_name');

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
                'enableClientScript' => false,
    ]);
    ?>

    <?= $form->errorSummary($model); ?>

    <?=
        $form->field($model, 'items_category[]')->dropDownList($categoryArray, [
            'class' => 'select2',
            'multiple' => 'multiple',
            'value' => $itemCategoryValues
        ]);
    ?>


    <?= $form->field($model, 'item_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_description_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_number')->textInput() ?>

    <?= $form->field($model, 'stock_qty')->textInput() ?>

    <?= $form->field($model, 'item_price')->textInput(['value' => $model->item_price != null ? $model->item_price : 0]) ?>

    
    <?=
    $form->field($model, 'image', [
        'template' => "{label}"
        . "            <div class='input-group'>"
        . "             <div class='custom-file'>"
        . "                 {input}"
        . "                 <label class='custom-file-label' for='exampleInputFile'>Choose file</label>"
        . "             </div>"
        . "            </div>"
    ])->fileInput([
        'multiple' => false,
        'accept' => 'image/*',
        'class' => 'custom-file-input',
    ])
    ?>



    <div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
