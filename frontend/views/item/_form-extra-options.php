<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;


?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-extra-options',
    'widgetItem' => '.extra-option',
    'limit' => 4,
    'min' => 1,
    'insertButton' => '.add-extra-option',
    'deleteButton' => '.remove-extra-option',
    'model' => $modelsExtraOption[0],
    'formId' => 'dynamic-form',
    'formFields' => [
        'extra_option_name',
        'extra_option_name_ar',
        'extra_option_price',
    ],
]); ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th class="text-center">
                <button type="button" class="add-extra-option btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
            </th>
        </tr>
    </thead>
    <tbody class="container-extra-options">
    <?php foreach ($modelsExtraOption as $indexExtraOption => $modelExtraOption): ?>
        <tr class="extra-option">
            <td class="vcenter">
                <?php
                    // necessary for update action.
                    if (! $modelExtraOption->isNewRecord) {
                        echo Html::activeHiddenInput($modelExtraOption, "[{$indexOption}][{$indexExtraOption}]extra_option_id");
                    }
                ?>
                <?= $form->field($modelExtraOption, "[{$indexOption}][{$indexExtraOption}]extra_option_name")->label(false)->textInput(['maxlength' => true, 'placeholder' => 'Extra option name in English']) ?>
                <?= $form->field($modelExtraOption, "[{$indexOption}][{$indexExtraOption}]extra_option_name_ar")->label(false)->textInput(['maxlength' => true, 'placeholder' => 'Extra option name in Arabic']) ?>
                <?= $form->field($modelExtraOption, "[{$indexOption}][{$indexExtraOption}]extra_option_price")->label(false)->textInput(['type' => 'number', 'step' => '.01', 'maxlength' => true, 'placeholder' => 'Extra option price']) ?>
            </td>
            <td class="text-center vcenter" style="width: 90px;">
                <button type="button" class="remove-extra-option btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
            </td>
        </tr>
     <?php endforeach; ?>
    </tbody>
</table>
<?php DynamicFormWidget::end(); ?>
