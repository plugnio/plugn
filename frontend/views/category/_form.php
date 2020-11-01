<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use common\components\FileUploader;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */

$url = yii\helpers\Url::to(['delete-category-image', 'restaurantUuid' => $model->restaurant_uuid, 'categoryId' => $model->category_id]);


$js = <<< JS

// enable fileuploader plugin
$('input[class="category-upload"]').fileuploader({
	limit: 1,
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

   if('$model->category_id'){

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

<div class="card category-form">
    <div class="card-body">

        <?php
        $form = ActiveForm::begin([
                    'enableClientScript' => false,
                    'errorSummaryCssClass' => 'alert alert-danger'
        ]);


        // $initialPreviewArray = $modelItem->getItemImages()->asArray()->all();
        // $uploadsFiles = ArrayHelper::getColumn($initialPreviewArray, 'product_file_name');
        $preloadedFiles = array();

        if ($model->category_image) {
            $file_name = $model->category_image;

            $file_location = "https://res.cloudinary.com/plugn/image/upload/restaurants/" . $model->restaurant_uuid . "/category/" . $model->category_image;
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
        ?>

        <?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>

        <div class="row">
            <div class="col-12 col-sm-6 col-lg-6">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'e.g. Meal Deals or Sushi Sets or Soft Drinks', 'autocomplete' => 'off']) ?>
            </div>

            <div class="col-12 col-sm-6 col-lg-6">

<?= $form->field($model, 'title_ar')->textInput(['maxlength' => true, 'placeholder' => 'e.g. Meal Deals or Sushi Sets or Soft Drinks', 'autocomplete' => 'off']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-lg-6">

<?= $form->field($model, 'subtitle')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
            </div>
            <div class="col-12 col-sm-6 col-lg-6">

                <?= $form->field($model, 'subtitle_ar')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
            </div>
        </div>


<?= $form->field($model, 'sort_number')->textInput(['autocomplete' => 'off']) ?>

<?php
echo $form->field($model, 'image')->fileinput(['name' => 'category_image', 'class' => 'category-upload', 'data-fileuploader-files' => $preloadedFiles]);
?>

        <div class="form-group" style="background: #f4f6f9; margin-bottom: 0px; padding-bottom: 0px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
        </div>

            <?php ActiveForm::end(); ?>

    </div>
</div>
