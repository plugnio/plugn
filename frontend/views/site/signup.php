<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $agent_model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="row flexbox-container">
    <div class="col-xl-8 col-10 d-flex justify-content-center">
        <div class="card bg-authentication rounded-0 mb-0">
            <div class="row m-0">
                <div class="col-lg-6 d-lg-block d-none text-center align-self-center pl-0 pr-3 py-0">
                    <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/app-assets/images/pages/register.jpg' ?>" class="img-fluid"  alt="alternative">

                </div>
                <div class="col-lg-6 col-12 p-0">
                    <div class="card rounded-0 mb-0 p-2">
                        <div class="card-header pt-50 pb-1">
                            <div class="card-title">
                                <h4 class="mb-0">Create Account</h4>
                            </div>
                        </div>
                        <p class="px-2">Fill the below form to create a new account.</p>
                        <div class="card-content">
                            <div class="card-body pt-0">
                                <?php $form = ActiveForm::begin(['id' => 'store-form', 'enableClientScript' => false]); ?>

                                <?= $form->field($store_model, 'name')->textInput(['maxlength' => true])->label('Your store name in English') ?>

                                <?= $form->field($store_model, 'name_ar')->textInput(['maxlength' => true])->label('Your store name in Arabic') ?>

                                <?=
                                $form->field($store_model, 'restaurant_domain', [
                                    'template' => "{label}"
                                    . "<div class='input-group'>
                                           {input}
                                            <div class='input-group-append'>
                                                <span class='input-group-text' id='basic-addon2'>.plugn.store</span>
                                            </div>
                                       "
                                    . "</div>"
                                    . "{error}{hint}"
                                ])->textInput([
                                    'class' => 'form-control'
                                ])->label('Store URL')
                                ?>

                                <?= $form->field($agent_model, 'agent_name')->textInput(['maxlength' => true])->label('Owner name') ?>

                                <?= $form->field($agent_model, 'agent_email')->textInput(['maxlength' => true])->label('Owner email') ?>

                                <?= $form->field($agent_model, 'tempPassword')->passwordInput(['maxlength' => true]) ?>


                                <?= Html::a('Login', ['site/login'], ['class' => 'btn btn-outline-primary float-left btn-inline mb-50']) ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Register', ['class' => 'btn btn-primary float-right btn-inline mb-50', 'name' => 'register-button']) ?>
                                </div>


                                <?php ActiveForm::end(); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
