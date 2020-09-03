<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="row flexbox-container">
             <div class="col-xl-7 col-10 d-flex justify-content-center">
                 <div class="card bg-authentication rounded-0 mb-0 w-100">
                     <div class="row m-0">
                         <div class="col-lg-6 d-lg-block d-none text-center align-self-center p-0">
                             <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/app-assets/images/pages/reset-password.png' ?>"  alt="branding logo">
                         </div>
                         <div class="col-lg-6 col-12 p-0">
                             <div class="card rounded-0 mb-0 px-2">
                                 <div class="card-header pb-1">
                                     <div class="card-title">
                                         <h4 class="mb-0">Reset Password</h4>
                                     </div>
                                 </div>
                                 <p class="px-2">Please enter your new password.</p>
                                 <div class="card-content">
                                     <div class="card-body pt-1">
                                       <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                                             <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                                             <div class="row pt-2">
                                                 <div class="col-12 col-md-6 mb-1">
                                                     <?= Html::a('Go Back to Login', ['login'], ['class' => 'btn btn-outline-primary btn-block px-0']) ?>
                                                 </div>
                                                 <div class="col-12 col-md-6 mb-1">
                                                     <?= Html::submitButton('Reset', ['class' => 'btn btn-primary btn-block px-0']) ?>
                                                 </div>
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
