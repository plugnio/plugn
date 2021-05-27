<?php

use common\models\Restaurant;
use yii\helpers\Html;
use common\models\Order;
use common\models\AgentAssignment;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;
$this->title = 'Connect existing domain';
?>

<section>


    <?php if (Yii::$app->session->getFlash('successResponse') != null) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fa fa-check"></i> Success!</h5>
            <?= (Yii::$app->session->getFlash('successResponse')) ?>
        </div>
    <?php } ?>
    
  <div class="card">


        <div class="card-header">
            <h5>Domain</h5>
        </div>

        <div class="card-body">
            <div class="card-content">

                <?php $form = ActiveForm::begin(); ?>

                <?=
                $form->field($restaurant_model, 'restaurant_domain', [
                    'template' => "{label}"
                    . "
                   {input}
                  <p style='font-size: 12px; font-weight: 400; line-height: 2rem; text-transform: initial; letter-spacing: initial; color: #637381;'>Enter the domain you want to connect.</p>"
                    . "</div>"
                    . "{error}{hint}"
                ])->textInput([
                    'class' => 'form-control',
                    'required' => true,
                    'type' => 'url'
                ])->label(false)
                ?>


                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => '    float: left;']) ?>
                </div>

                <?php ActiveForm::end();  ?>

            </div>
        </div>

    </div>
</section>
