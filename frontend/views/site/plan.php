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

        <p><a style="color: #006fbb">Compare plans</a> with different features and rates.</p>
      </div>
      <div class="col-8">
        <div class="card">
            <div class="card-body">
              <div class="row card-content">
                      <div class="col-4">
                        <b>Memeber since</b> <br/>
                        Oct 20, 2020
                      </div>
                      <div class="col-4">
                        <b>Current plan</b> <br/>
                        Free plan
                      </div>
                      <div class="col-4">
                        <b>Status</b> <br/>
                        Free plan
                      </div>
              </div>
            </div>
        </div>
      </div>

    </div>
</section>
