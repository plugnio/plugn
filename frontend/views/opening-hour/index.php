<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opening Hours';
$this->params['breadcrumbs'][] = $this->title;

$this->params['restaurant_uuid'] = $restaurantUuid;

$js = "

    $('thead').hide();
    $('.top').hide();
    $('.bottom').hide();
    $('.form-group').css('margin', '0px');
";
$this->registerJs($js);
?>



<section id="data-list-view" class="data-list-view-header">


<!-- DataTable starts -->
<div class="card table-responsive">

  <div class="card-body">
    <?php $form = ActiveForm::begin(); ?>

    <table class="table ">
        <tbody>
              <?php foreach ($models as $index => $model) { ?>
                <tr>

                      <td style="padding: 5px  15px">
                        <?= $model->getDayOfWeek() ?>
                      </td>
                      <td style="padding: 5px  15px">
                          <?= $form->field($model, "[$index]open_time" )->textInput(['class' => 'form-control' ,'type' => 'time'])->label('Opens at'); ?>
                      </td>
                      <td style="padding: 5px  15px" >
                          <?= $form->field($model, "[$index]close_time")->textInput(['class' => 'form-control' ,'type' => 'time'])->label('Closes at'); ?>
                      </td>
                    </tr>

          <?php } ?>

        </tbody>

    </table>

    <div class="form-group" style="background: #f4f6f9;  margin-bottom: 0px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>



</div>
<!-- DataTable ends -->

  </section>
<!-- Data list view end -->
