<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin(); ?>

<div class="form-group">
    <label class="control-label">Image url</label>
    <?= Html::input('text', 'category_image', isset($category['category_image'])? $category['category_image']: null, ['class' => 'form-control']) ?>
</div>
 
<div class="form-group required">
    <label class="control-label">Sort number</label>
    <?= Html::input('number', 'sort_number', isset($category['sort_number']) ? $category['sort_number']: null, ['required'=>true, 'class' => 'form-control']) ?>
</div>

<div class="form-group required">
    <label class="control-label">Url slug</label>
    <?= Html::input('text', 'slug', isset($category['slug'])? $category['slug']: null, ['required'=>true, 'class' => 'form-control']) ?>
</div>

<h5>English </h5>
<?php if(isset($category["ID"])) { ?>
<input type="hidden" name='blogCategoryDescriptions[0][ID]' value="<?= isset($category["blogCategoryDescriptions"][0]['ID'])? $category["blogCategoryDescriptions"][0]['ID']: null ?>" />
<?php } ?>

<input type="hidden" name='blogCategoryDescriptions[0][language_code]' value="en" />

<div class="form-group required">
    <label class="control-label">Title</label>
    <?= Html::input('text', 'blogCategoryDescriptions[0][title]', isset($category["blogCategoryDescriptions"][0]['title'])? $category["blogCategoryDescriptions"][0]['title']: null, ['required'=>true, 'class' => 'form-control']) ?>
</div>

<div class="form-group required">
    <label class="control-label">Description</label>
    <?= Html::textarea('blogCategoryDescriptions[0][description]', isset($category["blogCategoryDescriptions"][0]['description'])? $category["blogCategoryDescriptions"][0]['description']: null, ['required'=>true, 'class' => 'form-control']) ?>
</div>

<h5>Arabic </h5>
<?php if(isset($category["ID"])) { ?>
<input type="hidden" name='blogCategoryDescriptions[1][ID]' value="<?= isset($category["blogCategoryDescriptions"][1]['ID'])? $category["blogCategoryDescriptions"][1]['ID']: null ?>" />
<?php } ?>

<input type="hidden" name='blogCategoryDescriptions[1][language_code]' value="ar" />

<div class="form-group required">
    <label class="control-label">Title</label>
    <?= Html::input('text', 'blogCategoryDescriptions[1][title]', isset($category["blogCategoryDescriptions"][1]['title'])? $category["blogCategoryDescriptions"][1]['title']: null, ['required'=>true, 'class' => 'form-control']) ?>
</div>

<div class="form-group required">
    <label class="control-label">Description</label>
    <?= Html::textarea('blogCategoryDescriptions[1][description]', isset($category["blogCategoryDescriptions"][1]['description'])? $category["blogCategoryDescriptions"][1]['description']: null, ['required'=>true, 'class' => 'form-control']) ?>
</div>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
</div>


<?php ActiveForm::end(); ?>