<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel partner\models\PartnerPayoutSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pending';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="partner-payout-index">

  <h1 style="margin-bottom:20px"><?= Html::encode($this->title) ?></h1>

  <?php
  if (sizeof($payments) > 0) { ?>

  <div class="card">
      <div id="w1" class="grid-view">
          <div class="summary">Total <b><?= sizeof($payments) ?> </b> <?= sizeof($payments) > 1 ? 'items' : 'item' ?> items.</div>
          <table class="table table-striped table-bordered">
              <thead>
                  <tr>
                      <th>
                        <a>Date</a>
                      </th>
                      <th>
                        <a>Store</a>
                      </th>
                      <th>
                        <a>Amount</a>
                      </th>
                  </tr>
              </thead>
              <tbody>


                  <tr>
                      <td>Total</td>
                      <td></td>
                      <td><?= Yii::$app->formatter->asCurrency($totalEanings , 'KWD',[ \NumberFormatter::MIN_FRACTION_DIGITS => 4, \NumberFormatter::MAX_FRACTION_DIGITS => 4 ]); ?></td>
                  </tr>



                <?php

                  foreach ($payments as $key => $payment) { ?>

                    <tr>
                        <td><?= date('M d, Y, h:i A', strtotime($payment->payment_created_at)) ?></td>
                        <td><?= $payment->restaurant->name ?></td>
                        <td><?= Yii::$app->formatter->asCurrency($payment->partner_fee , 'KWD',[ \NumberFormatter::MIN_FRACTION_DIGITS => 4, \NumberFormatter::MAX_FRACTION_DIGITS => 4 ]); ?></td>
                    </tr>


                  <?php  } ?>

              </tbody>
          </table>
      </div>
  </div>
<?php  } ?>




</div>
