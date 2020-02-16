<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manage Categories';
$this->params['breadcrumbs'][] = $this->title;
?>


    <div class="page-title"> <i class="icon-custom-left"></i>
        <h3><?= Html::encode($this->title) ?></h3>
        <p>
            <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-body ">

                    <?php
                    echo
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'label' => 'Order',
                                'value' => 'sort_number'
                            ],
                            'category_name',
                            'category_name_ar',
                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                        'options' => ['class' => 'table table-stripe'],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>