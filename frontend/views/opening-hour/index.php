<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opening Hours';
$this->params['breadcrumbs'][] = $this->title;

$this->params['restaurant_uuid'] = $restaurantUuid;


?>
<?php
if ($dataProvider->getCount() > 0) {
    ?>
    <div class="col-12">
    <p>
        <?= Html::a('Edit Delivery Zones', ['update', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-success']) ?>
    </p>
  </div>
    <?php
    echo 'ddd';
    }
 else {
    echo'<div class="card"><div style="padding: 70px 0; text-align: center;">'
    . '     <h4>You currently do not have any places you deliver to</h4>'
    . Html::a('Setup Delivery Zones', ['create', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-success'])
    . '</div></div>';
}
?>
