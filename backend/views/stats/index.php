<?php
use yii\helpers\Html;
use backend\components\ChartWidget;

$this->title = 'Statistics';

?>

<div class="site-index">
 	<div class="body-content">

    <div class="grid">
        <h3>Stats</h3>

        <?= Html::beginForm(['/stats/graph'], 'POST', ['class' => "form-inline"]); ?>

        <div class="form-group mb-2">
            <label for="date_start">Start Date</label>
            <?= Html::input('date', 'date_start', null, ["id" => "date_start", "class"=>"form-control"]); ?>
        </div>

        <div class="form-group mb-2">
            <label for="end_start">End Date</label>
            <?= Html::input('date', 'date_end', null, ["id" => "date_end", "class"=>"form-control"]); ?>
        </div>

        <div class="form-group" style="background: #f4f6f9;  margin-bottom: 0px; padding-bottom: 0px; background:#f4f6f9 ">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary mb-2']) ?>
        </div>
        <?= Html::endForm(); ?>

        <div class="row">

                    <div class="col-xl-3 col-lg-4">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total Orders Received</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $totalOrders ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Number of premium stores</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= Yii::$app->formatter->asCurrency($totalPremium, "KWD") ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Number of free stores</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $totalFreeStores ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                            <i class="fas fa-percent"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- END .col-xl-3 -->
            <div class="col-xl-3 col-lg-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Stores with payment method usage</h5>
                                <span class="h2 font-weight-bold mb-0"><?= $totalStoresWithPaymentGateway ?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- END .col-xl-3 -->

            <div class="col-xl-3 col-lg-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Free plugn domain</h5>
                                <span class="h2 font-weight-bold mb-0"><?= $totalPlugnDomain ?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- END .col-xl-3 -->

            <div class="col-xl-3 col-lg-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Custom domain</h5>
                                <span class="h2 font-weight-bold mb-0"><?= $totalCustomDomain ?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- END .col-xl-3 -->

                </div>

        <h3>Revenues</h3>
        <div class="row">

        <?php foreach ($revenues as $revenue) { ?>
               <div class="col-xl-3 col-lg-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Revenue in <?= $revenue['currency_code'] ?></h5>
                                <span class="h2 font-weight-bold mb-0">
                                            <?= Yii::$app->formatter->asCurrency($revenue['total_price'], $revenue['currency_code']) ?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                    <i class="fa fa-chart-pie"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>

        <!--Our profit margin and payment gateway margin separated from that revenue -->
        <div class="grid">
        <?php foreach($payments as $payment) { ?>

            <div class="row">

                <h3><?= $payment['currency_code'] ?></h3>
                <div class="col-xl-3 col-lg-4">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Payment gateway fees</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        <?= Yii::$app->formatter->asCurrency($payment['payment_gateway_fees'], $payment['currency_code']) ?> </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fa fa-chart-pie"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Plugn fees</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        <?= Yii::$app->formatter->asCurrency($payment['plugn_fees'], $payment['currency_code']) ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fa fa-chart-pie"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Partner fees</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        <?= Yii::$app->formatter->asCurrency($payment['partner_fees'], $payment['currency_code']) ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fa fa-chart-pie"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>

</div>            	