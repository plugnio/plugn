<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\AgentAssignment;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */
$this->params['restaurant_uuid'] = $storeUuid;

$this->title = 'Change password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-update card">

  <div class="card-content">
    <div class="card-body">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'tempPassword')->passwordInput(['maxlength' => true])?>


        <div class="form-group" style="background: #f4f6f9;; margin-bottom: 0px; background:#f4f6f9 ">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
  </div>

</div>
