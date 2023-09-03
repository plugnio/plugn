<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Domain';

?>
<style type="text/css">

    .card {
        border-radius: 5px;
        border: 1px solid #eee;
        background: #fff;
        margin-bottom: 15px;
    }

    .card .card-body {
        padding: 15px;
    }

    .card h5 {
        color: #32325d;
    }
    .font-weight-bold {
        font-weight: 600 !important;
    }

    a:hover {
        text-decoration: none;
    }

    a.text-success:hover,
    a.text-success:focus {
        color: #24a46d !important;
    }

    .text-warning {
        color: #fb6340 !important;
    }

    a.text-warning:hover,
    a.text-warning:focus {
        color: #fa3a0e !important;
    }

    .text-danger {
        color: #f5365c !important;
    }

    a.text-danger:hover,
    a.text-danger:focus {
        color: #ec0c38 !important;
    }

    .text-white {
        color: #fff !important;
    }

    a.text-white:hover,
    a.text-white:focus {
        color: #e6e6e6 !important;
    }

    .text-muted {
        color: #8898aa !important;
    }
    .bg-yellow {
        background-color: #ffd600 !important;
    }

    .icon {
        width: 3rem;
        height: 3rem;
    }

    .icon i {
        font-size: 2.25rem;
    }

    .icon-shape {
        display: inline-flex;
        padding: 12px;
        text-align: center;
        border-radius: 50%;
        align-items: center;
        justify-content: center;
    }

    .form-filter {
        margin: 45px 0 30px 0;
        position: relative;
    }

    .form-filter:after {
        position: absolute;
        top: -25px;
        left: 0px;
        font-size: 12px;
        font-weight: 700;
        color: #959595;
        text-transform: uppercase;
        letter-spacing: 1px;
        content: "Filter by date range";
    }
</style>

<div class="site-index">
    <div class="body-content">

        <div class="grid">
            <h3>Stats</h3>

            <?= Html::beginForm(['/stats/index'], 'GET', ['class' => "form-inline form-filter"]); ?>

            <div class="form-group mb-2">
                <label for="date_start">Start Date</label>
                <?= Html::input('date', 'date_start', $date_start, ["id" => "date_start", "class"=>"form-control"]); ?>
            </div>

            <div class="form-group mb-2">
                <label for="end_start">End Date</label>
                <?= Html::input('date', 'date_end', $date_end, ["id" => "date_end", "class"=>"form-control"]); ?>
            </div>

            <!--
            <div class="form-group mb-2">
                <label for="end_start">Country</label>
                <?= Html::dropdownList('country_id', $country_id, $countries, ["id" => "country_id", "class"=>"form-control"]); ?>
            </div>-->

            <div class="form-group" style="background: #f4f6f9;  margin-bottom: 0px; padding-bottom: 0px; background:#f4f6f9 ">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary mb-2']) ?>
            </div>

            <?= Html::a('Clear', \yii\helpers\Url::to(['stats/domain']), ['class' => 'btn btn-secondary mb-2']) ?>

            <?= Html::endForm(); ?>

            <div class="row">

                <div class="col-xl-3 col-lg-4">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-muted mb-0">Custom domain</h5>
                            <span class="h2 font-weight-bold mb-0"><?= $totalCustomDomain ?></span>
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                    </div>
                </div><!-- END .col-xl-3 -->

                <div class="col-xl-3 col-lg-4">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-muted mb-0">Free plugn domain</h5>
                            <span class="h2 font-weight-bold mb-0"><?= $totalPlugnDomain ?></span>
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                    </div>
                </div><!-- END .col-xl-3 -->


                <div class="col-xl-3 col-lg-4">
                    <a href="<?= Url::to(['restaurant-domain-request/index']) ?>">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-muted mb-0">Domain Requests</h5>
                                <span class="h2 font-weight-bold mb-0"><?= $totalDomainRequests ?></span>

                                <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div><!-- END .col-xl-3 -->

                <div class="col-xl-3 col-lg-4">
                    <a href="<?= Url::to(['restaurant-domain-request/index', 'RestaurantDomainRequestSearch[status]' => '2']) ?>">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-muted mb-0">Pending Domain Requests</h5>
                                <span class="h2 font-weight-bold mb-0"><?= $pendingDomainRequests ?></span>

                                <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                        </div></a>
                </div><!-- END .col-xl-3 -->

            </div>

        </div>
    </div>

</div>