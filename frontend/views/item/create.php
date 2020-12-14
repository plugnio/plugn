<?php

use yii\helpers\Html;
use common\models\ExtraOption;
use common\models\Option;

/* @var $this yii\web\View */
/* @var $model common\models\Item */

$this->params['restaurant_uuid'] = $storeUuid;

$this->title = 'Add Item';
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index', 'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="item-create">

    <?php
      if(count($categoryQuery) > 0){
        echo $this->render('_form', [
                        'modelItem' => $modelItem,
                        'categoryQuery' => $categoryQuery,
                        'modelsOption' => (empty($modelsOption)) ? [new Option] : $modelsOption,
                        'modelsExtraOption' => (empty($modelsExtraOption)) ? [[new ExtraOption]] : $modelsExtraOption,
                        'storeUuid' => $storeUuid
        ]);
      } else {


          echo'
          <div class="card">
            <div style="padding: 50px 0; text-align: center;">'
          . '     <h4 style="margin-bottom: 30px">You need to first define a category before adding items</h4>'
          . Html::a('Create category', ['category/create', 'storeUuid' => $storeUuid], ['class' => 'btn btn-success'])
          . '</div>
          </div>';

        }

    ?>

</div>
