<?php

use common\models\Restaurant;
use yii\helpers\Html;
use common\models\Order;
use common\models\AgentAssignment;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;
?>

<style>
  .pricing__tag {
      font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      font-size: 55px;
      color: #212b36;
      font-weight: 700;
      margin: 1.6rem 0;
  }
  .pricing__currency {
    font-size: 20px;
    font-weight: 500;
    vertical-align: super;
    position: relative;
    top: -6px;
}
.pricing__period {
    display: block;
    font-size: 14px;
    font-weight: normal;
}
.next-heading--1 {
    font-size: 22px;
}
.next-heading {
font-size: 1.7rem;
font-weight: 600;
line-height: 2.4rem;
margin: 0 0 20px;
}
.pricing__description {
    max-width: 250px;
    min-height: 65px;
    margin: 0 auto;
}

.pricing__list {
    list-style: none;
    padding: 0px;
    padding-top: 0.8rem;
}
@media screen and (min-width: 640px){
  .ui-heading {
      font-size: 1.6rem;
  }
}
.ui-heading {
    font-size: 1.3rem;
    font-weight: 600;
    line-height: 2.4rem;
}
.pricing__list li {
    margin: 0px 0px 0.6em 0px;
}
</style>
<section>
  <div class="row" style="padding: 3rem 1.6rem;">
      <!-- account start -->
      <div class="col-12">

        <p style="text-align: center;font-size: 2.1rem; line-height: 3.2rem;">Pick a plan for your store</p>


      </div>

    </div>



    <div class="row" style="  padding-bottom: 3.2rem;text-align: center;width: 80%;  margin: auto;">
        <?php foreach ($availablePlans as  $plan) { ?>
            <div class="col-6">
              <div class="card">
                <div class="card-body">
                  <div class="card-content">
                    <strong class="pricing__tag">
                      <span class="pricing__currency">KWD</span><?= $plan->price ?>
                      <span class="pricing__period">per year</span>
                    </strong>

                    <?php
                      if($selectedPlan->plan_id == $plan->plan_id)
                        echo  Html::a('Current plan', ['update'], ['class' => 'btn btn-primary btn-lg disabled' , 'style'=>'    margin: 1.5rem 0;']);
                      else
                        echo  Html::a('Choose this plan', [$plan->price > 0 ? 'confirm-plan' : 'downgrade-to-free-plan',  'id' => $restaurant_model->restaurant_uuid, 'selectedPlanId' => $plan->plan_id], ['class' => 'btn btn-primary btn-lg' , 'style'=>'    margin: 1.5rem 0;']);
                    ?>

                    <h2 class="next-heading next-heading--1"><?= $plan->name ?></h2>

                    <p class="pricing__description">
                      <?= $plan->description ?>
                    </p>

                    <ul class="pricing__list">
                       <h2 class="ui-heading">Platform fee</h2>
                       <li class="">
                          <?= \Yii::$app->formatter->asPercent($plan->platform_fee, 0); ?> <br/> commission charged on each order received.
                       </li>

                    </ul>

                  </div>
                </div>
              </div>
            </div>
            <?php } ?>


    </div>
</section>
