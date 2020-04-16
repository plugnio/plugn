<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>


<!-- Header -->
<header id="header" class="ex-2-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>Log In</h1>
                <p>You don't have a password? Then please             
                    <?=
                        Html::a('Sign up', ['site/signup'],['class' => 'black']);
                    ?></p>
                <!-- Sign Up Form -->
                <div class="form-container">
                    <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientScript' => false]); ?>
                    <div class="form-group">
                        <?=
                        $form->field($model, 'email', ['options' => [
                                'tag' => 'div',
                                'class' => 'form-group field-loginform has-feedback required'
                            ],
                            'template' => '{input}{error}{hint}'
                        ])->textInput(['type' => 'email', 'placeholder' => 'Email'])
                        ?>

                    </div>
                    <div class="form-group">
                        
                    <?=
                    $form->field($model, 'password', ['options' => [
                            'tag' => 'div',
                            'class' => 'form-group field-loginform has-feedback required'
                        ],
                        'template' => '{input}{error}{hint}'
                    ])->passwordInput(['placeholder' => 'Password'])
                    ?>
                        
                    </div>
                    <div class="form-group">
                         <?= Html::submitButton('LOG IN', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                    </div>
                    <div class="form-message">
                        <div id="lmsgSubmit" class="h3 text-center hidden"></div>
                    </div>
                    <?php ActiveForm::end(); ?>


                </div> <!-- end of form container -->
                <!-- end of sign up form -->

            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</header> <!-- end of ex-header -->
<!-- end of header -->


 
