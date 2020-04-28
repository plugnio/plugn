<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Order;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Refund */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="refund-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'refund_amount')->textInput() ?>

    <?= $form->field($model, 'reason')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
