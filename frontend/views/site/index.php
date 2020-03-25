<?php

use common\models\Restaurant;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <?php if ($restaurant_model->restaurant_status == Restaurant::RESTAURANT_STATUS_CLOSED) { ?>
            <h1>You're restaurant is closed!</h1>
            <p>
                <?=
                    Html::a('Open', ['promote-to-open', 'id' => $restaurant_model->restaurant_uuid],
                            ['class' => 'btn btn-success']);
                ?>
            </p>
        <?php } else if ($restaurant_model->restaurant_status == Restaurant::RESTAURANT_STATUS_OPEN) {
            ?>
            <h1>You're restaurant is open!</h1>
            <p>
                <?=
                    Html::a('Close', ['promote-to-close', 'id' => $restaurant_model->restaurant_uuid], 
                            ['class' => 'btn btn-danger']);
                ?>
            </p>
        <?php } ?>

    </div>

</div>