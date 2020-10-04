<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use kartik\file\FileInput;

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
                                <h4 class="mb-0">Create an online store</h4>
                            </div>
                        </div>
                        <p class="px-2">Fill the below form to create an online store.</p>
                        <div class="card-content">
                            <div class="card-body pt-0">
                              <?php $form = ActiveForm::begin(['id' => 'signup-form', 'enableClientScript' => false]); ?>


                          
                                    <?=
                                    $form->field($model, 'restaurant_logo', [
                                        'template' => "{label}"
                                        . "            <div class='input-group'>"
                                        . "             <div class='custom-file'>"
                                        . "                 {input}"
                                        . "                 <label class='custom-file-label' for='exampleInputFile'>Upload store's logo</label>"
                                        . "             </div>"
                                        . "            </div>"
                                    ])->fileInput([
                                        'multiple' => false,
                                        'accept' => 'image/*',
                                        'class' => 'custom-file-input',
                                    ])->label(false)
                                    ?>


                                    <!-- <div class="form-group row">
                                        <div class="col-12">
                                            <fieldset class="checkbox">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input type="checkbox" checked>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class=""> I accept the terms & conditions.</span>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div> -->


                                    <div class="form-group">
                                        <?= Html::submitButton('Next', ['class' => 'btn btn-primary float-right btn-inline mb-50', 'name' => 'register-button']) ?>
                                    </div>


                                    <?php ActiveForm::end(); ?>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
