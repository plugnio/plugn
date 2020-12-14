<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Reports';
$this->params['breadcrumbs'][] = $this->title;
// \yii\web\YiiAsset::register($this);
?>

<div class="content-body">


    <!-- apex charts section start -->
    <section id="apexchart">
        <div class="row">
            <!-- Finances -->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4 class="card-title">Orders report</h4>

                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">
                            <p>
                              View your store’s orders including sales, payments, and more.
                            </p>

                            <?= Html::a('Download report', ['order/orders-report', 'storeUuid' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4 class="card-title">Item sales</h4>

                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">
                            <p>
                              Track and understand the movement of your products.
                            </p>

                            <?= Html::a('Download historical report', ['item/export-to-excel', 'storeUuid' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Download for specific date range', ['item/items-report', 'storeUuid' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Customers -->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4 class="card-title">Customers</h4>

                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">
                            <p>
                              Gain insights into who your customers are and how they interact with your business.
                            </p>

                            <?= Html::a('Download report', ['customer/export-to-excel', 'storeUuid' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>



</div>

</section>
<!-- // Apex charts section end -->

</div>
