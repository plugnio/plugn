<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Restaurants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-view">
    <h1>
        <?= Html::encode($this->title) ?>
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
        <?= (Yii::$app->session->getFlash('successResponse')) ?>
    </div>
    <?php } ?>

    <p>

        <?=
          Html::a($model->hide_request_driver_button == 1 ? 'Display request driver button' : 'Hide request driver button',
          [
            $model->hide_request_driver_button == 1 ?  'display-request-driver-button' : 'hide-request-driver-button'
         , 'id' => $model->restaurant_uuid], ['class' => $model->hide_request_driver_button == 0 ? 'btn btn-success' : 'btn btn-danger'])
         ?>

        <?= Html::a('Update', ['update', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary btn-update']) ?>

        <?= Html::a('Toggle Debugger', ['toggle-debugger', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary btn-update']) ?>

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

        <?=
        Html::a('Delete', ['delete', 'id' => $model->restaurant_uuid], [
            'class' => 'btn btn-danger btn-delete',
            'data' => [
                'confirm' => 'Are you sure you want to delete this store?',
                'method' => 'post',
            ],
        ])
        ?>
        <?= Html::a('List Payment Method', ['restaurant-payment-method/index', 'uuid' => $model->restaurant_uuid], ['class' => 'btn btn-default']) ?>
        <br/>
        <br/>

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



        <?php if ($model->restaurant_status == Restaurant::RESTAURANT_STATUS_OPEN) { ?>
            <?=
            Html::a('Upgrade', ['upgrade', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to upgrade store to latest codebase?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php } ?>


        <?= Html::a('Update sitemap', ['update-sitemap', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-warning']) ?>

    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
          'payment_gateway_queue_id',
          [
              'label' => 'is_tap_enable',
              'value' => function ($data) {
                  return $data->is_tap_enable ? 'Yes' : 'No';
              },
              'format' => 'raw'
          ],
          [
              'label' => 'is_myfatoorah_enable',
              'value' => function ($data) {
                  return $data->is_myfatoorah_enable ? 'Yes' : 'No';
              },
              'format' => 'raw'
          ],
          'retention_email_sent',
            'tap_queue_id',
            'version',
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
            'site_id',
            'company_name',
            'name',
            'name_ar',
            'tagline',
            'tagline_ar',
            'app_id',
            'status',
            'business_type',
            'restaurant_domain',
            'thumbnail_image',
            'logo',
            'identification_file_front_side',
            'identification_file_back_side',
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
            'restaurant_created_at',
            'restaurant_updated_at',
            'platform_fee:percent',
            'warehouse_fee',
            'warehouse_delivery_charges',
            'facebook_pixil_id',
            'google_analytics_id',
            [
              'attribute' => 'Owner name',
              'format' => 'html',
              'value' => function ($data) {
                  return  $data->owner_first_name && $data->owner_last_name ? $data->owner_first_name . ' ' . $data->owner_last_name : null;
              },
            ],
            [
              'attribute' => 'owner_email',
            ],
            'owner_number',
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

            'wallet_id',
            'operator_id',

            'live_api_key',
            'test_api_key',

            'live_public_key',
            'test_public_key',

            'store_branch_name',
            'custom_css:text',

            'currency_id',
            'country_id',

            'meta_title',
            'meta_title_ar',

            'meta_description',
            'meta_description_ar',

            'phone_number_country_code',

            'license_number',
            'not_for_profit',
            'authorized_signature_file_purpose',
            'authorized_signature_file_id',
            'authorized_signature_file',
            'authorized_signature_title',
            'authorized_signature_expiry_date',
            'authorized_signature_issuing_date',

            'owner_phone_country_code',

            'identification_issuing_date',
            'identification_expiry_date',
            'identification_file_purpose',

            'armada_api_key',
            'phone_number_display',

            'store_layout',
            'commercial_license_issuing_date',
            'commercial_license_expiry_date',
            'commercial_license_file',
            'commercial_license_file_purpose',

            'show_opening_hours',

            'mashkor_branch_id',
            'schedule_interval',
            'schedule_order',

            'has_deployed',
            'tap_queue_id',

            'snapchat_pixil_id',
            'default_language',

            'enable_gift_message',
            'payment_gateway_queue_id',

            'annual_revenue',
            'referral_code',
            'custom_subscription_price',

            'is_public:boolean',
            'is_sandbox:boolean',
            'is_under_maintenance:boolean',
            'is_deleted:boolean',
            'enable_debugger:boolean',
            'accept_order_247:boolean'
        ],
    ])
    ?>


    <h2>Theme color</h2>

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
