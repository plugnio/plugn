<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-box" style="margin-bottom: 100%;">
    <div class="login-logo">
        <a href="../../index2.html"><b>Admin</b>LTE</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <?php
            $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'enableClientScript' => false
            ]);
            ?>
          
            <?=
            $form->field($model, 'email', ['options' => [
                    'tag' => 'div',
                    'class' => 'form-group field-loginform has-feedback required'
                ],
                'template' => '{input}{error}{hint}'
            ])->textInput(['type' => 'email', 'placeholder' => 'Email'])
            ?>

            <?=
            $form->field($model, 'password', ['options' => [
                    'tag' => 'div',
                    'class' => 'form-group field-loginform has-feedback required'
                ],
                'template' => '{input}{error}{hint}'
            ])->passwordInput(['placeholder' => 'Password'])
            ?>

            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <?=
                        $form->field($model, 'rememberMe', ['options' => [
                                'tag' => 'div',
                                'class' => 'icheck-primary'
                    ]])->checkbox()
                        ?>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                </div>
                <!-- /.col -->
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
