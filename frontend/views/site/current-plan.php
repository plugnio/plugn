<?php

use common\models\Restaurant;
use yii\helpers\Html;
use common\models\Order;
use common\models\AgentAssignment;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;
$this->title = 'Plan';
?>

<section>
  <div class="row">
      <!-- account start -->
      <div class="col-4">

        <h5>Plan details</h5>

        <p><a style="color: #006fbb"></a>
          <?= Html::a('Compare plans', ['compare-plan', 'id' => $restaurant_model->restaurant_uuid], ['style' => 'color: #006fbb']) ?>
          with different features and rates.
        </p>
      </div>
      <div class="col-8">
        <div class="card">
            <div class="card-body">
              <div class="row card-content">
                      <div class="col-4">
                        <b>Memeber since</b> <br/>
                        <?= date('M d, Y', strtotime($restaurant_model->restaurant_created_at)) ?>
                      </div>
                      <div class="col-4">
                        <b>Current plan</b> <br/>
                        <?= $subscription->plan->name ?>
                      </div>
                      <div class="col-4">
                        <b>Status</b> <br/>
                        <?= $subscription->status ?>
                      </div>
              </div>
            </div>
        </div>
      </div>

    </div>
</section>
