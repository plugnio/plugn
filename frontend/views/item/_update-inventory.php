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


$form = ActiveForm::begin([
						'enableClientScript' => false,
]);
		?>

		<div class="row">
		<div class="col-8" style="padding:0px">
			<?php
		echo $form->field($model, 'stock_qty', [
					'options' => ['style' => 'margin: 0px'],
					'template' => '
				 {input}
				 '
			])
			->textInput(['type' => 'number', 'value' => 0, 'min' => 0, 'style' => ' border-top-right-radius: unset !important;border-bottom-right-radius: unset !important;'])->label(false);
			?>
		</div>
		<div class="col-4" style="padding:0px">

				<?=
				Html::submitButton('Save', ['style' => 'margin-right: 20px; border-top-left-radius: unset;   border-bottom-left-radius: unset;height: calc(1.25em + 1.4rem + 0px);', 'class' => 'btn btn-success', 'name' => $model->item_uuid])
				?>

		</div>
		</div>


				<?php

ActiveForm::end();

?>
