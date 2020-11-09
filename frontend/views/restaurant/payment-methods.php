<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Payment Settings';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>


<style>
    .current-plan{
        color: white;
        text-align: center;
        background: #4CAF50;
        border: 3px solid #4CAF50;
        border-top: 4px solid #4CAF50 !important;
    }
    .premium-plan{
        text-align: center;
        color: #4CAF50;
        text-decoration: underline;
        border-top: none !important;
    }
    .current-plan-header-row{
        border: solid #4CAF50;
    }
    .current-plan-body-row{
        border-right: solid #4CAF50;
        border-left: solid #4CAF50;
    }
    .current-plan-bottom-row{
        border-bottom: solid #4CAF50;
    }

    .table-wrapper{
        padding: 10px;  border: 1px solid rgb(219, 219, 219);
        border-radius: 4px;
        flex-direction: column;
        -webkit-box-pack: justify;
        justify-content: space-between;
        padding: 24px;
        margin: 24px;
    }

    .payment-method-icon{
      margin-right: 5px;
    }
</style>
<div class="restaurant-view">
    <?php if (Yii::$app->session->getFlash('error') != null) { ?>

        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fa fa-ban"></i> Warning!</h5>
            <?= (Yii::$app->session->getFlash('error')) ?>
        </div>
    <?php } elseif (Yii::$app->session->getFlash('success') != null) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fa fa-check"></i> Success!</h5>
            <?= (Yii::$app->session->getFlash('success')) ?>
        </div>
    <?php } ?>


    <!-- Online Payment  -->
    <div class="card">
        <div class="card-header">
            <h3>
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" class="mr-2"><path fill="#FFB782" d="M22.536 6.82l-4.329-4.337v17.38H24V10.34c0-1.322-.527-2.59-1.464-3.521z"></path><path fill="#DE4C3C" d="M3.383 16.695L.111 8.2c-.328-.853.096-1.81.95-2.14L16.506.112c.853-.328 1.811.097 2.14.95l3.272 8.495c.329.853-.096 1.811-.95 2.14l-15.445 5.95c-.853.328-1.81-.098-2.14-.95z"></path><path fill="#7A4930" d="M19.825 4.121L1.305 11.3l1.234 3.201 18.52-7.175-1.234-3.205z"></path><path fill="#4398D1" d="M0 10.759V1.655C0 .741.741 0 1.655 0h16.552c.914 0 1.655.741 1.655 1.655v9.104c0 .914-.741 1.655-1.655 1.655H1.655C.741 12.414 0 11.673 0 10.759z"></path><path fill="#3E8CC7" d="M18.207 0h-1.759L4.034 12.414h14.173c.914 0 1.655-.741 1.655-1.655V1.655C19.862.741 19.12 0 18.207 0z"></path><path fill="#5EB3D1" d="M1.655 6.62H3.31v.828H1.655v-.827zM1.655 9.103H3.31v.828H1.655v-.828zM9.104 9.103h1.655v.828H9.104v-.828zM4.138 6.62h1.655v.828H4.138v-.827zM6.621 6.62h1.655v.828H6.621v-.827zM9.104 6.62h1.655v.828H9.104v-.827zM16.966 1.655h.827v1.242h-.827V1.655zM15.31 1.655h.828v1.242h-.828V1.655zM13.655 1.655h.828v1.242h-.828V1.655zM12 1.655h.827v1.242H12V1.655z"></path><path fill="#88B337" d="M16.552 19.862H24V24h-7.448v-4.138z"></path><path fill="#FFB782" d="M15.337 7.475c-.701-.7-1.837-.697-2.537.005-.676.678-.7 1.769-.053 2.476l3.391 3.7c-1.02 1.783-.976 3.983.113 5.725l.3.481h5.38V14.07l-6.594-6.594z"></path><path fill="#6B962A" d="M17.793 21.104h.828v.827h-.828v-.828z"></path><path fill="#FDB62F" d="M1.655 4.303V2.317c0-.366.297-.662.662-.662h1.987c.365 0 .662.296.662.662v1.986c0 .366-.297.663-.662.663H2.317c-.365 0-.662-.297-.662-.663z"></path><path fill="#FD7B2F" d="M1.655 2.897h1.242v.827H1.655v-.827zM3.725 2.897h1.24v.827h-1.24v-.827z"></path><path fill="#F2A46F" d="M21.517 14.07c-.11 0-.215-.045-.293-.122l-1.655-1.655c-.159-.165-.154-.427.01-.586.161-.154.415-.154.575 0l1.656 1.656c.161.162.161.423 0 .585-.078.077-.183.121-.293.121z"></path></svg>
                Online Payments
            </h3>


        </div>
        <div class="card-body">

            <p style="color: black;">We partnered with Tap Payments to empower you with competitive fees and shorter settlement windows.</p>



            <div>
                <table class="table table-responsive" style="margin-bottom: 20px">
                    <thead>
                        <tr>
                            <th style="border-top: none !important;"></th>
                            <th style="border-top: none !important;"></th>
                            <th class="<?= $model->plan->plan_id == 2 ? 'current-plan' : 'premium-plan' ?>"><?= $model->plan->plan_id == 2 ? 'Current plan' : Html::a('Upgrade to premium', ['site/confirm-plan', 'id' => $model->restaurant_uuid, 'selectedPlanId' => 2 ], ['style' => 'color: #4CAF50;']) ?></th>
                            <th class="<?= $model->plan->plan_id == 1 ? 'current-plan' : '' ?>"><?= $model->plan->plan_id == 1 ? 'Current plan' : '' ?></th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>Payment Gateway</th>
                            <th>Settlement Window</th>
                            <th class="<?= $model->plan->plan_id == 2 ? 'current-plan-header-row' : '' ?>">Premium Plan Fees</th>
                            <th class="<?= $model->plan->plan_id == 1 ? 'current-plan-header-row' : '' ?>">Free Plan Fees</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">
                                <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDE2Ij4KICAgIDxnIGZpbGw9Im5vbmUiPgogICAgICAgIDxwYXRoIGZpbGw9IiMzRjUxQjUiIGQ9Ik0yNCAxMy44NjdDMjQgMTUuMDQ1IDIyLjk3NyAxNiAyMS43MTQgMTZIMi4yODZDMS4wMjMgMTYgMCAxNS4wNDUgMCAxMy44NjdWMi4xMzNDMCAuOTU1IDEuMDIzIDAgMi4yODYgMGgxOS40MjhDMjIuOTc3IDAgMjQgLjk1NSAyNCAyLjEzM3YxMS43MzR6Ii8+CiAgICAgICAgPGVsbGlwc2UgY3g9IjE1LjE0MyIgY3k9IjgiIGZpbGw9IiNGRkMxMDciIHJ4PSI1LjIzOCIgcnk9IjUuMzMzIi8+CiAgICAgICAgPHBhdGggZmlsbD0iI0ZGM0QwMCIgZD0iTTEwLjk2IDExLjJjLS4yNDMtLjMzLS40NTItLjY4NS0uNjE2LTEuMDY3aDIuNzg5Yy4xNDYtLjMzOS4yNi0uNjk1LjMzNC0xLjA2NkgxMC4wMWMtLjA3LS4zNDUtLjEwNi0uNzAxLS4xMDYtMS4wNjdoMy42NjZjMC0uMzY2LS4wMzYtLjcyMi0uMTA1LTEuMDY3SDEwLjAxYy4wNzQtLjM3LjE4OC0uNzI3LjMzNC0xLjA2NmgyLjc4OWMtLjE2NC0uMzgyLS4zNzItLjczOC0uNjE2LTEuMDY3SDEwLjk2Yy4yMjktLjMxLjQ4Ny0uNTk4Ljc3NS0uODUtLjkxNS0uNzk5LTIuMTAyLTEuMjgzLTMuNDAyLTEuMjgzQzUuNDQgMi42NjcgMy4wOTUgNS4wNTQgMy4wOTUgOHMyLjM0NSA1LjMzMyA1LjIzOCA1LjMzM2MxLjcxMyAwIDMuMjI4LS44NCA0LjE4My0yLjEzM0gxMC45NnoiLz4KICAgIDwvZz4KPC9zdmc+Cg==" width="24px" height="16px" class="payment-method-icon">
                                Mastercard
                            </th>
                            <td>
                                5 working days
                            </td>
                            <td class="<?= $model->plan->plan_id == 2 ? 'current-plan-body-row' : '' ?>">
                                2.5% per transaction, no minimum.
                            </td>
                            <td class="<?= $model->plan->plan_id == 1 ? 'current-plan-body-row' : '' ?>">
                                5% per transaction, no minimum.
                            </td>

                        </tr>
                        <tr>
                            <th scope="row">
                                <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDE2Ij4KICAgIDxnIGZpbGw9Im5vbmUiPgogICAgICAgIDxwYXRoIGZpbGw9IiMxNTY1QzAiIGQ9Ik0yNCAxMy44NjdDMjQgMTUuMDQ1IDIyLjk3NyAxNiAyMS43MTQgMTZIMi4yODZDMS4wMjMgMTYgMCAxNS4wNDUgMCAxMy44NjdWMi4xMzNDMCAuOTU1IDEuMDIzIDAgMi4yODYgMGgxOS40MjhDMjIuOTc3IDAgMjQgLjk1NSAyNCAyLjEzM3YxMS43MzR6Ii8+CiAgICAgICAgPGcgZmlsbD0iI0ZGRiI+CiAgICAgICAgICAgIDxwYXRoIGQ9Ik0zLjgzMy41MTlsLTEuNDAxIDQuMDZzLS4zNTYtMS43MTctLjM5LTEuOTMzQzEuMjQzLjg3Ny4wNjYuOTc2LjA2Ni45NzZsMS4zODcgNS4yNDZIMy4xNEw1LjQ3LjUxOEgzLjgzNHpNNS4xNjcgNi4yMjJMNi42OTkgNi4yMjIgNy42MjUuNTE5IDYuMDc0LjUxOXpNMTYuMDA0LjUxOWgtMS42MUwxMS44OCA2LjIyMmgxLjUyMWwuMzE0LS44MTRoMS45MThsLjE2My44MTRoMS4zOTNMMTYuMDA0LjUyem0tMS44NjQgMy44bC44MzQtMi4xNTYuNDM2IDIuMTU1aC0xLjI3ek05Ljc5NyAyLjE4YzAtLjMxMy4yNjUtLjU0NyAxLjAyNy0uNTQ3LjQ5NSAwIDEuMDYyLjM1IDEuMDYyLjM1bC4yNDgtMS4xOThTMTEuNDEuNTE4IDEwLjcuNTE4Yy0xLjYxIDAtMi40NC43NDktMi40NCAxLjY5NyAwIDEuNzE0IDIuMTIyIDEuNDc5IDIuMTIyIDIuMzYgMCAuMTUtLjEyMy41LTEuMDA3LjUtLjg4NyAwLTEuNDcyLS4zMTctMS40NzItLjMxN2wtLjI2NCAxLjE1cy41NjcuMzE0IDEuNjYzLjMxNGMxLjA5OCAwIDIuNjIxLS43OTkgMi42MjEtMS45NDYgMC0xLjM4LTIuMTI1LTEuNDgtMi4xMjUtMi4wOTV6IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgzLjY0IDQuNDQ0KSIvPgogICAgICAgIDwvZz4KICAgICAgICA8cGF0aCBmaWxsPSIjRkZDMTA3IiBkPSJNNS40IDhsLS40ODctMi4xOTJzLS4yMi0uNDc1LS43OTQtLjQ3NUgxLjg4UzQuNzM1IDYuMTA1IDUuNCA4eiIvPgogICAgPC9nPgo8L3N2Zz4K" width="24px" height="16px" class="payment-method-icon">
                                Visa
                            </th>
                            <td>  5 working days </td>
                            <td class="<?= $model->plan->plan_id == 2 ? 'current-plan-body-row' : '' ?>">
                                2.5% per transaction, no minimum.
                            </td>
                            <td class="<?= $model->plan->plan_id == 1 ? 'current-plan-body-row' : '' ?>">
                                5% per transaction, no minimum.
                            </td>

                        </tr>
                        <tr>
                            <th scope="row">
                                <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDE2Ij4KICAgIDxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+CiAgICAgICAgPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjE2IiBmaWxsPSIjMDc3MkI4IiByeD0iMyIvPgogICAgICAgIDxwYXRoIGZpbGw9IiNGQ0VEMjAiIGZpbGwtcnVsZT0ibm9uemVybyIgZD0iTTE4IDkuODkyTDE0Ljg4NSA3LjczIDE3LjY1NCA2IDEyLjU3NyA2IDkuODA4IDcuNzMgOS44MDggNiA2IDYgNiAxMCA5LjgwOCAxMCA5LjgwOCA3LjgzOCAxMi41NzcgOS44OTJ6Ii8+CiAgICAgICAgPHBhdGggZmlsbD0iI0Y1RjVGOSIgZD0iTTAgNUwyNCA1IDI0IDUuMiAwIDUuMnpNMCAxMS40TDI0IDExLjQgMjQgMTEuNiAwIDExLjZ6Ii8+CiAgICAgICAgPHBhdGggZmlsbD0iI0ZGRiIgZmlsbC1ydWxlPSJub256ZXJvIiBkPSJNNy42MTYgMTMuOTI3aC0uNDY5di0uNzQyYzAtLjE1Ny0uMDEtLjI1OC0uMDMtLjMwNC0uMDItLjA0Ni0uMDUyLS4wODItLjA5OC0uMTA4LS4wNDUtLjAyNS0uMDk5LS4wMzgtLjE2Mi0uMDM4LS4wODIgMC0uMTU1LjAxOC0uMjIuMDU1LS4wNjQuMDM2LS4xMDguMDg0LS4xMzIuMTQ1LS4wMjQuMDYtLjAzNi4xNzEtLjAzNi4zMzR2LjY1OEg2di0xLjQ1NGguNDM2di4yMTRjLjE1NS0uMTY0LjM1LS4yNDYuNTg0LS4yNDYuMTA0IDAgLjE5OC4wMTUuMjg0LjA0NS4wODYuMDMxLjE1LjA3LjE5NS4xMTcuMDQ0LjA0OC4wNzQuMTAyLjA5Mi4xNjIuMDE3LjA2LjAyNS4xNDYuMDI1LjI1OXYuOTAzem00LjU4Ny0uNDYzbC40NjguMDY1Yy0uMDYuMTQtLjE1NS4yNDctLjI4NS4zMi0uMTMuMDc0LS4yOTIuMTExLS40ODcuMTExLS4zMDggMC0uNTM2LS4wODMtLjY4NS0uMjQ4LS4xMTYtLjEzMi0uMTc1LS4zLS4xNzUtLjUgMC0uMjQyLjA3Ny0uNDMuMjMtLjU2Ny4xNTQtLjEzNi4zNDgtLjIwNC41ODMtLjIwNC4yNjQgMCAuNDcyLjA3LjYyNS4yMTQuMTUyLjE0My4yMjUuMzYxLjIxOS42NTZIMTEuNTJjLjAwMy4xMTQuMDQxLjIwMy4xMTQuMjY2LjA3Mi4wNjQuMTYyLjA5Ni4yNy4wOTYuMDc0IDAgLjEzNS0uMDE3LjE4Ni0uMDUuMDUtLjAzMy4wODctLjA4Ni4xMTMtLjE1OXptLjAyNy0uMzg4Yy0uMDA0LS4xMTItLjAzOS0uMTk2LS4xMDUtLjI1NC0uMDY3LS4wNTgtLjE0OC0uMDg3LS4yNDQtLjA4Ny0uMTAzIDAtLjE4Ny4wMy0uMjU0LjA5Mi0uMDY3LjA2LS4xLjE0NC0uMDk5LjI0OWguNzAyem01LjczMi0uNTYzdi4zMDdoLS4zMjF2LjU4NmMwIC4xMTkuMDAzLjE4OC4wMS4yMDcuMDA1LjAyLjAyLjAzNi4wNC4wNDkuMDIzLjAxMy4wNDkuMDE5LjA4LjAxOS4wNDQgMCAuMTA3LS4wMTIuMTg5LS4wMzdsLjA0LjI5OWMtLjExLjAzOC0uMjMzLjA1Ny0uMzcuMDU3LS4wODUgMC0uMTYyLS4wMTItLjIzLS4wMzUtLjA2Ny0uMDIzLS4xMTctLjA1My0uMTQ5LS4wOS0uMDMyLS4wMzctLjA1NC0uMDg3LS4wNjYtLjE1LS4wMS0uMDQ1LS4wMTUtLjEzNS0uMDE1LS4yNzF2LS42MzRoLS4yMTV2LS4zMDdoLjIxNXYtLjI4OGwuNDcxLS4yMjV2LjUxM2guMzJ6Ii8+CiAgICAgICAgPHBhdGggZmlsbD0iI0ZGRiIgZD0iTTIwLjA1NCAybC4yNS4zNzVoLTEuNjE5bC4yMTIuMjVoMS42NTVMMjEgM2wtLjU3Mi41aC0yLjg2M2wtLjI0OS0uMjVWNGgtMy40ODRWMi41aDIuNDg5di42MjVoLTEuMzdWMy41aDEuNzQzdi0xaC44NzFsLjU3My42MjVoMS43OTJ2LS4yNWgtMS42MThMMTcuNTY1IDJoMi40OXptLTEuODY3IDEuNjI1VjRoLS4zNzN2LS4zNzVoLjM3M3ptLjg3MiAwVjRoLS4zNzR2LS4zNzVoLjM3NHpNNS42MTggMmwtLjYyMiAxLjEyNUg5LjM1VjIuNzVoLjYyM3YuMzc1aC4zNzNWMi43NWguODcxdjFoLTYuNzJMNCAzLjEyNSA0Ljg3MSAyaC43NDd6bTcuNDY3Ljg3NXYuNUgxMS44NHYtLjVoMS4yNDV6bS0xLjk5MS0uNzV2LjVoLS40OTh2LS41aC40OTh6bS00LjczIDB2LjVoLS40OTd2LS41aC40OTh6bS42MjMgMHYuNWgtLjQ5OHYtLjVoLjQ5OHoiLz4KICAgIDwvZz4KPC9zdmc+Cg==" width="24px" height="16px" class="payment-method-icon">
                                KNET
                            </th>
                            <td>  3 working days</td>
                            <td class="<?= $model->plan->plan_id == 2 ? 'current-plan-body-row current-plan-bottom-row' : '' ?>">
                                1% per transaction, a minimum of 100 fills.
                            </td>
                            <td class="<?= $model->plan->plan_id == 1 ? 'current-plan-body-row current-plan-bottom-row' : '' ?>">
                                5% per transaction, a minimum of 200 fills.
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <?php if (!$model->is_tap_enable) { ?>
                <?php
                if (!$model->tap_queue_id)
                    echo Html::a('Create Tap account', ['create-tap-account', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-success']);
                else
                    echo "<h5>Your TAP payments account will be ready within 24 hours. We'll email you once it's ready</h5>";
                ?>
            <?php } else { ?>
                <div class="card-content">


                    <div>
                        <div class="row">

                            <div class="col-12">
                                <div class="row">

                                    <div class="col-6">
                                        <p style="margin-bottom: 1px;">Business name</p>
                                        <p style="color: black;"><?= $model->company_name ?></p>
                                    </div>

                                    <div class="col-6">
                                        <p style="margin-bottom: 1px;">Merchant ID</p>
                                        <p style="color: black;"><?= $model->merchant_id ?></p>
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <p style="margin-bottom: 1px;">IBAN</p>
                                        <p style="color: black;"><?= $model->iban ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




            <?php

            echo Html::a($isOnlinePaymentEnabled ? 'Disable online payments' : 'Enable online payments', [$isOnlinePaymentEnabled ? 'disable-online-payment' : 'enable-online-payment', 'restaurantUuid' => $model->restaurant_uuid], ['class' => $isOnlinePaymentEnabled ? 'btn btn-danger' : 'btn btn-success',
            'data' => [
                      'confirm' => $isOnlinePaymentEnabled ? 'Are you sure you want to disable online payments?' : 'Are you sure you want to enable online payments?',
                      'method' => 'post',
                  ],
          ]);

          } ?>
        </div>

    </div>

    <!-- Cash on Delivery -->
    <div class="card">
        <div class="card-header">
            <h3>
                <svg width="24" height="16" fill="none" viewBox="0 0 24 16" class="mr-2"><rect width="24" height="16" fill="#0E9347" rx="2"></rect><path fill="#3BB54A" fill-rule="evenodd" d="M1 12.455c1.32 0 2.4 1.145 2.4 2.545h17.2c0-1.4 1.08-2.545 2.4-2.545v-8.91c-1.32 0-2.4-1.145-2.4-2.545H3.4c0 1.4-1.08 2.545-2.4 2.545v8.91zM16 8c0 2.21-1.79 4-4 4s-4-1.79-4-4 1.79-4 4-4 4 1.79 4 4zM5 9c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm15-1c0 .552-.448 1-1 1s-1-.448-1-1 .448-1 1-1 1 .448 1 1z" clip-rule="evenodd"></path></svg>
                Cash on Delivery (COD)
            </h3>


        </div>
        <div class="card-body">
            <div class="card-content">
                <p style="color: black;">
                    Payments that are processed outside your online store. When a customer makes a manual payment, you need to approve their order before fulfilling.
                </p>
                <?= Html::a($isCashOnDeliveryEnabled ? 'Disable cash on delivery' : 'Enable cash on delivery', [$isCashOnDeliveryEnabled ? 'disable-cod' : 'enable-cod', 'restaurantUuid' => $model->restaurant_uuid], ['class' => $isCashOnDeliveryEnabled ? 'btn btn-danger' : 'btn btn-success',
                'data' => [
                          'confirm' => $isCashOnDeliveryEnabled ? 'Are you sure you want to disable cash on delivery?' : 'Are you sure you want to enable cash on delivery?',
                          'method' => 'post',
                      ]
                ]) ?>

            </div>
        </div>

    </div>

</div>
