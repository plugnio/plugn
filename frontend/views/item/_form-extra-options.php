<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use common\models\ExtraOption;
use yii\widgets\ActiveForm;

$js = "


    $( '.ql-snow' ).css( 'border-radius', '0px' )


    $(document).on('wheel', 'input[type=number]', function (e) {
        $(this).blur();
    });

    $('.delete-button').click(function() {
        var detail = $(this).closest('.extra-option');
        var updateType = detail.find('.update-type');
        if (updateType.val() === " . json_encode(ExtraOption::UPDATE_TYPE_UPDATE) . ") {

            //marking the row for deletion
            updateType.val(" . json_encode(ExtraOption::UPDATE_TYPE_DELETE) . ");


                      console.log(updateType.val());
            detail.hide();
        } else {
          console.log('enter heree lseee');

            //if the row is a new row, delete the row
            detail.remove();
        }

    });

";
$this->registerJs($js);
?>

                            <!-- Exxtra optn -->
                            <?php foreach ($modelExtraOptions as $indexExtraOption => $modelExtraOption) : ?>
                                <div class="row card extra-option extra-option-<?= $i ?>">
                                  <div class="card-header">
                                      <h4 class="card-title"><?= 'Option Values' ?></h4>
                                  </div>


                                        <div class="card-body">
                                          <?= Html::activeHiddenInput($modelExtraOption,  "[{$indexOption}][{$indexExtraOption}]extra_option_id") ?>
                                          <?= Html::activeHiddenInput($modelExtraOption,  "[{$indexOption}][{$indexExtraOption}]option_id") ?>
                                          <?= Html::activeHiddenInput($modelExtraOption,  "[{$indexOption}][{$indexExtraOption}]updateType", ['class' => 'update-type']) ?>

                                          <div class="row">
                                              <div class="col-12 col-sm-4 col-lg-4">
                                                  <?= $form->field($modelExtraOption, "[{$indexOption}][{$indexExtraOption}]extra_option_name")->label('Option Value') ?>
                                              </div>
                                              <div class="col-12 col-sm-4 col-lg-4">

                                                  <?= $form->field($modelExtraOption, "[{$indexOption}][{$indexExtraOption}]extra_option_name_ar")->label('Option Value in Arabic') ?>
                                              </div>

                                              <div class="col-12 col-sm-4 col-lg-4">

                                                  <?= $form->field($modelExtraOption, "[{$indexOption}][{$indexExtraOption}]extra_option_price") ?>
                                              </div>

                                          </div>
                                        </div>
                                        <?= die(json_encode) ?>

                                </div>
                            <?php endforeach; ?>
