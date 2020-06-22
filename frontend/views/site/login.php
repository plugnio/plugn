<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>



<section class="row flexbox-container">
    <div class="col-xl-8 col-11 d-flex justify-content-center">
        <div class="card bg-authentication rounded-0 mb-0">
            <div class="row m-0">
                <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                    <img src="app-assets/images/pages/login.png" alt="branding logo">
                </div>
                <div class="col-lg-6 col-12 p-0">
                    <div class="card rounded-0 mb-0 px-2">
                        <div class="card-header pb-1">
                            <div class="card-title">
                                <h4 class="mb-0">Login</h4>
                            </div>
                        </div>
                        <p class="px-2">Welcome back, please login to your account.</p>
                        <div class="card-content" style="padding:21px">
                              <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientScript' => false]); ?>
                              <div class="form-label-group form-group position-relative has-icon-left">
                                  <?=
                                  $form->field($model, 'email', ['options' => [
                                          'tag' => 'div',
                                          'class' => 'form-group field-loginform has-feedback required'
                                      ],
                                      'template' => '{input} <div class="form-control-position"> <i class="feather icon-user"></i> </div>{error}{hint}'
                                  ])->textInput(['type' => 'email', 'placeholder' => 'Email','required' => true])
                                  ?>

                              </div>

                              <div class="form-label-group form-group position-relative has-icon-left">

                              <?=
                              $form->field($model, 'password', ['options' => [
                                      'tag' => 'div',
                                      'class' => 'form-group field-loginform has-feedback required'
                                  ],
                                  'template' => '{input}<div class="form-control-position"> <i class="feather icon-lock"></i> </div>{error}{hint}'
                              ])->passwordInput(['placeholder' => 'Password','required' => true])
                              ?>

                              </div>

                              <div class="form-group">
                                   <?= Html::submitButton('Login', ['class' => 'btn btn-primary float-left btn-inline', 'name' => 'login-button']) ?>
                              </div>
                              <!-- <div class="form-message">
                                  <div id="lmsgSubmit" class="h3 text-center hidden"></div>
                              </div> -->

                              <?php ActiveForm::end(); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
