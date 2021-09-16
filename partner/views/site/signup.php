<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \frontend\models\SignupForm */


$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php

            $form = ActiveForm::begin(['id' => 'form-signup']);


                    $bankQuery = common\models\Bank::find()->asArray()->all();
                    $bankArray = ArrayHelper::map($bankQuery, 'bank_id', 'bank_name');

            ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'partner_email') ?>

                <?= $form->field($model, 'bank_id')->dropDownList($bankArray, ['prompt' => 'Choose your bank...'])->label('Bank name'); ?>

                <?= $form->field($model, 'benef_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'partner_iban')->textInput() ?>

                <?= $form->field($model, 'tempPassword')->passwordInput(['maxlength' => true])->label('Password *') ?>


                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
