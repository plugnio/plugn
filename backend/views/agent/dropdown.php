<?php

use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\AgentSearch */

$js = "

";

$this->registerJs($js);
?>

<!-- Modal -->

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Agent

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </h5>

            </div>
            <div class="modal-body">


        <?= $this->render('_dropdown_list', [
            'dataProvider' => $dataProvider,
        ]) ?>


            </div>
        </div>
    </div>

