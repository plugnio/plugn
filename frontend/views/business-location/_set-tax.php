<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Country;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessLocation */
/* @var $form yii\widgets\ActiveForm */

$this->params['restaurant_uuid'] = $storeUuid;
$this->title = 'Configure tax for this location';
$this->params['breadcrumbs'][] = ['label' => 'Business Locations', 'url' => ['index',  'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = $this->title;

?>


  <div class="card">
<div class="business-location-form card-body">

    <?php
          $form = ActiveForm::begin();
      ?>

    <?= $form->field($model, 'business_location_tax', [
        'template' => "{label}"

        . "<div  class='input-group'>
            <div class='input-group-prepend'>
              <span class='input-group-text'> % </span>
            </div>
              {input}
          </div>
        "
        . "{error}{hint}"
    ])->textInput([
      'type' => 'number',
      'step' => '.01',
      'style' => '    border-top-left-radius: 0px !important;   border-bottom-left-radius: 0px !important;']) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
