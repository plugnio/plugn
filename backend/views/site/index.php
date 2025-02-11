<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $failedInPaymentQueue integer */
/* @var $pendingInPaymentQueue integer */
/* @var $purchaseDomain */
/* @var $pendingDomain */
/* @var $failedInQueue */
/* @var $failedInQueue */

$this->title = 'Admin dashboard';
?>
<div class="site-index">

    <h3>Customer service tasks</h3>
    <div class="alert alert-danger" role="alert">
        <?= $openTickets ?> tickets in CRM need attention,
        <a href="https://crm.plugn.io/login" target="_blank">Check it now! </a>
    </div>

    <h3>Operation team tasks</h3>
    <?php if($failedInPaymentQueue > 0) { ?>
        <div class="alert alert-danger" role="alert">
            <?= $failedInPaymentQueue ?> Payment Gateway Request Failed, <?= Html::a('Check it now!', ['payment-gateway-queue/index']) ?>
        </div>
    <?php } ?>

    <?php if($pendingDomain > 0) { ?>
        <div class="alert alert-warning" role="alert">
            <?= $pendingDomain ?> Domains pending to purchase,
            <?= Html::a('Check it now!', ['restaurant-domain-request/index', 'RestaurantDomainRequestSearch[status]' => 2]) ?>
        </div>
    <?php } ?>

    <?php if($pending > 0) { ?>
        <div class="alert alert-warning" role="alert">
            <?= $pending ?> Unpaid store invoices
        </div>
    <?php } ?>

    <h3>Tech Team tasks</h3>

    <?php if($failedInQueue > 0) { ?>
        <div class="alert alert-danger" role="alert">
            <?= $failedInQueue ?> Store building Failed,
            <?= Html::a('Check it now!', ['queue/index', 'QueueSearch[queue_status]' => 5]) ?>
        </div>
    <?php } ?>

    <?php if($notPublished > 0) { ?>
        <div class="alert alert-danger" role="alert">
            <?= $notPublished ?> Store not published, <?= Html::a('Check it now!', ['restaurant/index', 'RestaurantSearch[has_deployed]' => 0]) ?>
        </div>
    <?php } ?>

    <div class="jumbotron">
        <h1>Plugn Management!</h1>

        <p class="lead">Dashboard with a summary of whats going on in the project</p>

    </div>

    <div class="body-content">

        <div class="row">
        

        <div class="col-12 col-lg-4">
                <h2>Last cron run</h2>

                <span class="badge badge-light"><?= $lastCronRun ?></span>

            </div>

            <div class="col-12 col-lg-4">
                <h2>Stores</h2>

                <span class="badge badge-light"><?= $pendingInQueue ?> Pending</span>

                <span class="badge badge-warning"><?= $holdInQueue ?> Hold</span>

                <?php if($failedInQueue > 0) { ?>
                    <span class="badge badge-danger"><?= $failedInQueue ?> Failed</span>
                <?php } ?>

                <br />
                <br />

                <?= Html::a('Go &raquo', ['restaurant/index'], ['class' => 'btn btn-default']) ?>
            </div>

            <div class="col-12 col-lg-4">
                <h2>Store Domains</h2>

                <?php if($pendingDomain > 0) { ?>
                <span class="badge badge-warning"><?= $pendingDomain ?> Pending to purchase</span>
                <?php } ?>

                <?php if($purchaseDomain > 0) { ?>
                <span class="badge badge-danger"><?= $purchaseDomain ?> need to assign to store</span>
                <?php } ?>

                <br />
                <br />

                  <?= Html::a('Go &raquo', ['restaurant/index'], ['class' => 'btn btn-default']) ?>
            </div>

            <div class="col-12 col-lg-4">
                <h2>Payment Gateway Queue </h2>

                <span class="badge badge-warning"><?= $pendingInPaymentQueue ?> Pending</span>

                <?php if($failedInPaymentQueue > 0) { ?>
                    <span class="badge badge-danger"><?= $failedInPaymentQueue ?> Failed</span>
                <?php } ?>

                <br />
                <br />

                <?= Html::a('Go &raquo', ['payment-gateway-queue/index'], ['class' => 'btn btn-default']) ?>
            </div>


        </div>
        <div class="row">

            <div class="col-12 col-lg-4">
                <h2>Store Invoices</h2>

                <span class="badge badge-light"><?= $draft ?> Unpaid</span>
                <span class="badge badge-warning"><?= $pending ?> Locked</span>
                <span class="badge badge-primary"><?= $paid ?> Paid</span>

                <br />
                <br />

                <?= Html::a('Go &raquo', ['restaurant-invoice/index'], ['class' => 'btn btn-default']) ?>
            </div>

            <div class="col-12 col-lg-4">
                <h2>Subscriptions</h2>

                <span class="badge badge-primary"><?= $premiumStores ?> Premium </span>

                <br />
                <br />

                <?= Html::a('Go &raquo', ['subscription/index'], ['class' => 'btn btn-default']) ?>
            </div>

            <div class="col-12 col-lg-4">
                <h2>Order Payments</h2>

                <?= Html::a('Go &raquo', ['payment/index'], ['class' => 'btn btn-default']) ?>
            </div>

        </div>
        <div class="row">
            <div class="col-12 col-lg-4">
                <h2>Agent Assignment</h2>

                  <?= Html::a('Go &raquo', ['agent-assignment/index'], ['class' => 'btn btn-default']) ?>
            </div>

            <div class="col-12 col-lg-4">
                <h2>Partners</h2>

                  <?= Html::a('Go &raquo', ['partner/index'], ['class' => 'btn btn-default']) ?>
            </div>
            <div class="col-12 col-lg-4">
                <h2>Payable Partners</h2>

                  <?= Html::a('Go &raquo', ['partner-payout/index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>

        <div class="row">


            <div class="col-12 col-lg-4">
                <h2>Agents</h2>

                <?= Html::a('Go &raquo', ['agent/index'], ['class' => 'btn btn-default']) ?>
            </div>


            <div class="col-12 col-lg-4">
                <h2>Store Orders</h2>

                <?= Html::a('Go &raquo', ['order/index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>
</div>
