<?php

use common\models\Restaurant;
use yii\helpers\Html;
use common\models\Order;
use common\models\AgentAssignment;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant->restaurant_uuid;
$this->title = $restaurant->name;
?>

<section>
    <div class="row d-flex vh-100 align-items-center justify-content-center">
        <div class="col-xl-5 col-md-8 col-sm-10 col-12 px-md-0 px-2">
            <div class="card text-center w-100 mb-0">

                <div class="card-content" style="margin-top: 100px;">
                    <div class="card-body pt-0">
                        <img  src="<?= Yii::$app->urlManager->getBaseUrl() . '/app-assets/images/pages/rocket.png' ?>" class="img-responsive block width-150 mx-auto" width="150" alt="bg-img">

                    </div>

                    <h3 style="padding: 10px 0px 0px;" class="mb-0">Your store will be ready in a few minutes</h3>


                      <p style="padding: 10px 21px 50px;" class="mb-0">
                        We'll be sending you an email as soon as it's ready. You can also refresh this page in a few minutes to view your store.
                        In the meantime you can start adding products and customizing your store settings.
                      </p>

                      <div  style="padding: 10px 21px 50px;" class="mb-0">
                        <?= Html::a('Back to dashboard', ['index', 'id' => $restaurant->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
                     </div>
                </div>

            </div>
        </div>
    </div>
</section>
