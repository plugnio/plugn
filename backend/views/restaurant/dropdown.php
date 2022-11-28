<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\RestaurantSearch */

?>

<!-- Modal -->

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Select Store

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </h5>

        </div>
        <div class="modal-body">


            <div class="agent-form">

                <?php

                $form = ActiveForm::begin([
                    'action' => ['restaurant/dropdown'],
                    'method' => 'get'
                ]);
                ?>

                <?= $form->field($searchModel, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($searchModel, 'name_ar')->textInput(['maxlength' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>

            <div class="list-wrapper">
                <?= $this->render('_dropdown_list', [
                    'dataProvider' => $dataProvider,
                ]) ?>
            </div>

        </div>
    </div>
</div>

