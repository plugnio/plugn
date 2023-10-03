<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin(); ?>

<div class="form-group">
    <label class="control-label">Image url</label>
    <?= Html::input('text', 'post_image', isset($post['post_image'])? $post['post_image']: null, ['class' => 'form-control']) ?>
</div>

<div class="form-group">
    <label class="control-label">Video url</label>
    <?= Html::input('text', 'post_video', isset($post['post_video'])? $post['post_video']: null, ['class' => 'form-control']) ?>
</div>

<div class="form-group required">
    <label class="control-label">Sort number</label>
    <?= Html::input('number', 'sort_number', isset($post['sort_number']) ? $post['sort_number']: null, ['required'=>true, 'class' => 'form-control']) ?>
</div>

<div class="form-group required">
    <label class="control-label">Url slug</label>
    <?= Html::input('text', 'slug', isset($post['slug'])? $post['slug']: null, ['required'=>true, 'class' => 'form-control']) ?>
</div>

<h5>English </h5>
<?php if(isset($post["ID"])) { ?>
<input type="hidden" name='blogPostDescriptions[0][ID]' value="<?= isset($post["blogPostDescriptions"][0]['ID'])? $post["blogPostDescriptions"][0]['ID']: null ?>" />
<?php } ?>
<input type="hidden" name='blogPostDescriptions[0][language_code]' value="en" />

<div class="form-group required">
    <label class="control-label">Title</label>
    <?= Html::input('text', 'blogPostDescriptions[0][title]', isset($post["blogPostDescriptions"][0]['title'])? $post["blogPostDescriptions"][0]['title']: null, ['required'=>true, 'class' => 'form-control']) ?>
</div>

<div class="form-group required">
    <label class="control-label">Description</label>
    <?= Html::textarea('blogPostDescriptions[0][description]', isset($post["blogPostDescriptions"][0]['description'])? $post["blogPostDescriptions"][0]['description']: null, ['required'=>true, 'class' => 'form-control']) ?>
</div>

<h5>Arabic </h5>
<?php if(isset($post["ID"])) { ?>
<input type="hidden" name='blogPostDescriptions[1][ID]' value="<?= isset($post["blogPostDescriptions"][1]['ID'])? $post["blogPostDescriptions"][1]['ID']: null ?>" />
<?php } ?>

<input type="hidden" name='blogPostDescriptions[1][language_code]' value="ar" />

<div class="form-group required">
    <label class="control-label">Title</label>
    <?= Html::input('text', 'blogPostDescriptions[1][title]', isset($post["blogPostDescriptions"][1]['title'])? $post["blogPostDescriptions"][1]['title']: null, ['required'=>true, 'class' => 'form-control']) ?>
</div>

<div class="form-group required">
    <label class="control-label">Description</label>
    <?= Html::textarea('blogPostDescriptions[1][description]', isset($post["blogPostDescriptions"][1]['description'])? $post["blogPostDescriptions"][1]['description']: null, ['required'=>true, 'class' => 'form-control']) ?>
</div>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
</div>


<?php ActiveForm::end(); ?>