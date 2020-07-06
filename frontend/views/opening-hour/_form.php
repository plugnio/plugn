<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\OpeningHour;

/* @var $this yii\web\View */
/* @var $model common\models\OpeningHour */
/* @var $form yii\widgets\ActiveForm */
?>




<section id="data-list-view" class="data-list-view-header">


<!-- DataTable starts -->
<div class="card table-responsive">
  <?php $form = ActiveForm::begin(); ?>

  <div class="card-body">

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
              <?php foreach ($models as $index => $model) { ?>
                <tr>

                      <td class="product-name">
                        <?= $model->getDayOfWeek() ?>
                      </td>
                      <td class="product-category">
                          <?= $form->field($model, 'open_time')->textInput(['class' => 'form-control pickatime']); ?>
                      </td>
                      <td >
                          <?= $form->field($model, 'close_time')->textInput(['class' => 'form-control pickatime']); ?>
                      </td>
                    </tr>

          <?php } ?>

        </tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
<!-- DataTable ends -->

  </section>
<!-- Data list view end -->
