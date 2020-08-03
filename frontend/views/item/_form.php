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

/* @var $this yii\web\View */
/* @var $model common\models\Item */
/* @var $form yii\widgets\ActiveForm */

$js = "


    $( '.ql-snow' ).css( 'border-radius', '0px' )


    $(document).on('wheel', 'input[type=number]', function (e) {
        $(this).blur();
    });

    $('.delete-button').click(function() {
        var detail = $(this).closest('.option');
        var updateType = detail.find('.update-type');
        if (updateType.val() === " . json_encode(Option::UPDATE_TYPE_UPDATE) . ") {

            //marking the row for deletion
            updateType.val(" . json_encode(Option::UPDATE_TYPE_DELETE) . ");


                      console.log(updateType.val());
            detail.hide();
        } else {
          console.log('enter heree lseee');

            //if the row is a new row, delete the row
            detail.remove();
        }

    });

";
$this->registerJs($js);
?>


<div class="item-form">



    <?php
    $categoryQuery = Category::find()->where(['restaurant_uuid' => $model->restaurant_uuid])->asArray()->all();
    $categoryArray = ArrayHelper::map($categoryQuery, 'category_id', 'title');

    $itemCategoryValues = [];

    if ($model->item_uuid != null) {
        $selectedCategoryValues = $model->getCategories()->asArray()->all();
        $itemCategoryValues = ArrayHelper::getColumn($selectedCategoryValues, 'category_id');
    }

    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'errorSummaryCssClass' => 'alert alert-danger',
                'enableClientValidation' => false
    ]);
    ?>
    <?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>


    <div class="card">
        <div class="card-body">
            <?=
            $form->field($model, 'items_category[]')->dropDownList($categoryArray, [
                'class' => 'select2',
                'multiple' => 'multiple',
                'value' => $itemCategoryValues
            ]);
            ?>
            <div class="row">
                <div class="col-12 col-sm-6 col-lg-6">
                    <?= $form->field($model, 'item_name')->textInput(['maxlength' => true, 'placeholder' => 'e.g. The Famous Burger, Short sleeve t-shirt']) ?>
                </div>
                <div class="col-12 col-sm-6 col-lg-6">
                    <?= $form->field($model, 'item_name_ar')->textInput(['maxlength' => true, 'placeholder' => 'e.g. The Famous Burger']) ?>
                </div>
            </div>

            <?= $form->field($model, 'item_description')->widget(Quill::class, ['theme' => 'snow', 'toolbarOptions' => 'FULL']) ?>

            <?= $form->field($model, 'item_description_ar')->widget(Quill::class, ['theme' => 'snow', 'toolbarOptions' => 'FULL']) ?>
            <div class="row">
                <div class="col-12 col-sm-6 col-lg-6">

                    <?= $form->field($model, 'sort_number')->textInput(['type' => 'number']) ?>
                </div>
                <div class="col-12 col-sm-6 col-lg-6">

                    <?= $form->field($model, 'stock_qty')->textInput(['type' => 'number']) ?>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php
            $initialPreviewArray = $model->getItemImages()->asArray()->all();
            $initialPreviewArray = ArrayHelper::getColumn($initialPreviewArray, 'product_file_name');

            foreach ($initialPreviewArray as $key => $file_name)
                $initialPreviewArray[$key] = "https://res.cloudinary.com/plugn/image/upload/restaurants/" . $model->restaurant->restaurant_uuid . "/items/" . $file_name;
            ?>


            <h3 style="margin-bottom: 20px;">
                Media
            </h3>

            <?php
            echo $form->field($model, 'item_images[]')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*', 'multiple' => true
                ],
                'pluginOptions' => [
                    'showRemove' => false,
                    'showUpload' => false,
                    'showZoom' => false,
                    'initialPreview' => $initialPreviewArray,
                    'initialPreviewAsData' => true,
                    'allowedFileExtensions' => ['jpg', 'png', 'jpeg'],
                    'overwriteInitial' => true,
                    'uploadAsync' => true,
                    'showUploadedThumbs' => true,
                    'maxFileCount' => 10,
                    'initialPreviewShowDelete' => false,
                    'maxFileSize' => 30000
                ]
            ])->label(false);
            ?>



        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <h3  style="margin-bottom: 20px;">
                Price
            </h3>


            <?=
            $form->field($model, 'item_price', [
                'template' => "{label}"
                . "<div class='input-group'> <div class='input-group-prepend'> <span class='input-group-text'>KWD</span> </div>{input}"
                . "</div>"
                . "{error}{hint}"
            ])->textInput([
                'type' => 'number',
                'step' => '.01',
                'value' => $model->item_price != null ? $model->item_price : \Yii::$app->formatter->asDecimal(0, 2),
                'class' => 'form-control'
            ])->label(false)
            ?>
        </div>
    </div>

    <div style="padding:21px">
        <h3 style="margin-bottom: 20px;">
            Options
        </h3>

        <div class="item-form">


            <div class="padding-v-md">
                <div class="line line-dashed"></div>
            </div>

            <?php foreach ($modelOptions as $indexOption => $modelOption) : ?>
                <div class="row card option option-<?= $indexOption ?>">

                    <div class="card-header">
                        <h4 class="card-title"><?= 'Option Set  ' . intval($indexOption + 1) ?></h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                                <li><a data-action="close"><i class="delete-button feather icon-x"></i></a></li>
                            </ul>
                        </div>
                    </div>



                    <div class="card-content collapse show">
                        <div class="card-body">
                            <?= Html::activeHiddenInput($modelOption, "[$indexOption]option_id") ?>
                            <?= Html::activeHiddenInput($modelOption, "[$indexOption]updateType", ['class' => 'update-type']) ?>

                            <div class="row">
                                <div class="col-12 col-sm-6 col-lg-6">
                                    <?= $form->field($modelOption, "[$indexOption]option_name")->label('Title') ?>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-6">

                                    <?= $form->field($modelOption, "[$indexOption]option_name_ar")->label('Title in Arabic') ?>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-6">

                                    <?= $form->field($modelOption, "[$indexOption]min_qty")->label('Min Selections <span style="color: rgba(0,0,0,.45);">(The minimum number of checkboxes that must be selected)</span>') ?>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-6">

                                    <?= $form->field($modelOption, "[$indexOption]max_qty")->label('Min Selections <span style="color: rgba(0,0,0,.45);">(The maximum number of checkboxes that may be selected)</span>') ?>

                                </div>
                            </div>


                            <div class="form-group">
                                <?= Html::submitButton('Create Extrsa Option Set', ['name' => 'addExtraOption', 'value' => 'true', 'class' => 'btn btn-primary']) ?>
                            </div>

                        </div>
                    </div>


                </div>
            <?php endforeach; ?>


            <div class="form-group">
                <?= Html::submitButton('Create Option Set', ['name' => 'addOption', 'value' => 'true', 'class' => 'btn btn-primary']) ?>
            </div>

        </div>

    </div>



    <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px; padding-bottom: 15px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
