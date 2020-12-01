<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $agent_model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use common\models\Country;
use common\models\Currency;


$this->title = 'Create an Online Store';
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
                                <h4 class="mb-0">Create an Online Store</h4>
                            </div>
                        </div>
                        <p class="px-2">Fill the below form to create an online store.</p>
                        <div class="card-content">
                            <div class="card-body pt-0">
                                <?php

                                      $form = ActiveForm::begin(['id' => 'store-form', 'enableClientScript' => false]);

                                      $countryQuery = Country::find()->asArray()->all();
                                      $countryArray = ArrayHelper::map($countryQuery, 'country_id', 'country_name');


                                        $currencyQuery = Currency::find()->asArray()->all();
                                        $currencyArray = ArrayHelper::map($currencyQuery, 'currency_id', 'title');

                                ?>


                                <?= $form->field($agent_model, 'agent_name')->textInput(['maxlength' => true])->label('Your name *') ?>

                                <?= $form->field($agent_model, 'agent_email')->textInput(['maxlength' => true,'type' => 'email'])->label('Your email *') ?>

                                <?= $form->field($store_model, 'owner_number')->textInput(['maxlength' => true])->label('Your phone number *') ?>

                                <?= $form->field($store_model, 'name')->textInput(['maxlength' => true])->label('Your store name *') ?>


                                <?=
                                $form->field($store_model, 'restaurant_domain', [
                                    'template' => "{label}"
                                    . "<div class='input-group'>
                                           {input}
                                            <div class='input-group-append'>
                                                <span class='input-group-text' id='basic-addon2'>.plugn.store</span>
                                            </div>
                                       "
                                    . "</div>

                                    <small>You can connect a domain once your account is created</small>
                                    "
                                    . "{error}{hint}"
                                ])->textInput([
                                    'class' => 'form-control'
                                ])->label('Store URL *')
                                ?>

                                <?= $form->field($store_model, 'country_id')->dropDownList($countryArray)->label('Business location *'); ?>


                                <?= $form->field($store_model, 'currency_id')->dropDownList($currencyArray)->label('Store Currency *'); ?>

                                <?= $form->field($agent_model, 'tempPassword')->passwordInput(['maxlength' => true])->label('Password *') ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Create', ['class' => 'btn btn-primary  btn-inline', 'style' => 'width: 100%;',  'name' => 'register-button']) ?>
                                </div>


                                <p>Already have a store?</p>
                                <?= Html::a('Login', ['site/login'], ['class' => 'btn btn-outline-primary float-left btn-inline mb-50']) ?>




                                <?php ActiveForm::end(); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
