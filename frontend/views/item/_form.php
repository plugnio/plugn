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

    $form = ActiveForm::begin();
    ?>

    <?=
    $form->field($model, 'items_category')->widget(Select2::classname(), [
        'data' => $categoryArray,
        'options' => [
            'placeholder' => 'Select items category ...',
            'multiple' => true,
            'value' => $itemCategoryValues
        ],
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [',', ' '],
        ],
    ]);
    ?>

    <?= $form->field($model, 'item_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_description_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_number')->textInput() ?>

    <?= $form->field($model, 'stock_qty')->textInput() ?>

    <?=
    $form->field($model, 'item_image')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', 'multiple' => false
        ],
        'pluginOptions' => [
            'showUpload' => false,
            'initialPreview' => $model->getItemImage(),
            'initialPreviewAsData' => true,
            'overwriteInitial' => true,
            'maxFileSize' => 2800
        ]
    ]);
    ?>

    <?= $form->field($model, 'price')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
