<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\CategoryItem;

/* @var $this yii\web\View */
/* @var $model backend\models\ItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<!-- users filter start -->
<div class="card">


    <!-- users filter start -->
    <div class="card" style="margin-bottom: 0px;">



        <div class="card-header">
            <h4 class="card-title">Filters</h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                    <li><a data-action="close"><i class="feather icon-x"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-content collapse show">


            <div class="card-body">
                <div class="users-list-filter">
                    <form>


                        <?php
                        $categoryQuery = CategoryItem::find()
                                ->innerJoin('item', 'item.item_uuid = category_item.item_uuid')
                                ->andWhere(['item.restaurant_uuid' => $restaurant_uuid])
                                ->all();


                        $categoryList = ArrayHelper::map($categoryQuery, 'category_id', 'category.title');


                        $form = ActiveForm::begin([
                                    'action' => ['item/inventory', 'storeUuid' => $restaurant_uuid],
                                    'method' => 'get',
                        ]);
                        ?>

                        <div class="row">
                            <div class="col-12 col-sm-6 col-lg-6">
                                <?= $form->field($model, 'barcode') ?>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-6">
                                <?= $form->field($model, 'sku') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6 col-lg-6">
                                <?=
                                $form->field($model, 'category_id')->dropDownList($categoryList, ['class' => 'form-control', 'prompt' => 'Select category'])->label('Category Name');
                                ?>
                            </div>

                        </div>

                        <div class="form-group">
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Reset', ['item/inventory', 'storeUuid' => $restaurant_uuid], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left: 10px;']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>

                </div>
                </form>
            </div>
        </div>

    </div>
</div>
