<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Category;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\CategoryItem;
use kartik\file\FileInput;
use wbraganca\dynamicform\DynamicFormWidget;
use common\models\ExtraOption;
use common\models\Option;

/* @var $this yii\web\View */
/* @var $model common\models\Item */
/* @var $form yii\widgets\ActiveForm */
$js = "  $(function () {

    //
    // $(document).ready(function(){
    //     if ($('.kv-file-zoom').length > 1){
    //         $( '.file-preview-initial' ).hide();
    //     }
    // });

  })";

$this->registerJs($js);
?>


<div class="item-form">

    <?php
    $categoryQuery = Category::find()->where(['restaurant_uuid' => $modelItem->restaurant_uuid])->asArray()->all();
    $categoryArray = ArrayHelper::map($categoryQuery, 'category_id', 'title');

    $itemCategoryValues = [];

    if ($modelItem->item_uuid != null) {
        $selectedCategoryValues = $modelItem->getCategories()->asArray()->all();
        $itemCategoryValues = ArrayHelper::getColumn($selectedCategoryValues, 'category_id');
    }

    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
//                'enableClientScript' => false,
    ]);
    ?>




    <div class="card">
        <div class="card-body">
            <?=
            $form->field($modelItem, 'items_category[]')->dropDownList($categoryArray, [
                'class' => 'select2',
                'multiple' => 'multiple',
                'value' => $itemCategoryValues
            ]);
            ?>

            <?= $form->field($modelItem, 'item_name')->textInput(['maxlength' => true, 'placeholder' => 'e.g. The Famous Burger, Short sleeve t-shirt']) ?>

            <?= $form->field($modelItem, 'item_name_ar')->textInput(['maxlength' => true, 'placeholder' => 'e.g. The Famous Burger']) ?>

            <?= $form->field($modelItem, 'item_description')->textarea(['class' => 'textarea', 'style' => 'style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"']) ?>

            <?= $form->field($modelItem, 'item_description_ar')->textarea(['class' => 'textarea', 'style' => 'style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"']) ?>

            <?= $form->field($modelItem, 'sort_number')->textInput(['type' => 'number']) ?>

            <?= $form->field($modelItem, 'stock_qty')->textInput(['type' => 'number']) ?>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
          <?php
          $initialPreviewArray = $modelItem->getItemImages()->asArray()->all();
          $initialPreviewArray = ArrayHelper::getColumn($initialPreviewArray, 'product_file_name');

          foreach ($initialPreviewArray as $key => $file_name)
            $initialPreviewArray[$key] = "https://res.cloudinary.com/plugn/image/upload/restaurants/". $modelItem->restaurant->restaurant_uuid ."/items/". $file_name;


          ?>


            <h5 style="margin-bottom: 20px;">
              Media
            </h5>

            <?php

            echo $form->field($modelItem, 'item_images[]')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*', 'multiple' => true
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                    'initialPreview'=> $initialPreviewArray,
                    'initialPreviewAsData' => true,
                    'showRemove' => true,
                    'allowedFileExtensions' => ['jpg', 'png', 'jpeg'],
                    'overwriteInitial' => true,
                    'uploadAsync' => true,
                    'showUploadedThumbs' => true,
                    // 'initialPreviewCount' => 1,
                    'validateInitialCount' => false,
                    'maxFileCount' => 10,
                    'initialPreviewShowDelete' => false,
                    'maxFileSize' => 2800
                ]
            ])->label(false);
            ?>



        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <h5 style="margin-bottom: 20px;">
                Price
            </h5>


            <?=
            $form->field($modelItem, 'item_price', [
                'template' => "{label}"
                . "<div class='input-group'> <div class='input-group-prepend'> <span class='input-group-text'>KWD</span> </div>{input}"
                . "</div>"
                . "{error}{hint}"
            ])->textInput([
                'type' => 'number',
                'step' => '.01',
                'value' => $modelItem->item_price != null ? $modelItem->item_price : \Yii::$app->formatter->asDecimal(0, 2),
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

                <?php
                DynamicFormWidget::begin([
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
                ]);
                ?>
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
                                    if (!$modelOption->isNewRecord) {
                                        echo Html::activeHiddenInput($modelOption, "[{$indexOption}]option_id");
                                    }
                                    ?>
                                    <?= $form->field($modelOption, "[{$indexOption}]option_name")->label(false)->textInput(['maxlength' => true, 'placeholder' => 'e.g. Color']) ?>
                                    <?= $form->field($modelOption, "[{$indexOption}]option_name_ar")->label(false)->textInput(['maxlength' => true, 'placeholder' => 'على سبيل المثال اللون']) ?>
                                    <?= $form->field($modelOption, "[{$indexOption}]min_qty")->label(false)->textInput(['type' => 'number', 'maxlength' => true, 'placeholder' => 'Minimum']) ?>
                                    <?= $form->field($modelOption, "[{$indexOption}]max_qty")->label(false)->textInput(['type' => 'number', 'maxlength' => true, 'placeholder' => 'Maximum']) ?>
                                </td>
                                <td>
                                    <?=
                                    $this->render('_form-extra-options', [
                                        'form' => $form,
                                        'indexOption' => $indexOption,
                                        'modelsExtraOption' => (empty($modelsExtraOption[$indexOption])) ? [[new ExtraOption]] : $modelsExtraOption[$indexOption],
                                    ])
                                    ?>
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
