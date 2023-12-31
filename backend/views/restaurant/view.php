<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\RestaurantDomainRequest;
use common\models\Restaurant;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Restaurants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);

$js = "
        $(function () {
           $('#tabs li:not(.link)').click(function (e) {
                
               $('#tabs li').removeClass('active');
               $(this).toggleClass('active'); 
               
               $('.tab-content').addClass('hidden');
               $('#tab-' + $(this).attr('id')).removeClass('hidden');
           });
       });";

$this->registerJs($js);

?>
<div class="restaurant-view">
    <h1>
        <?= Html::encode($this->title) ?>
        <?php if($model->is_deleted) { ?>
        <span class="badge badge-danger">Deleted</span>
        <?php } ?>
    </h1>

    <?php if (Yii::$app->session->getFlash('errorResponse') != null) { ?>

    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-ban"></i> Error!</h5>
        <?= (Yii::$app->session->getFlash('errorResponse')) ?>
    </div>
    <?php } elseif (Yii::$app->session->getFlash('successResponse') != null) { ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-check"></i> Success!</h5>
        <?php if(is_array(Yii::$app->session->getFlash('successResponse'))) {
            echo print_r(Yii::$app->session->getFlash('successResponse'));
        } else {
            echo Yii::$app->session->getFlash('successResponse');
        } ?>
    </div>
    <?php } ?>

    <?php if (!str_contains($model->restaurant_domain, ".plugn.site") && !$model->site_id) { ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-ban"></i> Warning!</h5>
        Not uploaded to server / website not published!
    </div>
    <?php } ?>

    <p>

        <?= Html::a('Update', ['update', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary btn-update']) ?>

        <?php if($model->is_deleted) {
            echo Html::a('Undo Delete', ['undo-delete', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-warning btn-undodelete',
                /*'data' => [
                    'confirm' => 'You will need to publish site, after undo',
                    'method' => 'post',
                ],*/
            ]);

        } else {

            if (!str_contains($model->restaurant_domain, ".plugn.site") &&
                !$model->site_id)
            {
                echo Html::a('Publish', ['publish', 'id' => $model->restaurant_uuid], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'confirm' => 'Are you sure you want to publish store?',
                        'method' => 'post',
                    ],
                ]);
            }

            echo '&nbsp;'. Html::a('Delete', ['delete', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-danger btn-delete',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this store?',
                    'method' => 'post',
                ],
            ]);

        }
        ?>

        <br/>
        <br/>

        <!--
        <?= Html::a('Update sitemap', ['update-sitemap', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-warning']) ?>
-->

    </p>

    <ul class="nav nav-tabs" id="tabs">
        <li class="active" id="general">
            <a href="#">Details</a>
        </li>

        <li id="restaurantType"><a href="#">Restaurant Type</a></li>
        <li id="payment"><a href="#">Tap payment</a></li>
        <li id="fees"><a href="#">Our Fees</a></li>
        <li id="theme"><a href="#">Theme color</a></li>
        <li id="seo"><a href="#">SEO</a></li>
        <li id="analytics"><a href="#">Analytics</a></li>
        <li id="netlify"><a href="#">Website Hosting/ Netlify</a></li>
        <li id="domain" class="link"><a target="_blank" href="<?= \yii\helpers\Url::to( ['restaurant-payment-method/index', 'uuid' => $model->restaurant_uuid]) ?>">Payment Methods</a></li>
        <li id="agents" class="link"><a target="_blank" href="<?= \yii\helpers\Url::to( ['agent-assignment/index', 'AgentAssignmentSearch[restaurant_uuid]' => $model->restaurant_uuid]) ?>">Agents/ Vendors</a></li>
        <li id="invoices" class="link"><a target="_blank" href="<?= \yii\helpers\Url::to( ['restaurant-invoice/index', 'RestaurantInvoice[restaurant_uuid]' => $model->restaurant_uuid]) ?>">Invoices</a></li>
        <li id="orders" class="link"><a target="_blank" href="<?= \yii\helpers\Url::to( ['order/index', 'OrderSearch[restaurant_uuid]' => $model->restaurant_uuid]) ?>">Orders</a></li>
        <li id="settings"><a href="#">Settings</a></li>
    </ul>

    <br />

    <div id="tab-analytics" class="tab-content hidden">
        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'facebook_pixil_id',
                'google_analytics_id',
                'google_tag_id',
                'google_tag_manager_id',
                'tiktok_pixel_id',
                'snapchat_pixil_id'
            ]
        ]) ?>
    </div>

    <div id="tab-general" class="tab-content">
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ip_address',
           'retention_email_sent',
            'sitemap_require_update',
            'country.country_name',
            [
                'label' => 'Store currency',
                'value' => function ($data) {
                  return $data->currency->title;
                }
            ],
            [
                'label' => 'Hide Request driver button',
                'value' => function ($data) {
                  return $data->hide_request_driver_button  == 1 ? 'Yes' : 'No';
                }
            ],
            'restaurant_uuid',
            [
                'label' => 'Payment Methods',
                'value' => function ($data) {
                    $paymentMethods = '';

                    foreach ($data->getPaymentMethods()->all() as $key => $paymentMethod) {

                        if ($key == 0)
                            $paymentMethods .=  $paymentMethod['payment_method_name'] ;
                        else
                            $paymentMethods .= ', ' .  $paymentMethod['payment_method_name'] ;
                    }

                    return $paymentMethods;
                },
                'format' => 'raw'
            ],
            'company_name',
            'name',
            'name_ar',
            'tagline',
            'tagline_ar',
            'app_id',
            'status',
            'business_type',
            'thumbnail_image',
            'logo',
            [
                'attribute' => 'thumbnail_image',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img($data->getRestaurantThumbnailImageUrl());
                },
            ],
            [
                'attribute' => 'logo',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img($data->getRestaurantLogoUrl());
                },
            ],
            [
                 'label' => 'Support Delivery',
                 'value' => function ($data) {
                     return $data->support_delivery ? 'Yes' : 'No';
                 },
                 'format' => 'raw'
            ],
            [
                 'label' => 'Support Pick up',
                 'value' => function ($data) {
                     return $data->support_pick_up ? 'Yes' : 'No';
                 },
                 'format' => 'raw'
            ],
            'phone_number',
            'restaurant_email:email',
            'restaurant_email_notification',
            'instagram_url:url',
            'iban',
            'restaurant_created_at:datetime',
            'restaurant_updated_at:datetime',
            [
                'attribute' => 'restaurant_deleted_at',
                'format' => 'datetime',
                //'visible' => function ($data) {
                 //   return false;//!empty($data->restaurant_deleted_at)? false: true;
               // }
            ],
            'platform_fee:percent',
            'warehouse_fee',
            'warehouse_delivery_charges',
            [
              'attribute' => 'Owner name',
              'format' => 'html',
              'value' => function ($data) {
                  return  $data->owner_first_name && $data->owner_last_name ? $data->owner_first_name . ' ' . $data->owner_last_name : null;
              },
            ],
            'owner_number',

            'custom_css:text',

            'currency_id',
            'country_id',

            'phone_number_country_code',

            'owner_phone_country_code',


            'armada_api_key',
            'phone_number_display',

            'store_layout',


            'show_opening_hours',

            'mashkor_branch_id',
            'schedule_interval',
            'schedule_order',

            'default_language',

            'enable_gift_message',
            'payment_gateway_queue_id',

            'annual_revenue',
            'referral_code',
            'custom_subscription_price',


        ],
    ])
    ?>
    </div>

    <div id="tab-netlify" class="tab-content hidden">

        <?php if ($model->restaurant_status == Restaurant::RESTAURANT_STATUS_OPEN) {

            //if(str_contains($model->restaurant_domain, ".plugn.store")) {

            if(str_contains($model->restaurant_domain, ".plugn.site")) {

                echo Html::a('Downgrade', ['downgrade', 'id' => $model->restaurant_uuid], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to downgrade store to older codebase?',
                        'method' => 'post',
                    ],
                ]);

            } else if($model->site_id) {

                echo Html::a('Upgrade', ['upgrade', 'id' => $model->restaurant_uuid], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to upgrade store to latest codebase?',
                        'method' => 'post',
                    ],
                ]);
            } ?>
        <?php } ?>

        <br/>
        <br/>

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'version',
                'site_id',
                'store_branch_name',
                'has_deployed',
                'restaurant_domain',
            ]
        ]); ?>

        <?=
        GridView::widget([
            'dataProvider' => $domainRequests,
            'columns' => [
                'domain',
                [
                    'attribute' => 'status',
                    'filter' => RestaurantDomainRequest::arrStatus(),
                    'value' => function($data) {
                        return RestaurantDomainRequest::arrStatus()[$data->status];
                    }
                ],
                //'created_by',
                'created_at',
            ],
            'layout' => '{summary}<div class="card-body">{items}{pager}</div>',
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>

    </div>

    <div id="tab-settings" class="tab-content hidden">

        <?=
        Html::a($model->hide_request_driver_button == 1 ? 'Display request driver button' : 'Hide request driver button',
            [
                $model->hide_request_driver_button == 1 ?  'display-request-driver-button' : 'hide-request-driver-button'
                , 'id' => $model->restaurant_uuid], ['class' => $model->hide_request_driver_button == 0 ? 'btn btn-success' : 'btn btn-danger'])
        ?>

        <?= Html::a('Toggle Debugger', ['toggle-debugger', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary btn-update']) ?>

        <?php if ($model->restaurant_status == Restaurant::RESTAURANT_STATUS_BUSY || $model->restaurant_status == Restaurant::RESTAURANT_STATUS_CLOSED ) { ?>
            <?=
            Html::a('Open', ['promote-to-open', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Are you sure you want to change store status to open?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php } ?>

        <?php if ($model->restaurant_status == Restaurant::RESTAURANT_STATUS_OPEN) { ?>
            <?=
            Html::a('Busy', ['promote-to-busy', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to change store status to busy?',
                    'method' => 'post',
                ],
            ])
            ?>

            <?=
            Html::a('Close', ['promote-to-close', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to change store status to closed?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php } ?>
        <br/>
        <br/>

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'is_public:boolean',
                'is_sandbox:boolean',
                'is_under_maintenance:boolean',
                'is_deleted:boolean',
                'enable_debugger:boolean',
                'accept_order_247:boolean'
            ]
        ]); ?>
    </div>

    <div id="tab-seo" class="tab-content hidden">
        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'meta_title',
                'meta_title_ar',

                'meta_description',
                'meta_description_ar',
            ]
        ]); ?>
    </div>


    <div id="tab-restaurantType" class="tab-restaurantType hidden">

        <?= Html::a('Update', ['restaurant-type', 'id' => $model->restaurant_uuid], [
            'class' => 'btn btn-primary',
        ]) ?>

        <br /><br />

        <?php if($model->restaurantType) {
            echo DetailView::widget([
                'model' => $model->restaurantType,
                'attributes' => [
                    'businessCategory.business_category_en',
                    'businessType.business_type_en',
                    'merchantType.merchant_type_en',
                ],
            ]);
        } ?>

        <?=
        GridView::widget([
            'dataProvider' => $restaurantItemType,
            'columns' => [
                [
                    'label' => 'Item type',
                    'value' => 'businessItemType.business_item_type_en'
                ]
            ],
            'layout' => '{summary}<div class="card-body">{items}{pager}</div>',
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>

    </div>

    <div id="tab-payment" class="tab-content hidden">

        <?php

        if (($model->logo && !$model->logo_file_id) ||
            ($model->iban_certificate_file && !$model->iban_certificate_file_id) ||
            ($model->authorized_signature_file && !$model->authorized_signature_file_id) ||
            ($model->commercial_license_file && !$model->commercial_license_file_id) ||
            ($model->identification_file_front_side && !$model->identification_file_id_front_side) ||
            ($model->identification_file_back_side && !$model->identification_file_id_back_side)
        ) {//|| !$model->developer_id

            echo Html::a('Upload Documents', ['upload-documents-to-tap', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-primary btn-process-queue',
                'data' => [
                    'confirm' => 'Are you sure?',
                    'method' => 'post',
                ],
            ]). '&nbsp;&nbsp;';
        }

        //Create a business for a vendor on Tap if not already exists

        if (!$model->merchant_id && (!$model->business_id || !$model->business_entity_id)) {//|| !$model->developer_id

            echo Html::a('Create Business', ['create-business', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-primary btn-process-queue',
                'data' => [
                    'confirm' => 'Are you sure?',
                    'method' => 'post',
                ],
            ]). '&nbsp;&nbsp;';
        }

        //Create a merchant on Tap if not already added

        if (!$model->merchant_id) {

            echo Html::a('Create Merchant', ['create-merchant', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-primary btn-process-queue',
                'data' => [
                    'confirm' => 'Are you sure?',
                    'method' => 'post',
                ],
            ]). '&nbsp;&nbsp;';
        }

        if ($model->merchant_id && !$model->operator_id) {

            echo Html::a('Set API Keys from Merchant Details', ['fetch-merchant', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-primary btn-process-queue',
                'data' => [
                    'confirm' => 'Are you sure?',
                    'method' => 'post',
                ],
            ]). '&nbsp;&nbsp;';
        }

        if ($model->wallet_id && $model->developer_id && !$model->operator_id) {

            echo Html::a('Create An Operator', ['create-an-operator', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-primary btn-process-queue',
                'data' => [
                    'confirm' => 'Are you sure?',
                    'method' => 'post',
                ],
            ]). '&nbsp;&nbsp;';
        }

        if($model->business_id && !$model->is_tap_business_active) {
            echo Html::a('Check Tap Business Status', ['poll-tap-business-status', 'id' => $model->restaurant_uuid], [
                    'class' => 'btn btn-primary btn-process-queue',
                    'data' => [
                        'confirm' => 'Are you sure?',
                        'method' => 'post',
                    ],
                ]). '&nbsp;&nbsp;';
        }

        if($model->merchant_id && $model->tap_merchant_status != "Active") {
            echo Html::a('Check Tap Merchant Status', ['poll-tap-merchant-status', 'id' => $model->restaurant_uuid], [
                    'class' => 'btn btn-primary btn-process-queue',
                    'data' => [
                        'confirm' => 'Are you sure?',
                        'method' => 'post',
                    ],
                ]). '&nbsp;&nbsp;';
        }

        ?>

        <?=
        Html::a('Remove tap account detail', ['reset-tap', 'id' => $model->restaurant_uuid], [
            'class' => 'btn btn-danger btn-process-queue',
            'data' => [
                'confirm' => 'Are you sure you want to remove details for this store? This will not remove actual tap account.',
                'method' => 'post',
            ],
        ])
        ?>

        <?php if($model->paymentGatewayQueue && $model->paymentGatewayQueue->queue_status != \common\models\PaymentGatewayQueue::QUEUE_STATUS_COMPLETE) { ?>

            <?=
            Html::a('Remove payment gateway request', ['remove-gateway-queue', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-danger btn-process-queue',
                'data' => [
                    'confirm' => 'Are you sure you want to remove payment gateway queue for this store?',
                    'method' => 'post',
                ],
            ])
            ?>

            <?=
            Html::a('Process payment gateway request', ['process-gateway-queue', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-danger btn-process-queue',
                'data' => [
                    'confirm' => 'Are you sure you want to create payment gateway account for this store?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php } ?>

        <?php if($model->is_tap_enable) { ?>
            <?= Html::a('Test tap integration', ['restaurant/test-tap', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-default']) ?>
        <?php } ?>
        <br/>
        <br/>

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'tap_queue_id',
                [
                    'attribute' => 'owner_email',
                ],
                'payment_gateway_queue_id',
                [
                    'label' => 'is_tap_enable',
                    'value' => function ($data) {
                        return $data->is_tap_enable ? 'Yes' : 'No';
                    },
                    'format' => 'raw'
                ],
                'tap_merchant_status',
                'is_tap_business_active',
                [
                    'label' => 'is_myfatoorah_enable',
                    'value' => function ($data) {
                        return $data->is_myfatoorah_enable ? 'Yes' : 'No';
                    },
                    'format' => 'raw'
                ],
                'tap_queue_id',
                'identification_file_front_side',
                'identification_file_back_side',
                'vendor_sector',
                'supplierCode',
                'business_id',
                'developer_id',
                'business_entity_id',
                'merchant_id',

                'authorized_signature_file_id',
                'commercial_license_file_id',
                'identification_file_id_front_side',
                'identification_file_id_back_side',
                'identification_title',
                'commercial_license_title',
                'authorized_signature_title',
                'license_number',
                'not_for_profit',
                'authorized_signature_file_purpose',
                'authorized_signature_file_id',
                'authorized_signature_file',
                'authorized_signature_title',
                'authorized_signature_expiry_date',
                'authorized_signature_issuing_date',

                'wallet_id',
                'operator_id',

                'live_api_key',
                'test_api_key',

                'live_public_key',
                'test_public_key',
                'identification_issuing_date',
                'identification_expiry_date',
                'identification_file_purpose',
                'commercial_license_issuing_date',
                'commercial_license_expiry_date',
                'commercial_license_file',
                'commercial_license_file_purpose',
            ]
        ]); ?>
    </div>

    <div id="tab-theme" class="tab-content hidden">

    <div class="card">

        <?=
        GridView::widget([
            'dataProvider' => $storeThemeColors,
            'columns' => [
                'primary',
                'secondary',
                'tertiary',
                'light',
                'medium',
                'dark',
            ],
            'layout' => '{summary}<div class="card-body">{items}{pager}</div>',
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>

    </div>
    </div>

    <div id="tab-fees" class="tab-content hidden">

    <?php if(empty($payments)) { ?>
        <p>~ no orders yet ~</p>
    <?php } ?>

    <?php foreach($payments as $payment) { ?>

        <h5><?= $payment['currency_code'] ?></h5>

        <div class="row">

            <div class="col-xl-3 col-lg-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase text-muted mb-0">Payment gateway fees</h5>
                        <span class="h2 font-weight-bold mb-0">
                                        <?= Yii::$app->formatter->asCurrency($payment['payment_gateway_fees'], $payment['currency_code']) ?> </span>
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fa fa-chart-pie"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase text-muted mb-0">Plugn fees</h5>
                        <span class="h2 font-weight-bold mb-0">
                                        <?= Yii::$app->formatter->asCurrency($payment['plugn_fees'], $payment['currency_code']) ?></span>
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fa fa-chart-pie"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase text-muted mb-0">Partner fees</h5>
                        <span class="h2 font-weight-bold mb-0">
                                        <?= Yii::$app->formatter->asCurrency($payment['partner_fees'], $payment['currency_code']) ?></span>
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fa fa-chart-pie"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase text-muted mb-0">Store revenue</h5>
                        <span class="h2 font-weight-bold mb-0">
                                        <?= Yii::$app->formatter->asCurrency($payment['payment_net_amount'], $payment['currency_code']) ?></span>
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fa fa-chart-pie"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?php } ?>

    </div>

</div>
