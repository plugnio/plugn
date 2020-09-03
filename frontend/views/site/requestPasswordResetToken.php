<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="row flexbox-container">
    <div class="col-xl-7 col-md-9 col-10 d-flex justify-content-center px-0">
        <div class="card bg-authentication rounded-0 mb-0">
            <div class="row m-0">
                <div class="col-lg-6 d-lg-block d-none text-center align-self-center">
                    <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/app-assets/images/pages/forgot-password.png' ?> " alt="branding logo">
                </div>
                <div class="col-lg-6 col-12 p-0">
                    <div class="card rounded-0 mb-0 px-2 py-1">
                        <div class="card-header pb-1">
                            <div class="card-title">
                                <h4 class="mb-0">Recover your password</h4>
                            </div>
                        </div>
                        <p class="px-2 mb-0">Please enter your email address and we'll send you instructions on how to reset your password.</p>
                        <div class="card-content">
                            <div class="card-body">
                                <?php $form = ActiveForm::begin(['id' => 'request-password', 'enableClientScript' => false]); ?>

                                <div class="form-label-group">

                                    <?=
                                    $form->field($model, 'email', ['options' => [
                                            'tag' => 'div',
                                            'class' => 'form-group field-loginform has-feedback required'
                                        ],
                                        'template' => '{input}{error}{hint}'
                                    ])->textInput(['type' => 'email', 'placeholder' => 'Email', 'required' => true])
                                    ?>

                                </div>


                                <div class="float-md-left d-block mb-1">
                                    <?= Html::a('Back to Login', ['login'], ['class' => 'btn btn-outline-primary btn-block px-75']) ?>
                                </div>
                                <div class="float-md-right d-block mb-1">
                                    <?= Html::submitButton('Recover Password', ['class' => 'btn btn-primary btn-block px-75', 'name' => 'login-button']) ?>
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
