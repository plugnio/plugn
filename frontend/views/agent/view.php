<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */

$this->params['restaurant_uuid'] = $storeUuid;

$this->title = $model->agent_name;
$this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index', 'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

        <!-- page users view start -->
        <section class="page-users-view">
            <div class="row">
                <!-- account start -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Account</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="users-view-image">
                                    <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/img/avatar.jpg' ?>" class="img-fluid"  alt="alternative">

                                </div>
                                <div class="col-12 col-sm-9 col-md-6 col-lg-5">
                                    <table>
                                        <tr>
                                            <td class="font-weight-bold">Name</td>
                                            <td><?= $model->agent_name ?></td>
                                        </tr>

                                        <tr>
                                            <td class="font-weight-bold">Email</td>
                                            <td><?= $model->agent_email ?></td>
                                        </tr>

                                        <tr>
                                            <td class="font-weight-bold">Reminder Email</td>
                                            <td><?= $model->reminder_email ? 'True' : 'False'  ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-12 col-md-12 col-lg-5">
                                    <table class="ml-0 ml-sm-0 ml-lg-0">
                                        <tr>
                                            <td class="font-weight-bold">Status</td>
                                            <td>active</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Email Notification</td>
                                            <td><?= $model->email_notification ? 'True' : 'False'  ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-12" style="margin-top:20px">
                                  <?= Html::a('<i class="feather icon-edit-1"></i> Edit', ['update', 'id' => $model->agent_id, 'storeUuid' => $storeUuid], ['class' => 'btn btn-primary mr-1']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- page users view end -->
