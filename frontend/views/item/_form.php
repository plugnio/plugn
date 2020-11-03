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
use \bizley\quill\Quill;
use common\components\FileUploader;

/* @var $this yii\web\View */
/* @var $model common\models\Item */
/* @var $form yii\widgets\ActiveForm */

$url = yii\helpers\Url::to(['delete-item-image', 'restaurantUuid' => $modelItem->restaurant_uuid, 'itemUuid' => $modelItem->item_uuid]);


$js = <<< JS

$( '.ql-snow' ).css( 'border-radius', '0px' )
$(document).on('wheel', 'input[type=number]', function (e) {
		$(this).blur();
});
$( window ).on( 'load', function() {
		if ('$modelItem->track_quantity' == 1)
			$('#stock_qty').show();
		else
			$('#stock_qty').hide();
});
let trackQuantityInput = $('#trackQuantityInput');
trackQuantityInput.change(function(e){
	let selection = trackQuantityInput.is(':checked');
	if (selection == true)
		$('#stock_qty').show();
	else
		$('#stock_qty').hide();
});


// enable fileuploader plugin
$('input[class="item-upload"]').fileuploader({
	limit:10,
	extensions: ['image/*'],
	addMore: true,
	thumbnails: {
		onItemShow: function (item) {
      // add sorter button to the item html
      		if (item.choosed )
			item.html.find('.fileuploader-action-remove').before('<button type="button" class="fileuploader-action fileuploader-action-sort" title="Sort"><i class="fileuploader-icon-sort"></i></button>');

			if (item.choosed && !item.html.find('.fileuploader-action-edit').length)
				item.html.find('.fileuploader-action-remove').before('<button type="button" class="fileuploader-action fileuploader-action-popup fileuploader-action-edit" title="Edit"><i class="fileuploader-icon-edit"></i></button>');
		}
	},
	sorter: {
		selectorExclude: null,
		placeholder: null,
		scrollContainer: window,
		onSort: function (list, listEl, parentEl, newInputEl, inputEl) {
			// onSort callback
		}
	},
	editor: {
		cropper: {
			ratio: '1:1',
			minWidth: 100,
			minHeight: 100,
			showGrid: true
		}
	},
	onRemove: function (item) {

   if('$modelItem->item_uuid'){
   $.ajax({
             url:'$url',
             type:'POST',
             dataType:'json',
             data:{
                file: item['name']
            }

    });
  }

	},

});

JS;
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
                'errorSummaryCssClass' => 'alert alert-danger'
    ]);
    ?>
    <?= $form->errorSummary([$modelItem], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>


    <div class="card">
        <div class="card-body">
            <?=
            $form->field($modelItem, 'items_category[]')->dropDownList($categoryArray, [
                'class' => 'select2',
                'multiple' => 'multiple',
                'value' => $itemCategoryValues
            ]);
            ?>
            <div class="row">
                <div class="col-12 col-sm-6 col-lg-6">
                    <?= $form->field($modelItem, 'item_name')->textInput(['maxlength' => true, 'placeholder' => 'e.g. The Famous Burger, Short sleeve t-shirt', 'autocomplete' => 'off']) ?>
                </div>
                <div class="col-12 col-sm-6 col-lg-6">
                    <?= $form->field($modelItem, 'item_name_ar')->textInput(['maxlength' => true, 'placeholder' => 'e.g. The Famous Burger', 'autocomplete' => 'off']) ?>
                </div>
            </div>

            <?= $form->field($modelItem, 'item_description')->widget(Quill::class, ['theme' => 'snow', 'toolbarOptions' => 'FULL']) ?>

            <?= $form->field($modelItem, 'item_description_ar')->widget(Quill::class, ['theme' => 'snow', 'toolbarOptions' => 'FULL']) ?>




            <?= $form->field($modelItem, 'sort_number')->textInput(['type' => 'number']) ?>


        </div>

    </div>

    <div class="card">
        <div class="card-body">
            <?php
            $initialPreviewArray = $modelItem->getItemImages()->asArray()->all();
            $uploadsFiles = ArrayHelper::getColumn($initialPreviewArray, 'product_file_name');


            $preloadedFiles = array();


            // add files to our array with
            // made to use the correct structure of a file
            foreach ($uploadsFiles as $file_name) {
                $file_location = "https://res.cloudinary.com/plugn/image/upload/restaurants/" . $modelItem->restaurant->restaurant_uuid . "/items/" . $file_name;

                // add file to our array
                // !important please follow the structure below
                $preloadedFiles[] = array(
                    "name" => $file_name,
                    "type" => FileUploader::mime_content_type($file_location),
                    "size" => null,
                    "file" => $file_location,
                    "local" => $file_location, // same as in form_upload.php
                    "data" => array(
                        "url" => $file_location, // (optional)
                        "readerForce" => true // (optional) prevent browser cache
                    ),
                );
            }

            $preloadedFiles = json_encode($preloadedFiles);

            // foreach ($initialPreviewArray as $key => $file_name)
            //     $initialPreviewArray[$key] = "https://res.cloudinary.com/plugn/image/upload/restaurants/" . $modelItem->restaurant->restaurant_uuid . "/items/" . $file_name;
            ?>


            <h5 style="margin-bottom: 20px;">
                Media
            </h5>

            <?php
            echo $form->field($modelItem, 'item_images[]')->fileinput(['name' => 'item_images', 'class' => 'item-upload', 'data-fileuploader-files' => $preloadedFiles])->label(false);
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
                'style' => 'border-top-left-radius: unset !important; border-bottom-left-radius: unset !important;',
                'value' => $modelItem->item_price != null ? $modelItem->item_price : \Yii::$app->formatter->asDecimal(0, 2),
                'class' => 'form-control'
            ])->label(false)
            ?>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <h5 style="margin-bottom: 20px;">
                Inventory
            </h5>

            <div class="row">

                <div class="col-12 col-sm-6 col-lg-6">

                    <?= $form->field($modelItem, 'sku')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-12 col-sm-6 col-lg-6">
                    <?= $form->field($modelItem, 'barcode')->textInput(['maxlength' => true]) ?>
                </div>


            </div>


            <?=
            $form->field($modelItem, 'track_quantity', [
                'template' => '
            <div class="vs-checkbox-con vs-checkbox-primary">
                {input}
                <span class="vs-checkbox">
                    <span class="vs-checkbox--check">
                        <i class="vs-icon feather icon-check"></i>
                    </span>
                </span>
                <span class="">{label}</span>
            </div>
            <div class=\"col-lg-8\">{error}</div>
            ',
            ])->checkbox([
                'checked' => $modelItem->track_quantity == 0 ? false : true,
                'id' => 'trackQuantityInput',
                    ], false)
            ?>

            <div class="row" id="stock_qty">
                <div class="col-6">
                    <?= $form->field($modelItem, 'stock_qty')->textInput(['type' => 'number']) ?>
                </div>
            </div>
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
                                    <?= $form->field($modelOption, "[{$indexOption}]min_qty")->label(false)->textInput(['type' => 'number', 'maxlength' => true, 'placeholder' => 'Minimum Selection']) ?>
                                    <?= $form->field($modelOption, "[{$indexOption}]max_qty")->label(false)->textInput(['type' => 'number', 'maxlength' => true, 'placeholder' => 'Maximum Selection']) ?>
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
                                    <button type="button" class="add-option btn btn-success btn-xs" style="margin-bottom:10px"><span class="fa fa-plus"></span></button>
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
