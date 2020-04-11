<?php

use common\models\Restaurant;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = $restaurant_model->name;
?>
<div class="site-index">

    <!-- Todo -->
    <div class="jumbotron">
        <?php if ($restaurant_model->restaurant_status == Restaurant::RESTAURANT_STATUS_CLOSE) { ?>
            <h3>You're restaurant is closed!</h3>
            <p>
                <?=
                Html::a('Open', ['promote-to-open', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-success']);
                ?>
            </p>
            <?php
        } else if ($restaurant_model->restaurant_status == Restaurant::RESTAURANT_STATUS_OPEN) {
            ?>
            <h3>You're restaurant is open!</h3>
            <p>
                <?=
                Html::a('Close', ['promote-to-close', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-danger']);
                ?>
            </p>
            <?php
        }
        ?>

    </div>

</div>