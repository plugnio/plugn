<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>



<!-- Header -->
<header id="header" class="ex-2-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>Sign Up</h1>
                <p>Fill out the form below to sign up for a Plugn website. Already signed up? Then just 
                    <?=
                        Html::a('Log In', ['site/login'],['class' => 'black']);
                    ?>
                </p>
                <!-- Sign Up Form -->
                <div class="form-container">
                    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>


                    <?=
                    $form->field($model, 'company_name', ['options' => [
                            'tag' => 'div',
                            'class' => 'form-group'
                        ],
                        'template' => '{input}{error}{hint}'
                    ])->textInput(['text' => 'text', 'placeholder' => 'Company name'])
                    ?>                   

                    <?=
                    $form->field($model, 'name', ['options' => [
                            'tag' => 'div',
                            'class' => 'form-group'
                        ],
                        'template' => '{input}{error}{hint}'
                    ])->textInput(['text' => 'text', 'placeholder' => 'Name'])
                    ?>

                    <?=
                    $form->field($model, 'phone', ['options' => [
                            'tag' => 'div',
                            'class' => 'form-group'
                        ],
                        'template' => '{input}{error}{hint}'
                    ])->textInput(['text' => 'email', 'placeholder' => 'Phone'])
                    ?>


                    <?=
                    $form->field($model, 'email', ['options' => [
                            'tag' => 'div',
                            'class' => 'form-group'
                        ],
                        'template' => '{input}{error}{hint}'
                    ])->textInput(['type' => 'email', 'placeholder' => 'Email'])
                    ?>


                    <div class="form-group">
                        <?= Html::submitButton('SEND REQUEST', ['class' => 'form-control-submit-button', 'name' => 'contact-button']) ?>
                    </div>
                </div> <!-- end of form container -->
                <!-- end of sign up form -->

            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</header> <!-- end of ex-header -->
<!-- end of header -->

