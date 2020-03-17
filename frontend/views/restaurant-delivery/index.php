<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\Area;
use yii\helpers\ArrayHelper;
use common\models\RestaurantDelivery;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RestaurantDeliverySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Delivery Zones';
$this->params['breadcrumbs'][] = $this->title;



$js = "

$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

  })

";



    $this->registerJs($js);

    $areaQuery = Area::find()->asArray()->all();
    $restaurantDeliveryArray = ArrayHelper::map($areaQuery, 'area_id', 'area_name');

    if ($restaurant_model->restaurant_uuid != null) {

        $sotredRestaurantDeliveryAreas = RestaurantDelivery::find()
                ->select('area_id')
                ->asArray()
                ->where(['restaurant_uuid' => $restaurant_model->restaurant_uuid])
                ->all();

        $sotredRestaurantDeliveryAreas = ArrayHelper::getColumn($sotredRestaurantDeliveryAreas, 'area_id');
    }
    
    $form = ActiveForm::begin();

    echo $form->errorSummary($restaurant_model);

    echo $form->field($restaurant_model, 'restaurant_delivery_area')->dropDownList(
            $restaurantDeliveryArray, [
                'class' => 'select2',
                'multiple' => 'multiple',
                'value' => $sotredRestaurantDeliveryAreas
            ]
    );
?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php
    ActiveForm::end();
?>


<?php
foreach ($dataProvider->query as $city) {
    if ($city->restaurantDeliveryAreas) {
        ?>
        <div>
            <div class="card card-default">
                <div class="card-header">
                    <h1 class="card-title" style="font-size: 25px; font-weight: 500"><?= $city->city_name ?></h1>


                    <div class="card-tools">
                        <?php
                        echo Html::a(
                                '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', ['restaurant-delivery/update-delivery-time-for-city', 'city_id' => $city->city_id]
                        );
                        ?>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>

                    </div>
                </div>
                <div class="card-body">

                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Area</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($city->restaurantDeliveryAreas as $restaurantDeliveryArea) {
                                $form = ActiveForm::begin([
                                            'enableClientScript' => false,
                                ]);
                                ?>
                                <tr>
                                    <td>  
                                    </td>
                                    <td>
                                        <?= $restaurantDeliveryArea->area->area_name ?></td>
                                    <td>
                                        <?= $form->field($restaurantDeliveryArea, 'min_delivery_time')->input('number') ?></td>
                                    <td>
                                        <?= $form->field($restaurantDeliveryArea, 'delivery_fee')->input('float') ?></td>
                                    <td>
                                        <?= $form->field($restaurantDeliveryArea, 'min_charge')->input('float') ?></td>
                                    <td style="padding-top: 43px">   
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-6">
                                                    <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'name' => $restaurantDeliveryArea->area->area_id]) ?>

                                                </div>
                                                <div class="col-6">
                                                    <?=
                                                    Html::a('<i style="font-size: 21px;" class="fas fa-minus-circle"></i>', ['delete', 'area_id' => $restaurantDeliveryArea->area->area_id], ['class' => 'btn btn-danger',
                                                        'data' => [
                                                            'method' => 'post',
                                                        ]
                                                    ])
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                                ActiveForm::end();
                            }
                            ?>

                            <?php ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php
    }
}
?>