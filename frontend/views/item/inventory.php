<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\touchspin\TouchSpin;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Inventory';
$this->params['breadcrumbs'][] = $this->title;

$js = "
$(function () {
  $('.summary').insertAfter('.top');
});


    $(document).on('wheel', 'input[type=number]', function (e) {
        $(this).blur();
    });

";
$this->registerJs($js);
?>



<section id="data-list-view" class="data-list-view-header">


    <?php echo $this->render('_inventory-search', ['model' => $searchModel, 'restaurant_uuid' => $restaurant_model->restaurant_uuid]); ?>


    <!-- DataTable starts -->
    <div class="table-responsive">



        <table class="table data-list-view">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Item name</th>
                    <th>SKU</th>
                    <th>Available</th>
                    <th>Edit quantity available</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($dataProvider->query->all() as $item) {
                    $form = ActiveForm::begin([
                                'enableClientScript' => false,
                    ]);
                    ?>
                    <tr>
                        <td style="vertical-align: inherit;">
                            <?php
                            $itemItmage = $item->getItemImages()->one();
                            if ($itemItmage)
                                echo Html::img("https://res.cloudinary.com/plugn/image/upload/c_scale,h_60,w_60/restaurants/" . $item->restaurant->restaurant_uuid . "/items/" . $itemItmage->product_file_name, ['style' => 'border-radius: 3px;margin-right: 20px;']);
                           else
                                echo Html::img("https://res.cloudinary.com/plugn/image/upload/c_scale,h_60,w_60/no-image.jpg", ['style' => 'border-radius: 3px;margin-right: 20px;']);
                            ?>
                        </td>
                        <td style="vertical-align: inherit;">

                            <?= Html::a($item->item_name, ['item/update', 'id' => $item->item_uuid, 'restaurantUuid' => $item->restaurant_uuid]) ?>

                        </td>
                        <td style="vertical-align: inherit;">
                            <?= $item->sku ? '<span style="color:black;">' . $item->sku . '</span>' : '<span style="color:#637381;"> No SKU </span>'; ?>
                        </td>
                        <td style="vertical-align: inherit;">
                            <?= $item->stock_qty ?>
                        </td>
                        <td width="220px">

                            <div style=" position: relative;   display: flex;">
                                <?=
                                $form->field($item, 'stock_qty', [
                                    'options' => ['style' => 'margin: 0px'],
                                    'template' => '
                                   {input}
                                   '
                                ])->textInput(['type' => 'number', 'value' => 0, 'min' => 0, 'style' => ' border-top-right-radius: unset !important;border-bottom-right-radius: unset !important;'])->label(false)
                                ?>
                                <div style="position: relative; z-index: 10; flex: 0 0 auto;">

                                    <?=
                                    Html::submitButton('Save', ['style' => 'margin-right: 20px; border-top-left-radius: unset;   border-bottom-left-radius: unset;height: calc(1.25em + 1.4rem + 0px);', 'class' => 'btn btn-success', 'name' => $item->item_uuid])
                                    ?>

                                </div>
                            </div>

                        </td>

                        <td></td>

                    </tr>

                    <?php
                    ActiveForm::end();
                }
                ?>

            </tbody>
        </table>


    </div>
    <!-- DataTable ends -->

</section>
<!-- Data list view end -->
