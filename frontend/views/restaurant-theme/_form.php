<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantTheme */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-theme-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'primary')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'secondary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tertiary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'light')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'medium')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'dark')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
